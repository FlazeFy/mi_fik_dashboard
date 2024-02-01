<style>
    #calendar {
        width: 100%;
    }
</style>

<div class="calendar-holder">
    <div id="calendar"></div>
</div>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var offset = getUTCHourOffset();
        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: sessionStorage.getItem('locale'),
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                //right: 'dayGridMonth'
                right: 'dayGridMonth,timeGridDay',
            },
            selectable: true,
            navLinks: true, 
            eventLimit: true,
            dayMaxEvents: 4,
            events: [],
            eventClick:  function(info, jsEvent, view) {
                window.location.href = "http://127.0.0.1:8000/attendance/detail/" +info.event.extendedProps.id;
            },
        });
        calendar.render();
    });
</script>