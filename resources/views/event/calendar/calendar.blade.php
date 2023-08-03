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
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                //right: 'dayGridMonth'
                right: 'dayGridMonth,timeGridDay'
            },
            selectable: true,
            navLinks: true, 
            eventLimit: true,
            dayMaxEvents: 4,
            events: [
                <?php
                    $i = 0;
                    
                    foreach($content as $ct){
                        if($ct->content_date_start){
                            echo "
                                {
                                    groupId: '".$i."',
                                    title: '".$ct->content_title."',
                                    start: getDateToContext('".$ct->content_date_start."','calendar'),
                                    end: getDateToContext('".$ct->content_date_end."','calendar'),
                                    extendedProps: {
                                        slug_name: '".$ct->slug_name."'
                                    }
                                },
                            ";
                            $i++;
                        }
                    }
                    
                ?>
            ],
            eventClick:  function(info, jsEvent, view) {
                window.location.href = "http://127.0.0.1:8000/event/detail/" +info.event.extendedProps.slug_name;
            },
        });
        calendar.render();
    });
</script>