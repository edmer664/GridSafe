<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl 
        leading-tight">
            {{ $sensorData->first()->node_id }}
        </h2>
    </x-slot>

    <div class="container mx-auto m-10">
        @if ($sensorData->first()->is_generation_sensor)
            @if (\App\Models\SensorData::isLowGeneration($sensorData->first()->node_id))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Warning!</strong>
                    <span class="block sm:inline">The generation rate is low.</span>
                </div>
            @endif
        @else
            @if (\App\Models\SensorData::hasAnomaly($sensorData->first()->node_id))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Warning!</strong>
                    <span class="block sm:inline">An anomaly has been detected.</span>
                </div>
            @endif
        @endif
    </div>

    <div class="container mx-auto p-5 grid md:grid-cols-2 grid-cols-1 gap-5">

        <div class="relative bg-white shadow p-5 rounded">
            <h2 class="text-2xl font-semibold text-gray-800 leading-tight mb-5">

                {{ $sensorData->first()->is_generation_sensor ? 'Generated Power' : 'Consumption Rate' }}
            </h2>
            <p>
                This chart shows the power over time of the sensor.
            </p>
            <canvas id="powerChart"></canvas>
        </div>

        <div class="relative bg-white shadow p-5 rounded">


            <h2 class="text-2xl font-semibold text-gray-800 leading-tight mb-5">
                Voltage
            </h2>
            <p class="text-sm text-gray-600">
                This chart shows the voltage over time of the sensor.
            </p>
            <canvas id="voltageChart"></canvas>
        </div>

        <div class="relative bg-white p-5 rounded shadow">
            <h2 class="text-2xl font-semibold text-gray-800 leading-tight mb-5">
                Current
            </h2>
            <p class="text-sm text-gray-600">
                This chart shows the current over time of the sensor.
            </p>
            <canvas id="ampereChart"></canvas>

        </div>

        <div class="relative bg-white p-5 rounded shadow">
            <h2 class="text-2xl font-semibold text-gray-800 leading-tight mb-5">
                Temperature
            </h2>
            <p class="text-sm text-gray-600">
                This chart shows the temperature over time of the sensor.
            </p>
            <canvas id="temperatureChart"></canvas>
        </div>
    </div>


    @push('scripts')
        <script>
            function groupAndAverageData(sensorData) {
                const groupedData = {};

                sensorData.forEach(data => {
                    // Parse the timestamp and format it to the minute
                    const date = dateFns.parseISO(data.created_at);
                    const minuteKey = dateFns.format(date, 'HH:mm');

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

            const voltageChartContext = document.getElementById('voltageChart').getContext('2d');
            const sensorData = groupAndAverageData(@json($sensorData));

            const voltageChart = new Chart(voltageChartContext, {
                type: 'line',
                data: {
                    labels: sensorData.map(data => data.created_at),
                    datasets: [{
                        label: 'Voltage',
                        data: sensorData.map(data => data.voltage),
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            const powerChartContext = document.getElementById('powerChart').getContext('2d');
            const powerChart = new Chart(powerChartContext, {
                type: 'line',
                data: {
                    labels: sensorData.map(data => data.created_at),
                    datasets: [{
                        label: 'Power (kW)', // Assuming power is in watts (W
                        data: sensorData.map(data => data.power),
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            const ampereChartContext = document.getElementById('ampereChart').getContext('2d');
            const ampereChart = new Chart(ampereChartContext, {
                type: 'line',
                data: {
                    labels: sensorData.map(data => data.created_at),
                    datasets: [{
                        label: 'Ampere',
                        data: sensorData.map(data => data.ampere),
                        backgroundColor: 'rgba(255, 206, 86, 0.2)',
                        borderColor: 'rgba(255, 206, 86, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            const temperatureChartContext = document.getElementById('temperatureChart').getContext('2d');
            const temperatureChart = new Chart(temperatureChartContext, {
                type: 'line',
                data: {
                    labels: sensorData.map(data => data.created_at),
                    datasets: [{
                        label: 'Equipment Temperature',
                        data: sensorData.map(data => data.equipment_temperature),
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }, {
                        label: 'Ambient Temperature',
                        data: sensorData.map(data => data.ambient_temperature),
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    @endpush


    </x-layout-app>
