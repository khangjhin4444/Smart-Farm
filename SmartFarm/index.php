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
                    <h3 class="text-lg font-semibold">Water Pump</h3>
                    <button id="waterBtn" disable onclick="controlDevice('water')" class="btn btn-primary px-4 py-2 rounded on" style="width: 130px;">On</button>
                    <label class="flex items-center mt-2">
                        <input type="checkbox" id="autoWater" onchange="toggleAuto('water')" class="mr-2">
                        Auto Mode (Humidity < 50%)
                    </label>
                </div>
                
                <!-- Lighting Control -->
                <div class="mb-4">
                    <h3 class="text-lg font-semibold">Light</h3>
                    <button id="lightBtn" disable onclick="controlDevice('light')" class="btn btn-warning px-4 py-2 rounded on" style="width: 130px;">On</button>
                    <label class="flex items-center mt-2">
                        <input type="checkbox" id="autoLight" onchange="toggleAuto('light')" class="mr-2">
                        Light (Light < 200 lux)
                    </label>
                </div>
                
                <!-- Current Sensor Data -->
                <div id="sensorData" class="mt-6">
                    <h3 class="text-lg font-semibold">Current Data</h3>
                    <p>Temperature: <span id="temp">...</span> °C</p>
                    <p>Humidity: <span id="humidity">...</span> %</p>
                    <p>Light: <span id="light">...</span> lux</p>
                </div>

                <div class="mt-6 ">
                    <img class="img-fluid liveImage" src="" alt="" srcset="">
                    <div id="alertBox" style="color: red; font-weight: bold; display: none;">
                    ⚠️ WARNING: DISEASE DETECTED!
                    </div>
                </div>
            </div>
            <div class="col-8">
                <h2 class="mb-6">Smart Farm Dashboard</h2>
                <div class="chart-container position-relative">
                    <canvas id="tempChart"></canvas>
                    <!-- <div class="tempOverlay">
                        <button class="btn btn-outline-danger">Low Temperature</button>
                    </div> -->
                </div>
                <div class="chart-container position-relative">
                    <canvas id="humidityChart"></canvas>
                    <!-- <div class="humidityOverlay">
                        <button class="btn btn-outline-danger">Low Humidity</button>
                    </div> -->
                </div>
                <div class="chart-container position-relative">
                    <canvas id="lightChart"></canvas>
                    <!-- <div class="lightOverlay">
                        <button class="btn btn-outline-danger">Low Light</button>
                    </div> -->
                </div>
            </div>
        </div>
        
    </div>

    <script>
        let waterCheck = document.getElementById('autoWater');
        let lightCheck = document.getElementById('autoLight');
        waterCheck.addEventListener('change', function() {
            if (this.checked) {
                document.getElementById('waterBtn').disabled = true;
            }
            else {
                document.getElementById('waterBtn').disabled = false;
                let water_btn = document.getElementById(`waterBtn`)
                let xml_2 = new XMLHttpRequest();
                xml_2.open("GET", `get_device_state.php?device=water`, true);
                xml_2.onload = function() {
                    if (xml_2.status == 200) {
                        let response_2 = JSON.parse(xml_2.responseText);
                        if (response_2.status == 'on') {
                            water_btn.classList.remove('off');
                            water_btn.classList.add('on');
                            water_btn.textContent = `Off`;
                        } else {
                            water_btn.classList.remove('on');
                            water_btn.classList.add('off');
                            water_btn.textContent = `On`;
                        }
                    }
                };
                xml_2.send();
            }    
        });
        lightCheck.addEventListener('change', function() {
            if (this.checked) {
                document.getElementById('lightBtn').disabled = true;
            }
            else {
                document.getElementById('lightBtn').disabled = false;
                let light_btn = document.getElementById(`lightBtn`)
                let xml = new XMLHttpRequest();
                xml.open("GET", `get_device_state.php?device=light`, true);
                xml.onload = function() {
                    if (xml.status == 200) {
                        let response = JSON.parse(xml.responseText);
                        if (response.status == 'on') {
                            light_btn.classList.remove('off');
                            light_btn.classList.add('on');
                            light_btn.textContent = `Off`;
                        } else {
                            light_btn.classList.remove('on');
                            light_btn.classList.add('off');
                            light_btn.textContent = `On`;
                        }
                    }
                };
                xml.send();
            }
        });
        // Chart initialization
        const tempChart = new Chart(document.getElementById('tempChart'), {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Temperature (°C)',
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
                    label: 'Humidity (%)',
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
                    label: 'Light (lux)',
                    data: [],
                    borderColor: 'rgba(255, 206, 86, 1)',
                    fill: false
                }]
            },
            options: { responsive: true, scales: { y: { beginAtZero: true } } }
        });

        let auto_dict = {
            'water': false,
            'light': false
        };

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
                    // if (document.getElementById('autoWater').checked) {
                    //     controlDevice('water');
                    // }
                    // if (document.getElementById('autoLight').checked) {
                    //     controlDevice('light');
                    // }
                });
        }

        // Control devices
        function controlDevice(device) {
            let re_state = '';
            let btn = document.getElementById(`${device}Btn`)
            // let newState = btn.classList.contains('on') ? 'off' : 'on';

            // // Cập nhật UI ngay lập tức
            // if (newState === 'off') {
            //     btn.classList.remove('on');
            //     btn.classList.add('off');
            //     btn.textContent = `On`;
            // } else {
            //     console.log("VAO ON");
            //     btn.classList.remove('off');
            //     btn.classList.add('on');
            //     btn.textContent = `Off`;
            // }
            
            let xml = new XMLHttpRequest();
            xml.open("GET", `get_device_state.php?device=${device}`, true);
            xml.onload = function() {
                if (xml.status == 200) {
                    let response = JSON.parse(xml.responseText);
                    console.log(response);
                    re_state = response.status;
                    console.log("re_state: ", re_state);
                    // if (re_state == 'on') {
                    //     btn.classList.remove('off');
                    //     btn.classList.add('on');
                    //     btn.textContent = `Off`;
                    // } else {
                    //     btn.classList.remove('on');
                    //     btn.classList.add('off');
                    //     btn.textContent = `On`;
                        
                    // }
                    let state = re_state == 'on' ? 'off' : 'on';
                    // let state = btn.classList.contains('on') ? 'off' : 'on';
                    console.log("state: ", state);
                    if (state == 'on') {
                        btn.classList.remove('on');
                        btn.classList.add('off');
                        btn.textContent = `Off`;

                    } else {
                        btn.classList.remove('off');
                        btn.classList.add('on');
                        btn.textContent = `On`;
                    }
                    // if (auto_dict[device] == false) {
                        fetch('control_device.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({device: device, state: state})
                        })
                        .then(response => response.json())
                        .then(data => {
                            // alert(data.message);
                        });
                    // }
                    
                }
            };
            xml.send();
            
        }

        // Toggle auto mode
        function toggleAuto(device) {
            const state = document.getElementById(`auto${device.charAt(0).toUpperCase() + device.slice(1)}`).checked;
            auto_dict[device] = state;
            fetch('toggle_auto.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ device: device, state: state })
            });
        }

        // Initial fetch and set interval
        fetchSensorData();
        setInterval(fetchSensorData, 5000);

        function updateImage() {
            fetch('get_current_image.php')
                .then(res => res.json())
                .then(data => {
                if (data.filename) {
                    const img = document.querySelector('.liveImage');
                    const alertBox = document.getElementById('alertBox');
                    
                    img.src = data.filename + '?time=' + new Date().getTime(); // tránh cache

                    if (data.status === 'disease') {
                    alertBox.style.display = 'block';
                    } else {
                    alertBox.style.display = 'none';
                    }
                }
                });
        }

        setInterval(updateImage, 3000); // cập nhật mỗi 3 giây
        updateImage();
    </script>
</body>
</html>