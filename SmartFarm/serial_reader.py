# serial_reader.py
import serial
import mysql.connector
import re
import datetime

# Kết nối tới COM6 (cổng kết nối từ Arduino)
ser = serial.Serial('COM9', 9600)

# Kết nối MySQL
db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",  
    database="smart_farm"
)
cursor = db.cursor()

buffer = ""

while True:
    if ser.in_waiting > 0:
        line = ser.readline().decode().strip()
        buffer += line + "\n"

        if "lux" in line:
            print("Nhận được gói dữ liệu:")
            print(buffer)

            # Tách dữ liệu bằng regex
            match = re.search(r"Nhiet do: ([\d.]+) C.*?Do am: ([\d.]+) %.*?Anh sang: (\d+) lux", buffer, re.DOTALL)
            if match:
                temp = float(match.group(1))
                humi = float(match.group(2))
                light = int(match.group(3))

                # Lưu vào MySQL
                timestamp = datetime.datetime.now()

                sql = """
                UPDATE sensor_data 
                SET temperature = %s, humidity = %s, light = %s, timestamp = %s 
                WHERE id = 1
                """
                cursor.execute(sql, (temp, humi, light, timestamp))
                db.commit()

                print(f"Đã lưu: Temp={temp}, Humi={humi}, Light={light}")

            else:
                print("Không phân tích được dữ liệu!")

            buffer = ""  # reset để đọc gói mới
