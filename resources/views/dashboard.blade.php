<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="container mx-auto p-5">
        <h1 class="text-2xl font-semibold text-gray-800 leading-tight mb-5">
            Welcome to GridSafe
        </h1>
        <p class="text-gray-600">
            This is a dashboard displaying the overview of the sensor data connected to the GridSafe system.
        </p>
    </div>
    <div class="container mx-auto p-5 my-20 grid grid-cols-1 md:grid-cols-2 gap-4">

        <div class="relative bg-white p-5 rounded shadow">
            <h2 class="text-2xl font-semibold text-gray-800 leading-tight mb-5">
                Average Voltage
            </h2>
            <p class="text-sm text-gray-600">
                This chart shows the average voltage over time of sensors stationed grid substations.
            </p>
            <canvas id="voltageChart"></canvas>
        </div>
        <div class="relative bg-white p-5 rounded shadow">
            <h2 class="text-2xl font-semibold text-gray-800 leading-tight mb-5">
                Average Current

            </h2>
            <p class="text-sm text-gray-600">
                This chart shows the average current over time of sensors stationed grid substations.

            </p>
            <canvas id="ampereChart"></canvas>
        </div>
        <div class="relative bg-white p-5 rounded shadow">
            <h2 class="text-2xl font-semibold text-gray-800 leading-tight mb-5">
                Average Temperature
            </h2>
            <p class="text-sm text-gray-600">
                This chart shows the average temperature over time of sensors stationed grid substations.
            </p>
            <canvas id="temperatureChart"></canvas>
        </div>

        <div class="relative bg-white p-5 rounded shadow">
            <h2 class="text-2xl font-semibold text-gray-800 leading-tight mb-5">
                Average Power Generated
            </h2>
            <p class="text-sm text-gray-600">
                This chart shows the average power generated over time entering the grid.
            </p>
            <canvas id="powerGeneratedChart"></canvas>
        </div>

    </div>

    @push('scripts')
        <script>
            function groupAndAverageData(sensorData) {
                const groupedData = {};

                sensorData.forEach(data => {
                    // Parse the timestamp and format it to the minute
                    const date = dateFns.parseISO(data.created_at);
                    const minuteKey = dateFns.format(date, 'yyyy-MM-dd HH:mm');

                    // Initialize the group if it doesn't exist
                    if (!groupedData[minuteKey]) {
                        groupedData[minuteKey] = {
                            count: 0,
                            voltageSum: 0,
                            ampereSum: 0,
                            equipmentTemperatureSum: 0,
                            ambientTemperatureSum: 0,
                            power: 0
                        };
                    }

                    // Accumulate the sums and count
                    groupedData[minuteKey].count += 1;
                    groupedData[minuteKey].voltageSum += data.voltage;
                    groupedData[minuteKey].ampereSum += data.ampere;
                    groupedData[minuteKey].equipmentTemperatureSum += data.equipment_temperature;
                    groupedData[minuteKey].ambientTemperatureSum += data.ambient_temperature;
                    groupedData[minuteKey].power += data.power;
                });

                // Calculate the averages
                const averagedData = Object.keys(groupedData).map(minuteKey => {
                    const group = groupedData[minuteKey];
                    return {
                        created_at: minuteKey,
                        voltage: group.voltageSum / group.count,
                        ampere: group.ampereSum / group.count,
                        equipment_temperature: group.equipmentTemperatureSum / group.count,
                        ambient_temperature: group.ambientTemperatureSum / group.count,
                        power: group.power
                    };
                });

                return averagedData;
            }


            // Convert Laravel data to JavaScript
            var sensorData = groupAndAverageData(@json($sensorData));
            var generationData = groupAndAverageData(@json($generationData));




            // Prepare data for charts
            var labels = sensorData.map(data => data.created_at); // Assuming created_at is your timestamp field
            var voltageData = sensorData.map(data => data.voltage);
            var ampereData = sensorData.map(data => data.ampere);
            var equipmentTemperatureData = sensorData.map(data => data.equipment_temperature);
            var ambientTemperatureData = sensorData.map(data => data.ambient_temperature);

            // Power Generated Data
            var powerGeneratedData = generationData.map(data => data.power / 1000);
            var powerGeneratedLabels = generationData.map(data => data.created_at);

            // Voltage Chart
            var ctx = document.getElementById('voltageChart').getContext('2d');
            var voltageChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Voltage',
                        data: voltageData,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        fill: false
                    }]
                },
                options: {
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'minute'
                            }
                        }
                    }
                }
            });

            // Ampere Chart
            var ctx = document.getElementById('ampereChart').getContext('2d');
            var ampereChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Ampere',
                        data: ampereData,
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1,
                        fill: false
                    }]
                },
                options: {
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'minute'
                            }
                        }
                    }
                }
            });

            // Temperature Chart
            var ctx = document.getElementById('temperatureChart').getContext('2d');
            var temperatureChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                            label: 'Equipment Temperature',
                            data: equipmentTemperatureData,
                            borderColor: 'rgba(255, 99, 132, 1)',
                            borderWidth: 1,
                            fill: false
                        },
                        {
                            label: 'Ambient Temperature',
                            data: ambientTemperatureData,
                            borderColor: 'rgba(54, 162, 235, 1)',
                            borderWidth: 1,
                            fill: false
                        }
                    ]
                },
                options: {
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'minute'
                            }
                        }
                    }
                }
            });

            // Power Generated Chart
            var ctx = document.getElementById('powerGeneratedChart').getContext('2d');
            var powerGeneratedChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: powerGeneratedLabels,
                    datasets: [{
                        label: 'Power Generated (kW)',
                        data: powerGeneratedData,
                        borderColor: 'rgba(255, 206, 86, 1)',
                        borderWidth: 1,
                        fill: false
                    }]
                },
                options: {
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'minute'
                            }
                        }
                    }
                }
            });
        </script>
    @endpush
</x-app-layout>
