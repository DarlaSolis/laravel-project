<div>
    <div class="mb-6">
        <div class="text-sm text-gray-500 mb-2">
            Dashboard / Horarios
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Horarios - Dr. {{ $doctor->user->name }}</h1>
    </div>

    <div class="bg-white rounded-lg shadow border border-gray-200">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900">Gestor de horarios</h2>
            <div class="flex items-center space-x-3">
                <button type="button" wire:click="toggleEverything" class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fa-solid fa-list-check mr-2"></i> Marcar toda la semana
                </button>
                <x-button wire:click="saveSchedules" primary>
                    <i class="fa-solid fa-save mr-2"></i> Guardar horario
                </x-button>
            </div>
        </div>

        <div class="p-0 overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-600">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b">
                    <tr>
                        <th class="px-6 py-4 font-medium text-gray-700">DÍA/HORA</th>
                        @foreach($days as $dayId => $dayName)
                            <th class="px-4 py-4 font-medium text-gray-700 text-center">
                                {{ $dayName }}
                                <br>
                                <button type="button" wire:click="toggleFullDay({{ $dayId }})" class="mt-1 text-xs text-blue-600 hover:text-blue-800 hover:underline">
                                    <i class="fa-solid fa-check-double mr-1"></i>Todos
                                </button>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($groupedTimeSlots as $hour => $slotsInHour)
                        <tr class="hover:bg-gray-50 align-top">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                {{ $hour }}:00
                            </td>

                            @foreach($days as $dayId => $dayName)
                                <td class="px-4 py-4 min-w-[150px] border-l border-gray-100">
                                    <div class="space-y-3">
                                        @foreach($slotsInHour as $slotInfo)
                                            <div class="flex items-center">
                                                <input id="slot_{{ $dayId }}_{{ str_replace(':', '', $slotInfo['value']) }}" 
                                                       type="checkbox" 
                                                       wire:model="selectedSlots.{{ $dayId }}.{{ $slotInfo['value'] }}"
                                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 cursor-pointer">
                                                <label for="slot_{{ $dayId }}_{{ str_replace(':', '', $slotInfo['value']) }}" 
                                                       class="ml-2 text-sm text-gray-700 cursor-pointer">
                                                    {{ $slotInfo['label'] }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
