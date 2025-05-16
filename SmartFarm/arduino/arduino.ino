#include <Wire.h>
#include <BH1750.h>
#include <LiquidCrystal_I2C.h>
#include <DHT.h>

// Khởi tạo cảm biến ánh sáng BH1750
BH1750 lightMeter;

// Khởi tạo LCD 16x2, địa chỉ 0x27, kích thước 16x2
LiquidCrystal_I2C lcd(0x27, 16, 2);

// Khởi tạo cảm biến DHT11
#define DHTPIN 2     // Chân kết nối DHT11 với Arduino (D2)
#define DHTTYPE DHT11   // Loại cảm biến DHT11
DHT dht(DHTPIN, DHTTYPE);

// Khai báo chân Máy bơm
int pumpPin = 8; 
// Khai báo chân LED đơn
int ledPin = 13;  // Chân D13 để điều khiển LED đơn

// Biến để theo dõi trạng thái hiển thị
bool showTempHum = true;  // true: hiển thị nhiệt độ/độ ẩm, false: hiển thị ánh sáng

void setup() {
  Wire.begin(); // Khởi động I2C (SDA = A4, SCL = A5)

  Serial.begin(9600);
  Serial.println("Khởi động...");

  lcd.init();        // Khởi động LCD
  lcd.backlight();   // Bật đèn nền

  pinMode(pumpPin, OUTPUT); // Thiết lập chân Máy bơm làm OUTPUT
  pinMode(ledPin, OUTPUT);  // Thiết lập chân LED làm OUTPUT

  if (lightMeter.begin(BH1750::CONTINUOUS_HIGH_RES_MODE)) {
    Serial.println("BH1750 khởi động thành công.");
  } else {
    Serial.println("Lỗi kết nối BH1750, kiểm tra dây!");
    while (1); // Dừng chương trình nếu lỗi
  }

  dht.begin();  // Khởi động cảm biến DHT11
}

unsigned long lastSendTime = 0;     // Thời điểm cuối cùng gửi dữ liệu
const unsigned long interval = 5000; // Khoảng thời gian gửi (10s)

void loop() {
  unsigned long currentMillis = millis();

  // Kiểm tra nếu đã đến lúc gửi dữ liệu
  if (currentMillis - lastSendTime >= interval) {
    lastSendTime = currentMillis;

    // Đọc dữ liệu cảm biến
    float lux = lightMeter.readLightLevel();
    float humidity = dht.readHumidity();
    float temperature = dht.readTemperature();

    if (isnan(humidity) || isnan(temperature)) {
      Serial.println("Lỗi đọc cảm biến DHT11");
    } else {
      // Gửi dữ liệu ra Serial
      Serial.print("Nhiet do: ");
      Serial.print(temperature);
      Serial.println(" C");

      Serial.print("Do am: ");
      Serial.print(humidity);
      Serial.println(" %");

      Serial.print("Anh sang: ");
      Serial.print(lux);
      Serial.println(" lux");
    }

    // Cập nhật LCD
    lcd.clear();
    if (showTempHum) {
      lcd.setCursor(0, 0);
      lcd.print("Temp: ");
      lcd.print(temperature);
      lcd.print(" C");
      lcd.setCursor(0, 1);
      lcd.print("Hum: ");
      lcd.print(humidity);
      lcd.print(" %");
    } else {
      lcd.setCursor(0, 0);
      lcd.print("Anh sang: ");
      lcd.print(lux);
      lcd.print(" lux");
    }

    // Đổi trạng thái hiển thị
    showTempHum = !showTempHum;
  }

  // Luôn luôn kiểm tra lệnh từ Serial
  if (Serial.available()) {
    String command = Serial.readStringUntil('\n');
    command.trim();

    if (command == "light_on") {
      digitalWrite(ledPin, HIGH);
      Serial.println("[ARDUINO] Đã nhận lệnh bật đèn");
    } else if (command == "light_off") {
      digitalWrite(ledPin, LOW);
      Serial.println("[ARDUINO] Đã nhận lệnh tắt đèn");
    } else if (command == "water_on") {
      digitalWrite(pumpPin, HIGH);
      Serial.println("[ARDUINO] Đã nhận lệnh bật máy bơm");
    } else if (command == "water_off") {
      digitalWrite(pumpPin, LOW);
      Serial.println("[ARDUINO] Đã nhận lệnh tắt máy bơm");
    }
  }
}