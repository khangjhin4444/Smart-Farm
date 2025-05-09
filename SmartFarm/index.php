<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Farm Dashboard</title>
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Arial', sans-serif; }
        .container { display: flex; height: 100vh; }
        .left-panel { width: 30%; padding: 20px; background-color: #f3f4f6; }
        .right-panel { width: 70%; padding: 20px; }
        .chart-container { margin-bottom: 20px; }
    </style>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <div class="container-fluid p-4">
        <div class="row">
            <div class="col-4 bg-light">
                <h2 class=" mb-6">Smart Farm Controls</h2>
                
                <!-- Watering Control -->
                <div class="mb-4">
                    <h3 class="text-lg font-semibold">Máy Bơm Nước</h3>
                    <button id="waterBtn" onclick="controlDevice('water')" class="btn btn-primary px-4 py-2 rounded on" style="width: 130px;">Bật</button>
                    <label class="flex items-center mt-2">
                        <input type="checkbox" id="autoWater" onchange="toggleAuto('water')" class="mr-2">
                        Chế độ tự động (Độ ẩm < 30%)
                    </label>
                </div>
                
                <!-- Lighting Control -->
                <div class="mb-4">
                    <h3 class="text-lg font-semibold">Đèn Chiếu Sáng</h3>
                    <button id="lightBtn" onclick="controlDevice('light')" class="btn btn-warning px-4 py-2 rounded on" style="width: 130px;">Bật</button>
                    <label class="flex items-center mt-2">
                        <input type="checkbox" id="autoLight" onchange="toggleAuto('light')" class="mr-2">
                        Chế độ tự động (Ánh sáng < 200 lux)
                    </label>
                </div>
                
                <!-- Current Sensor Data -->
                <div id="sensorData" class="mt-6">
                    <h3 class="text-lg font-semibold">Dữ liệu hiện tại</h3>
                    <p>Nhiệt độ: <span id="temp">...</span> °C</p>
                    <p>Độ ẩm: <span id="humidity">...</span> %</p>
                    <p>Ánh sáng: <span id="light">...</span> lux</p>
                </div>
            </div>
            <div class="col-8">
                <h2 class="mb-6">Smart Farm Dashboard</h2>
                <div class="chart-container position-relative">
                    <canvas id="tempChart"></canvas>
                    <div class="tempOverlay">
                        <button class="btn btn-outline-danger">Low Temperature</button>
                    </div>
                </div>
                <div class="chart-container position-relative">
                    <canvas id="humidityChart"></canvas>
                    <div class="humidityOverlay">
                        <button class="btn btn-outline-danger">Low Humidity</button>
                    </div>
                </div>
                <div class="chart-container position-relative">
                    <canvas id="lightChart"></canvas>
                    <div class="lightOverlay">
                        <button class="btn btn-outline-danger">Low Light</button>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

    <script>
        // Chart initialization
        const tempChart = new Chart(document.getElementById('tempChart'), {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Nhiệt độ (°C)',
                    data: [],
                    borderColor: 'rgba(255, 99, 132, 1)',
                    fill: false
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });

        const humidityChart = new Chart(document.getElementById('humidityChart'), {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Độ ẩm (%)',
                    data: [],
                    borderColor: 'rgba(54, 162, 235, 1)',
                    fill: false
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true, max: 100 } } }
        });

        const lightChart = new Chart(document.getElementById('lightChart'), {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Ánh sáng (lux)',
                    data: [],
                    borderColor: 'rgba(255, 206, 86, 1)',
                    fill: false
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });

        // Fetch sensor data every 30 seconds
        function fetchSensorData() {
            fetch('get_sensor_data.php')
                .then(response => response.json())
                .then(data => {
                    // Update current sensor data
                    document.getElementById('temp').textContent = data.temp;
                    document.getElementById('humidity').textContent = data.humidity;
                    document.getElementById('light').textContent = data.light;
                    if (data.temp < 15) {
                        document.querySelector('.tempOverlay').style.display = 'flex';
                    } else {
                        document.querySelector('.tempOverlay').style.display = 'none';
                    }
                    if (data.humidity < 50) {
                        document.querySelector('.humidityOverlay').style.display = 'flex';
                    } else {
                        document.querySelector('.humidityOverlay').style.display = 'none';
                    }
                    if (data.light < 10000) {
                        document.querySelector('.lightOverlay').style.display = 'flex';
                    } else {
                        document.querySelector('.lightOverlay').style.display = 'none';
                    }
                    // Update charts
                    const time = new Date().toLocaleTimeString();
                    tempChart.data.labels.push(time);
                    tempChart.data.datasets[0].data.push(data.temp);
                    humidityChart.data.labels.push(time);
                    humidityChart.data.datasets[0].data.push(data.humidity);
                    lightChart.data.labels.push(time);
                    lightChart.data.datasets[0].data.push(data.light);

                    // Keep only last 20 data points
                    if (tempChart.data.labels.length > 20) {
                        tempChart.data.labels.shift();
                        tempChart.data.datasets[0].data.shift();
                        humidityChart.data.labels.shift();
                        humidityChart.data.datasets[0].data.shift();
                        lightChart.data.labels.shift();
                        lightChart.data.datasets[0].data.shift();
                    }

                    tempChart.update();
                    humidityChart.update();
                    lightChart.update();

                    // Auto mode checks
                    if (document.getElementById('autoWater').checked && data.humidity < 30) {
                        controlDevice('water');
                    }
                    if (document.getElementById('autoLight').checked && data.light < 200) {
                        controlDevice('light');
                    }
                });
        }

        // Control devices
        function controlDevice(device) {
            let btn = document.getElementById(`${device}Btn`)
            let state = btn.classList.contains('on') ? 1 : 0;
            if (btn.classList.contains('on')) {
                btn.classList.remove('on');
                btn.classList.add('off');
                btn.textContent = `Tắt`;

            } else {
                btn.classList.remove('off');
                btn.classList.add('on');
                btn.textContent = `Bật`;
            }
            fetch('control_device.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({device: device, state: state})
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
            });
        }

        // Toggle auto mode
        function toggleAuto(device) {
            const state = document.getElementById(`auto${device.charAt(0).toUpperCase() + device.slice(1)}`).checked;
            fetch('toggle_auto.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ device: device, state: state })
            });
        }

        // Initial fetch and set interval
        fetchSensorData();
        setInterval(fetchSensorData, 10000);
    </script>
</body>
</html>