<div>
    <div class="mb-6 flex justify-between items-center">
        <div>
            <div class="text-sm text-gray-500 mb-2">Dashboard / Calendario</div>
            <h1 class="text-2xl font-bold text-gray-900">Calendario</h1>
        </div>
    </div>

    <!-- Contenedor del Calendario -->
    <div class="bg-white p-6 shadow sm:rounded-lg border border-gray-200">
        <div id="calendar" wire:ignore></div>
    </div>
</div>

@push('js')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
<script>
    document.addEventListener('livewire:initialized', function () {
        var calendarEl = document.getElementById('calendar');
        var events = {!! $events !!};

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            buttonText: {
                today: 'Hoy',
                month: 'Mes',
                week: 'Semana',
                day: 'Día',
                list: 'Lista'
            },
            eventDisplay: 'list-item',
            events: events,
            eventClick: function(info) {
                // Prevenimos el salto al url para poder controlarlo con wire:navigate (si usamos livewire) o directamente href
                if (info.event.url) {
                    info.jsEvent.preventDefault();
                    window.location.href = info.event.url;
                }
            },
            eventDidMount: function(info) {
                // Agregamos tooltips y el doctor al evento (Opcional, nativo JS)
                var tooltipTitle = "Paciente: " + info.event.title + "\n" + info.event.extendedProps.doctor;
                info.el.setAttribute("title", tooltipTitle);
            }
        });

        calendar.render();
    });
</script>
@endpush
