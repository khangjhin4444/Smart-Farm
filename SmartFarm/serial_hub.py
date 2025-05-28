import time
# import random
import serial
import mysql.connector
import re
import datetime
import threading
import socket
import queue

# Thiết lập cổng COM (giả lập hoặc thực tế)
ser = serial.Serial('COM7', 9600, timeout=1)  # Thay COM9 bằng cổng thực tế

# Hàng đợi chứa lệnh từ PHP gửi xuống
command_queue = queue.Queue()

# Kết nối MySQL
db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",  # Điền mật khẩu nếu có
    database="smart_farm"
)
cursor = db.cursor()

# ===== 1. GỬI DỮ LIỆU MÔ PHỎNG TỪ "ARDUINO" QUA SERIAL =====
# def arduino_simulator():
#     while True:
#         temp = round(random.uniform(25, 40), 1)
#         humi = round(random.uniform(40, 60), 1)
#         light = random.randint(5000, 20000)
#         data = f"Nhiet do: {temp} C\nDo am: {humi} %\nAnh sang: {light} lux\n"
#         ser.write(data.encode())
#         print("[SIM] Gửi dữ liệu cảm biến:")
#         print(data)
#         time.sleep(30)

# ===== 2. ĐỌC SERIAL VÀ GHI VÀO DATABASE =====
def serial_reader():
    buffer = ""
    while True:
        if ser.in_waiting > 0:
            line = ser.readline().decode(errors='ignore').strip()
            buffer += line + "\n"
            print(f"[SERIAL] Nhận dữ liệu: {line}")
            if "lux" in line:
                print("[RECEIVED] Nhận được gói dữ liệu:")
                print(buffer)

                match = re.search(
                    r"Nhiet do: ([\d.]+) C.*?Do am: ([\d.]+) %.*?Anh sang: ([\d.]+) lux",
                    buffer, re.DOTALL
                )
                if match:
                    temp = float(match.group(1))
                    humi = float(match.group(2))
                    light = float(match.group(3))
                    timestamp = datetime.datetime.now()

                    sql = """
                    UPDATE sensor_data 
                    SET temperature = %s, humidity = %s, light = %s, timestamp = %s 
                    WHERE id = 1
                    """
                    cursor.execute(sql, (temp, humi, light, timestamp))
                    db.commit()
                    print(f"[DB] Đã lưu: Temp={temp}, Humi={humi}, Light={light}")
                else:
                    print("[ERROR] Không phân tích được dữ liệu!")

                buffer = ""

# ===== 3. LẮNG NGHE LỆNH TỪ PHP QUA SOCKET =====
def socket_server():
    host = '127.0.0.1'
    port = 65432
    with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as s:
        s.bind((host, port))
        s.listen()
        print(f"[SOCKET] Đang chờ lệnh từ PHP tại {host}:{port}...")
        while True:
            conn, addr = s.accept()
            with conn:
                command = conn.recv(1024).decode().strip()
                if command:
                    print(f"[SOCKET] Nhận lệnh từ PHP: {command}")
                    # Cập nhật chế độ tự động
                    if command.startswith("auto_"):
                        parts = command.split("_")
                        device = parts[1]
                        mode = parts[2]
                        auto_modes[device] = (mode == "on")
                        print(f"[AUTO_MODE] {device} => {'ON' if auto_modes[device] else 'OFF'}")
                    # if "light_on\n" in command:
                    #     status["light"] = True
                    # elif "light_off\n" in command:
                    #     status["light"] = False
                    # elif "water_on\n" in command:
                    #     status["water"] = True
                    # elif "water_off\n" in command:
                    #     status["water"] = False

                    command_queue.put(command)

# ===== 4. XỬ LÝ GỬI LỆNH ĐIỀU KHIỂN RA SERIAL =====
def command_dispatcher():
    while True:
        if not command_queue.empty():
            cmd = command_queue.get()
            ser.write((cmd + "\n").encode())
            print(f"[CONTROL] Gửi lệnh tới Arduino: {cmd}")

# ===== 5. Điều khiển auto mode =====
auto_modes = {
    "light": False,
    "water": False
}

# status = {
#     "light": False,
#     "water": False
# }

# Ngưỡng điều khiển
thresholds = {
    "light": {"floor": 230, "ceil": 250},  # đơn vị: lux
    "water": {"floor": 50, "ceil": 60},        # đơn vị: %
    "temp":  {"floor": 30, "ceil": 32}         # đơn vị: độ C
}

# Thread kiểm tra và điều khiển theo auto mode
def auto_controller():
    while True:
        cursor.execute("SELECT temperature, humidity, light FROM sensor_data WHERE id = 1")
        row = cursor.fetchone()
        if row:
            temp, humi, light = row

            if auto_modes["light"]:
                if light < thresholds["light"]["floor"]:
                    # if not status["light"]:
                        ser.write(b"light_on\n")
                        # status["light"] = True
                      
                        sql = """
                        UPDATE device_status 
                        SET Status = 'on' 
                        WHERE Name = 'light'
                        """
                        cursor.execute(sql)
                        db.commit()

                        print("[AUTO] Bật đèn do ánh sáng thấp")
                elif light > thresholds["light"]["ceil"]:
                    # if status["light"]:
                    #     status["light"] = False
                        ser.write(b"light_off\n")

                        sql = """
                        UPDATE device_status 
                        SET Status = 'off' 
                        WHERE Name = 'light'
                        """
                        cursor.execute(sql)
                        db.commit()

                        print("[AUTO] Tắt đèn do ánh sáng cao")

            if auto_modes["water"]:
                if humi < thresholds["water"]["floor"] or temp > thresholds["temp"]["ceil"]:
                    # if not status["water"]:
                    #     status["water"] = True
                        ser.write(b"water_on\n")

                        sql = """
                        UPDATE device_status 
                        SET Status = 'on' 
                        WHERE Name = 'water'
                        """
                        cursor.execute(sql)
                        db.commit()

                        print("[AUTO] Bật tưới do độ ẩm thấp")
                elif humi > thresholds["water"]["ceil"] or temp < thresholds["temp"]["floor"]:
                    # if status["water"]:
                    #     status["water"] = False
                        ser.write(b"water_off\n")

                        sql = """
                        UPDATE device_status 
                        SET Status = 'off' 
                        WHERE Name = 'water'
                        """
                        cursor.execute(sql)
                        db.commit()

                        print("[AUTO] Tắt tưới do độ ẩm cao")
        time.sleep(10)


# ===== CHẠY TẤT CẢ CÁC THREAD =====
# threading.Thread(target=arduino_simulator, daemon=True).start()
threading.Thread(target=serial_reader, daemon=True).start()
threading.Thread(target=socket_server, daemon=True).start()
threading.Thread(target=command_dispatcher, daemon=True).start()
threading.Thread(target=auto_controller, daemon=True).start()

# ===== GIỮ CHƯƠNG TRÌNH LUÔN CHẠY =====
while True:
    time.sleep(1)
