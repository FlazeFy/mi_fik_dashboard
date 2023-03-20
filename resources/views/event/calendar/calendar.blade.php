<style>
    #calendar {
        width: 100%;
        margin: 40px auto;
    }
</style>

<div id="calendar"></div>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            center: 'prev,next today',
            left: 'title',
            //right: 'dayGridMonth'
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        selectable: true,
        navLinks: true, 
        eventLimit: true,
        dayMaxEvents: 4,
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
                            {
                                groupId: '".$i."',
                                title: '".$ct->content_title."',
                                start: '".$ct->content_date_start."',
                                end: '".$ct->content_date_end."',
                                extendedProps: {
                                    slug_name: '".$ct->slug_name."'
                                }
                            },
                            {
                                groupId: '".$i."',
                                title: '".$ct->content_title."',
                                start: '".$ct->content_date_start."',
                                end: '".$ct->content_date_end."',
                                extendedProps: {
                                    slug_name: '".$ct->slug_name."'
                                }
                            },
                            {
                                groupId: '".$i."',
                                title: '".$ct->content_title."',
                                start: '".$ct->content_date_start."',
                                end: '".$ct->content_date_end."',
                                extendedProps: {
                                    slug_name: '".$ct->slug_name."'
                                }
                            },
                            {
                                groupId: '".$i."',
                                title: '".$ct->content_title."',
                                start: '".$ct->content_date_start."',
                                end: '".$ct->content_date_end."',
                                extendedProps: {
                                    slug_name: '".$ct->slug_name."'
                                }
                            },
                            {
                                groupId: '".$i."',
                                title: '".$ct->content_title."',
                                start: '".$ct->content_date_start."',
                                end: '".$ct->content_date_end."',
                                extendedProps: {
                                    slug_name: '".$ct->slug_name."'
                                }
                            },
                            {
                                groupId: '".$i."',
                                title: '".$ct->content_title."',
                                start: '".$ct->content_date_start."',
                                end: '".$ct->content_date_end."',
                                extendedProps: {
                                    slug_name: '".$ct->slug_name."'
                                }
                            },
                            {
                                groupId: '".$i."',
                                title: '".$ct->content_title."',
                                start: '".$ct->content_date_start."',
                                end: '".$ct->content_date_end."',
                                extendedProps: {
                                    slug_name: '".$ct->slug_name."'
                                }
                            },
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