import time
import random
import serial
import threading

# Mở cổng COM (giống Arduino kết nối)
ser = serial.Serial('COM8', 9600, timeout=1)  # Thay COM8 bằng cổng đúng

def read_serial():
    """Luồng phụ để đọc dữ liệu gửi đến từ máy tính (host)."""
    while True:
        if ser.in_waiting > 0:
            incoming = ser.readline().decode(errors='ignore').strip()
            if incoming:
                print(f"[PC gửi -> Arduino] {incoming}")
        time.sleep(0.1)

# Bắt đầu luồng đọc
read_thread = threading.Thread(target=read_serial, daemon=True)
read_thread.start()

# Gửi dữ liệu cảm biến định kỳ
while True:
    temp = round(random.uniform(25, 40), 1)
    humi = round(random.uniform(40, 60), 1)
    light = random.randint(5000, 20000)
    data = f"Nhiet do: {temp} C\nDo am: {humi} %\nAnh sang: {light} lux\n"
    
    ser.write(data.encode())
    print("[Arduino gửi]")
    print(data)
    time.sleep(5)
