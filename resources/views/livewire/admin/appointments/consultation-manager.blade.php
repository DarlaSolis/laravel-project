<div x-data="{ showHistoryModal: false, showConsultationsModal: false }">
    <div class="mb-6 flex justify-between items-start">
        <div>
            <div class="text-sm text-gray-500 mb-2">Dashboard / Citas / Consulta</div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $appointment->patient->user->name }}</h1>
            <p class="text-sm text-gray-500">DNI: {{ $appointment->patient->user->id_number }}</p>
        </div>
        <div class="flex space-x-2">
            <button @click="showHistoryModal = true" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="fa-solid fa-notes-medical mr-2"></i> Ver Historia
            </button>
            <button @click="showConsultationsModal = true" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                <i class="fa-solid fa-clock-rotate-left mr-2"></i> Consultas Anteriores
            </button>
        </div>
    </div>

    <!-- Main Card -->
    <div class="bg-white shadow rounded-lg border border-gray-200 overflow-hidden">
        <!-- Tabs Header -->
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px" aria-label="Tabs">
                <button wire:click="$set('activeTab', 'consulta')" class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm {{ $activeTab === 'consulta' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <i class="fa-solid fa-stethoscope mr-2"></i> Consulta
                </button>
                <button wire:click="$set('activeTab', 'receta')" class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm {{ $activeTab === 'receta' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                    <i class="fa-solid fa-prescription-bottle-medical mr-2"></i> Receta
                </button>
            </nav>
        </div>

        <!-- Form Content -->
        <div class="p-6">
            <form wire:submit.prevent="saveConsultation">
                <!-- Tab: Consulta -->
                <div class="{{ $activeTab === 'consulta' ? 'block' : 'hidden' }} space-y-6">
                    <div>
                        <label for="diagnosis" class="block text-sm font-medium text-gray-700">Diagnóstico</label>
                        <textarea wire:model="diagnosis" id="diagnosis" rows="3" class="mt-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Describa el diagnóstico del paciente aquí..."></textarea>
                    </div>
                    <div>
                        <label for="treatment" class="block text-sm font-medium text-gray-700">Tratamiento</label>
                        <textarea wire:model="treatment" id="treatment" rows="3" class="mt-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Describa el tratamiento recomendado aquí..."></textarea>
                    </div>
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notas</label>
                        <textarea wire:model="notes" id="notes" rows="2" class="mt-1 bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Agregue notas adicionales sobre la consulta..."></textarea>
                    </div>
                </div>

                <!-- Tab: Receta (Mocked for UI compliance) -->
                <div class="{{ $activeTab === 'receta' ? 'block' : 'hidden' }}">
                    <div class="mb-4 flex items-center mb-2 gap-4">
                        <div class="w-1/2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Medicamento</label>
                            <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Amoxicilina 500mg" value="Amoxicilina 500mg">
                        </div>
                        <div class="w-1/4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Dosis</label>
                            <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="1 cada 8 horas" value="1 cada 8 horas">
                        </div>
                        <div class="w-1/4 relative">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Frecuencia / Duración</label>
                            <div class="flex gap-2 items-center">
                                <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Ej: cada 8 horas por 7 días">
                                <button type="button" class="bg-red-500 text-white rounded p-2 hover:bg-red-600">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="mt-2 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                        <i class="fa-solid fa-plus mr-2"></i> Añadir Medicamento
                    </button>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="inline-flex justify-center items-center px-6 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fa-solid fa-save mr-2"></i> Guardar Consulta
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Consultas Anteriores -->
    <div x-show="showConsultationsModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showConsultationsModal" @click="showConsultationsModal = false" class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showConsultationsModal" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Consultas Anteriores
                        </h3>
                        <button @click="showConsultationsModal = false" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Cerrar</span>
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </div>
                    <div class="mt-4 max-h-96 overflow-y-auto">
                        @forelse($pastAppointments as $past)
                            <div class="mb-4 border border-blue-200 rounded-lg p-4 bg-white shadow-sm">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <div class="flex items-center text-blue-600 font-bold mb-1">
                                            <i class="fa-solid fa-calendar mr-2"></i>
                                            {{ \Carbon\Carbon::parse($past->date)->format('d/m/Y') }} a las {{ \Carbon\Carbon::parse($past->start_time)->format('H:i') }}
                                        </div>
                                        <div class="text-sm text-gray-700 mb-3">Atendido por: Dr(a). {{ $past->doctor->user->name }}</div>
                                    </div>
                                    <button type="button" class="text-blue-600 bg-white border border-blue-300 hover:bg-blue-50 focus:ring-4 focus:ring-blue-100 font-medium rounded-lg text-xs px-4 py-2">
                                        Consultar Detalle
                                    </button>
                                </div>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-800 mb-1"><span class="font-bold">Diagnóstico:</span> {{ $past->reason ?? 'Consulta general' }}</p>
                                    <p class="text-sm text-gray-800 mb-1"><span class="font-bold">Tratamiento:</span> Tratamiento indicado por el médico en la consulta anterior.</p>
                                    <p class="text-sm text-gray-800"><span class="font-bold">Notas:</span> El paciente reporta síntomas relacionados al diagnóstico.</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">No hay consultas anteriores registradas para este paciente.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Historia Médica -->
    <div x-show="showHistoryModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showHistoryModal" @click="showHistoryModal = false" class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="showHistoryModal" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-600 text-sm" id="modal-title">
                            Historia médica del paciente
                        </h3>
                        <button @click="showHistoryModal = false" class="text-gray-400 hover:text-gray-500">
                            <span class="sr-only">Cerrar</span>
                            <i class="fa-solid fa-xmark text-xl"></i>
                        </button>
                    </div>
                    <div class="mt-4">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pb-4">
                            <div>
                                <span class="block text-sm text-gray-500 mb-1">Tipo de sangre:</span>
                                <span class="font-bold text-gray-800">{{ $appointment->patient->bloodType->name ?? 'No registrado' }}</span>
                            </div>
                            <div>
                                <span class="block text-sm text-gray-500 mb-1">Alergias:</span>
                                <span class="font-bold text-gray-800">{{ $appointment->patient->allergies ?? 'No registradas' }}</span>
                            </div>
                            <div>
                                <span class="block text-sm text-gray-500 mb-1">Enfermedades crónicas:</span>
                                <span class="font-bold text-gray-800">{{ $appointment->patient->chronic_conditions ?? 'No registradas' }}</span>
                            </div>
                            <div>
                                <span class="block text-sm text-gray-500 mb-1">Antecedentes quirúrgicos:</span>
                                <span class="font-bold text-gray-800">{{ $appointment->patient->surgical_history ?? 'No registradas' }}</span>
                            </div>
                        </div>
                        <div class="mt-4 text-center">
                            <a href="{{ route('admin.patients.edit', $appointment->patient_id) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                Ver / Editar Historia Médica
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
