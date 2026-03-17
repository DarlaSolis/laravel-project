<div>
    <!-- Header -->
    <div class="mb-6">
        <div class="text-sm text-gray-500 mb-2">Dashboard / Citas / Nuevo</div>
        <h1 class="text-2xl font-bold text-gray-900">Nuevo</h1>
    </div>

    <!-- main wrapper -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">
        
        <!-- Left Side: Search + Results (col-span-2) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Search Card -->
            <div class="bg-white shadow rounded-lg border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-1">Buscar disponibilidad</h2>
                <p class="text-sm text-gray-500 mb-4">Encuentra el horario perfecto para tu cita.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fecha</label>
                        <input type="date" wire:model.defer="searchDate" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        @error('searchDate') <span class="text-xs text-red-600 mt-1 block">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Hora</label>
                        <select wire:model.defer="searchTime" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">Cualquier hora</option>
                            @foreach($timeRanges as $range)
                                <option value="{{ $range }}">{{ $range }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Especialidad (opcional)</label>
                        <select wire:model.defer="searchSpecialty" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">Todas</option>
                            @foreach($specialties as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="flex justify-end">
                    <button wire:click="searchAvailability" class="w-full md:w-auto bg-blue-600 text-white font-medium rounded-lg text-sm px-8 py-2.5 hover:bg-blue-700 transition-colors">
                        Buscar disponibilidad
                    </button>
                </div>
            </div>

            <!-- Doctor Cards -->
            @foreach($availableDoctors as $docData)
                <div class="bg-white shadow rounded-lg border border-gray-100 p-6">
                    <div class="flex items-center mb-6 pb-6 border-b border-gray-100">
                        <!-- Avatar circle with Initials -->
                        @php
                            $names = explode(' ', trim($docData['doctor']->user->name));
                            $initials = '';
                            if(count($names) >= 2) {
                                $initials = strtoupper(substr($names[0], 0, 1) . substr($names[1], 0, 1));
                            } else {
                                $initials = strtoupper(substr($names[0], 0, 2));
                            }
                        @endphp
                        <div class="w-14 h-14 rounded-full bg-blue-50 flex items-center justify-center text-blue-600 font-bold text-xl mr-4 flex-shrink-0">
                            {{ $initials }}
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Dr(a). {{ $docData['doctor']->user->name }}</h3>
                            <p class="text-sm text-blue-600 font-medium">{{ $docData['doctor']->specialty->name }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-gray-600 mb-3">Horarios disponibles:</p>
                        <div class="flex flex-wrap gap-3">
                            @foreach($docData['slots'] as $slot)
                                <button 
                                    wire:click="selectSlot({{ $docData['doctor']->id }}, '{{ $docData['doctor']->user->name }}', '{{ $slot }}')"
                                    class="px-6 py-2 rounded-lg text-sm font-medium transition-colors {{ $doctor_id == $docData['doctor']->id && $start_time == $slot ? 'bg-blue-600 text-white shadow-md' : 'bg-blue-200 text-white hover:bg-blue-500' }}">
                                    {{ $slot }}:00
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
            
            @if(empty($availableDoctors))
                <div class="text-center py-12 bg-white rounded-lg shadow border border-gray-100 text-gray-500">
                    <i class="fa-solid fa-calendar-xmark text-4xl mb-3 text-gray-300"></i>
                    <p>No se encontraron horarios disponibles para los criterios de búsqueda.</p>
                </div>
            @endif
        </div>

        <!-- Right Side: Resumen (col-span-1) -->
        <div class="lg:col-span-1 sticky top-6">
            <div class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-6">Resumen de la cita</h2>
                
                <div class="space-y-4 mb-8 text-sm">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Doctor:</span>
                        <span class="font-medium text-gray-900 text-right">{{ $selectedDoctorName ? 'Dr(a). ' . $selectedDoctorName : '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Fecha:</span>
                        <span class="font-medium text-gray-900">{{ $date ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Horario:</span>
                        <span class="font-medium text-gray-900">{{ $start_time ? $start_time.':00 - '.$end_time.':00' : '-' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">Duración:</span>
                        <span class="font-medium text-gray-900">{{ $start_time ? '15 minutos' : '-' }}</span>
                    </div>
                </div>

                <form wire:submit.prevent="save">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Paciente</label>
                        <select wire:model="patient_id" class="mt-1 bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="">Seleccione un paciente...</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->user->name }}</option>
                            @endforeach
                        </select>
                        @error('patient_id') <span class="text-xs text-red-600 block mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Motivo de la cita</label>
                        <textarea wire:model="reason" rows="3" class="mt-1 bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder=""></textarea>
                        @error('reason') <span class="text-xs text-red-600 block mt-1">{{ $message }}</span> @enderror
                        @error('doctor_id') <span class="text-xs text-red-600 block mt-1">Debe seleccionar un horario disponible.</span> @enderror
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 rounded-lg transition-colors text-sm" {{ !$doctor_id ? 'disabled opacity-50 cursor-not-allowed' : '' }}>
                        Confirmar cita
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
