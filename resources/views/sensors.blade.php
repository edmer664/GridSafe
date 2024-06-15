<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sensors') }}
        </h2>
    </x-slot>

    <div class="container mx-auto p-5 grid md:grid-cols-4 grid-cols-1 gap-5">

        @foreach ($nodes as $node)
        <a href="{{ route('sensor.view', $node->node_id) }}" class="hover:shadow-lg">

            <div
                class="p-5 rounded shadow 
            @if (\App\Models\SensorData::isGenerationSensor($node->node_id)) bg-blue-200
                
            @else
            {{ \App\Models\SensorData::hasAnomaly($node->node_id) ? 'bg-red-200' : 'bg-green-200' }} @endif
            ">
                <h3 class="text-2xl font-semibold text-gray-800 leading-tight mb-5">
                    {{ $node->node_id }}
                </h3>
                <p class="text-sm text-gray-600">
                    <strong
                        class="text-xs"
                    >10 min avg</strong>
                    <ul
                        class="text-xs"
                    >
                        @php
                            $start = now()->subMinutes(10);
                            $end = now();
                        @endphp
                        <li>
                            Voltage: {{ \App\Models\SensorData::averageVoltage($node->node_id, $start, $end) }} V
                        </li>
                        <li>
                            Current: {{ \App\Models\SensorData::averageAmpere($node->node_id, $start, $end) }} A
                        </li>
                        <li>
                            Temperature: {{ \App\Models\SensorData::averageEquipmentTemperature($node->node_id, $start, $end) }} °C
                        </li>

                    </ul>
                </p>
            </div>
        </a>
        @endforeach

    </div>

</x-app-layout>
