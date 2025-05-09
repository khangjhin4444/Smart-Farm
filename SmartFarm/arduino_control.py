import serial
import sys

# Kết nối với Arduino qua cổng serial
ser = serial.Serial('COM9', 9600)  # Thay 'COM8' bằng cổng thực tế

device = sys.argv[1]  # Nhận tên thiết bị từ dòng lệnh
state = sys.argv[2]  # Nhận trạng thái (on/off) từ dòng lệnh

# Gửi tín hiệu đến Arduino
if device == "light":
    if state == "on":
        ser.write(b"light_on")  # Bật đèn
    elif state == "off":
        ser.write(b"light_off")  # Tắt đèn
elif device == "water":
    if state == "on":
        ser.write(b"water_on")  # Tưới nước
    elif state == "off":
        ser.write(b"water_off")  # Dừng tưới nước

ser.close()
