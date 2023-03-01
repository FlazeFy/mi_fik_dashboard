<style>
    #calendar {
        width: 100%;
        margin: 40px auto;
    }
    #calendar a {
        text-decoration: none !important;
        color:#414141;
    }
    .fc-daygrid-event{
        background: white !important;
        padding: 0px 10px;
        white-space: normal !important;
        margin-inline: 6px !important;
        font-weight: 500;
        border-radius: 6px;
        border-left: #F78A00 4px solid;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
        margin-bottom: 8px;
    }
    .fc-h-event{
        border:none;
        border-radius:6px;
    }
    .fc-daygrid-event-dot{
        border:calc(var(--fc-daygrid-event-dot-width,8px)/ 2) solid var(--fc-event-border-color, #198553);
    }
    .fc .fc-daygrid-day.fc-day-today{
        background: rgba(247, 138, 0, 0.4);
    }
    .fc-event-time{
        display:none;
    }
    .fc-event-title{
        color: #414141 !important;
        white-space: normal !important;
        font-weight: 500;
    }
    /* .fc-daygrid-day-events{
        display: flex;
        flex-direction: column;
        height: 80px;
        overflow-y: scroll;
    } */
    .fc-daygrid-event:hover{
        background: #F78A00 !important;
    }
</style>

<div id="calendar"></div>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth'
            //right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        selectable: true,
        navLinks: true, 
        //eventLimit: true,
        //eventLimit: 4,
        events: [
            <?php
                //Initial value
                $i = 0;
                
                foreach($content as $ct){
                    if($ct->content_date_start){
                        echo "
                            {
                                groupId: '".$i."',
                                title: '".$ct->content_title."',
                                start: '".$ct->content_date_start."',
                                end: '".$ct->content_date_end."',
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
        //Show content detail
        eventClick:  function(info, jsEvent, view) {
            window.location.href = "http://127.0.0.1:8000/event/detail/" +info.event.extendedProps.slug_name;
        },
    });
    calendar.render();
    });
</script>