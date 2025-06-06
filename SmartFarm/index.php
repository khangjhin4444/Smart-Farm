<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Farm Dashboard</title>
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body { font-family: 'Be Vietnam Pro', sans-serif; }
    </style>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <!-- <div class="container-fluid p-4">
        <div class="row">
            <div class="col-4 bg-light">
                <h2 class=" mb-6">Smart Farm Controls</h2>
                
                <div class="mb-4">
                    <h3 class="text-lg font-semibold">Water Pump</h3>
                    <button id="waterBtn" disable onclick="controlDevice('water')" class="btn btn-primary px-4 py-2 rounded on" style="width: 130px;">On</button>
                    <label class="flex items-center mt-2">
                        <input type="checkbox" id="autoWater" onchange="toggleAuto('water')" class="mr-2">
                        Auto Mode (Humidity < 50%)
                    </label>
                </div>
                
                <div class="mb-4">
                    <h3 class="text-lg font-semibold">Light</h3>
                    <button id="lightBtn" disable onclick="controlDevice('light')" class="btn btn-warning px-4 py-2 rounded on" style="width: 130px;">On</button>
                    <label class="flex items-center mt-2">
                        <input type="checkbox" id="autoLight" onchange="toggleAuto('light')" class="mr-2">
                        Light (Light < 200 lux)
                    </label>
                </div>
                
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
                </div>
                <div class="chart-container position-relative">
                    <canvas id="humidityChart"></canvas>
                </div>
                <div class="chart-container position-relative">
                    <canvas id="lightChart"></canvas>
                </div>
            </div>
        </div>
        
    </div> -->
    <section class="vh-100 pt-3 px-4 container-fluid" style="background-color:#E3F0E5">
        <div class="row">
            <div class="col col-md-3">
                <h2 class="title">Controllers</h2>
                <div class="row d-flex gap-3 align-items-center mb-3"  style="padding:0px 12px;">
                    <div class="col control-section">
                        <div class="type-section">
                            <i class="bi bi-droplet-half" style="font-size: 1.2em;"></i>
                            <p>Water Pump</p>
                        </div>
                        <div class="form-check form-switch d-flex align-items-center justify-content-center" style="font-size: 3em;">
                            <input class="form-check-input" type="checkbox" role="switch" id="waterSwitch" onclick="controlDevice('water')">
                        </div> 
                        <div class="form-check justify-content-start d-flex gap-2" style="font-size: 1.2em;">
                            <input class="form-check-input" type="checkbox" value="" id="autoWater" style="border: 1px solid green;" onchange="toggleAuto('water')">
                            <label class="form-check-label" for="autoWater">
                                Auto
                            </label>
                        </div>
                    </div>
                    <div class="col control-section">
                        <div class="type-section">
                            <i class="bi bi-brightness-high-fill" style="font-size: 1.2em;"></i>
                            <p>Light</p>
                        </div>
                        <div class="form-check form-switch d-flex align-items-center justify-content-center" style="font-size: 3em;">
                            <input class="form-check-input" type="checkbox" role="switch" id="lightSwitch" onclick="controlDevice('light')">
                        </div> 
                        <div class="form-check justify-content-start d-flex gap-2" style="font-size: 1.2em;">
                            <input class="form-check-input" type="checkbox" value="" id="autoLight" style="border: 1px solid green;" onchange="toggleAuto('light')">
                            <label class="form-check-label" for="autoLight">
                                Auto
                            </label>
                        </div>
                    </div>
                </div>

                <h2 class="title">Camera Feed</h2>
                <div class="camera-section">
                    <img class="img-fluid liveImage" src="" alt="" srcset="">
                    <div id="alertBox" style="color: red; font-weight: bold; display: none;">
                        ⚠️ WARNING: DISEASE DETECTED!
                    </div>
                </div>
            </div>
            <div class="col col-md-9 px-5">
                <h2 class="title">Sensor Data</h2>
                <div class="chartContainer">
                    <div id="temp_chart" style="width: 100%; height: 100%"></div>
                </div>
                <div class="chartContainer">
                    <div id="humidity_chart" style="width: 100%; height: 100%"></div>
                </div>
                <div class="chartContainer">
                    <div id="light_chart" style="width: 100%; height: 100%"></div>
                </div>
                
            </div>
        </div>
    </section>
    
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        var tempChart, tempData, tempOptions;
        var humidityChart, humidityData, humidityOptions;
        var lightChart, lightData, lightOptions;
        var tempDataArray = [];
        var humidityDataArray = [];
        var lightDataArray = [];
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawTempChart);
        google.charts.setOnLoadCallback(drawHumidityChart);
        google.charts.setOnLoadCallback(drawLightChart);

        function drawTempChart() {
            tempDataArray = [['0', 0]];
            var chartData = [['Time', 'Temperature']];
            chartData = chartData.concat(tempDataArray);
            
            tempData = google.visualization.arrayToDataTable(chartData);

            tempOptions = {
                colors: ['#ff2626'], // Orange color similar to the image
                title: 'Temperature Over Time',
                curveType: 'function',
                legend: { position: 'bottom' }
            };

            tempChart = new google.visualization.LineChart(document.getElementById('temp_chart'));

            tempChart.draw(tempData, tempOptions);
        }
        function drawHumidityChart() {
            humidityDataArray = [['0', 0]];
            var chartData = [['Time', 'Humidity']];
            chartData = chartData.concat(humidityDataArray);
    
            humidityData = google.visualization.arrayToDataTable(chartData);
            humidityOptions = {
                title: 'Humidity Levels',
                curveType: 'function',
                legend: { position: 'bottom' }
            };

            humidityChart = new google.visualization.LineChart(document.getElementById('humidity_chart'));

            humidityChart.draw(humidityData, humidityOptions);
        }
        function drawLightChart() {
            lightDataArray = [['0', 0]];
            var chartData = [['Time', 'Light']];
            chartData = chartData.concat(lightDataArray);
    
            lightData = google.visualization.arrayToDataTable(chartData);

            lightOptions = {
                colors: ['#ffcc00'], // Yellow color similar to the image
                title: 'Light Levels',
                curveType: 'function',
                legend: { position: 'bottom' }
            };

            lightChart = new google.visualization.LineChart(document.getElementById('light_chart'));

            lightChart.draw(lightData, lightOptions);
        }

        function updateTempChart(newTime, newTemp) {
            tempDataArray.push([newTime, newTemp]);
            
            // Giữ chỉ 10 phần tử mới nhất
            if (tempDataArray.length > 10) {
                tempDataArray.shift();
            }
            
            var chartData = [['Time', 'Temperature']];
            chartData = chartData.concat(tempDataArray);
            
            tempData = google.visualization.arrayToDataTable(chartData);
            tempChart.draw(tempData, tempOptions);
        }

        function updateHumidityChart(newTime, newHumidity) {
            humidityDataArray.push([newTime, newHumidity]);
            
            if (humidityDataArray.length > 10) {
                humidityDataArray.shift();
            }
            
            var chartData = [['Time', 'Humidity']];
            chartData = chartData.concat(humidityDataArray);
            
            humidityData = google.visualization.arrayToDataTable(chartData);
            humidityChart.draw(humidityData, humidityOptions);
        }

        function updateLightChart(newTime, newLight) {
            lightDataArray.push([newTime, newLight]);
            
            if (lightDataArray.length > 10) {
                lightDataArray.shift();
            }
            
            var chartData = [['Time', 'Light']];
            chartData = chartData.concat(lightDataArray);
            
            lightData = google.visualization.arrayToDataTable(chartData);
            lightChart.draw(lightData, lightOptions);
        }


        let waterCheck = document.getElementById('autoWater');
        let lightCheck = document.getElementById('autoLight');
        waterCheck.addEventListener('change', function() {
            if (this.checked) {
                document.getElementById('waterSwitch').disabled = true;
            }
            else {
                document.getElementById('waterSwitch').disabled = false;
                let water_btn = document.getElementById(`waterSwitch`)
                let xml_2 = new XMLHttpRequest();
                xml_2.open("GET", `get_device_state.php?device=water`, true);
                xml_2.onload = function() {
                    if (xml_2.status == 200) {
                        let response_2 = JSON.parse(xml_2.responseText);
                        if (response_2.status == 'on') {
                            water_btn.checked = true;
                        } else {
                            water_btn.checked = false;
                        }
                    }
                };
                xml_2.send();
            }    
        });
        lightCheck.addEventListener('change', function() {
            if (this.checked) {
                document.getElementById('lightSwitch').disabled = true;
            }
            else {
                document.getElementById('lightSwitch').disabled = false;
                let light_btn = document.getElementById(`lightSwitch`)
                let xml = new XMLHttpRequest();
                xml.open("GET", `get_device_state.php?device=light`, true);
                xml.onload = function() {
                    if (xml.status == 200) {
                        let response = JSON.parse(xml.responseText);
                        if (response.status == 'on') {
                            light_btn,checked = true;
                        } else {
                            light_btn.checked = false;
                        }
                    }
                };
                xml.send();
            }
        });

        // // Fetch sensor data every 30 seconds
        function fetchSensorData() {
            fetch('get_sensor_data.php')
                .then(response => response.json())
                .then(data => {
                    // Update charts
                    const time = new Date().toLocaleTimeString();
                    updateTempChart(time, Number(data.temp));
                    updateHumidityChart(time, Number(data.humidity));
                    updateLightChart(time, Number(data.light));
                });
        }

        // // Control devices
        function controlDevice(device) {
            console.log("Control device: ", device);
            let re_state = '';
            let btn = document.getElementById(`${device}Switch`);
            
            let xml = new XMLHttpRequest();
            xml.open("GET", `get_device_state.php?device=${device}`, true);
            xml.onload = function() {
                if (xml.status == 200) {
                    let response = JSON.parse(xml.responseText);
                    console.log(response);
                    re_state = response.status;
                    console.log("re_state: ", re_state);
                    let state = re_state == 'on' ? 'off' : 'on';
                    console.log("state: ", state);
                    if (state == 'on') {
                        btn.checked = true;

                    } else {
                        btn.checked = false;
                    }
                        fetch('control_device.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify({device: device, state: state})
                        })
                        .then(response => response.json())
                        .then(data => {
                        });
                    
                }
            };
            xml.send();
            
        }

        // // Toggle auto mode
        function toggleAuto(device) {
            const state = document.getElementById(`auto${device.charAt(0).toUpperCase() + device.slice(1)}`).checked;
            auto_dict[device] = state;
            fetch('toggle_auto.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ device: device, state: state })
            });
        }

        // // Initial fetch and set interval
        fetchSensorData();
        setInterval(fetchSensorData, 5000);

        function updateImage() {
            console.log("Updating image...");
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