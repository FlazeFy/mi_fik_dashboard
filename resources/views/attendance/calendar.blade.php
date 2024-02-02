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
            events: [
                <?php
                    $i = 0;
                    
                    foreach($attd as $at){
                        echo "
                            {
                                groupId: '".$i."',
                                title: '";
                                if($at->content_title){
                                    echo $at->content_title." | ";
                                }
                                echo $at->attendance_title."',
                                start: getDateToContext('".$at->attendance_time_start."','calendar'),";

                                if($at->attendance_time_end){
                                    echo "end: getDateToContext('".$at->attendance_time_end."','calendar'),";
                                }
                                echo "extendedProps: {
                                    slug: '"; 
                                        if(session()->get("role_key") != 1){
                                            echo $at->id;
                                        } else {
                                            echo $at->id_attendance;
                                        }
                                    echo"'
                                }
                            },
                        ";
                        $i++;
                    }
                    
                ?>
            ],
            eventClick:  function(info, jsEvent, view) {
                window.location.href = "http://127.0.0.1:8000/attendance/detail/" +info.event.extendedProps.slug;
            },
        });
        calendar.render();
    });
</script>