<div class="position-relative">
    <h5 class="text-secondary fw-bold">{{ __('messages.tac') }}</h5><hr>
    @if(count($atrs) != 0)
        <div id="TA_pie_chart"></div>
    @else
        <img src="{{asset('assets/nodata.png')}}" class="img nodata-icon">
        <h6 class="text-center">{{ __('messages.no_data') }}</h6>
    @endif

    @include('popup.mini_help', ['id' => 'taChart', 'title'=> 'Total Attendance Chart', 'location'=>'most_ta_chart'])
</div>

<script type="text/javascript">
    var options = {
        series: [
            <?php
                $totalPresence = 0;
                $totalAbsence = 0;
                $totalNotResponse = 0;

                foreach($atrs as $atr){
                    if($atr->attendance_answer == "presence"){
                        $totalPresence++;
                    } else if($atr->attendance_answer == "absence"){
                        $totalAbsence++;
                    } else {
                        $totalNotResponse++;
                    } 
                }
                echo $totalPresence.","; 
                echo $totalAbsence.","; 
                echo $totalNotResponse; 
            ?>
        ],
        chart: {
        width: <?php if(!$isMobile){echo'360';} else {echo'300';} ?>,
        type: 'pie',
    },
    labels: ['Presence','Absence','Not Response'],
    colors: ['var(--successBG)','var(--warningBG)','var(--shadowColor)'],
    legend: {
        position: 'bottom'
    },
    responsive: [{
        // breakpoint: 480,
        options: {
            legend: {
                position: 'bottom'
            }
        }
    }]
    };

    var chart = new ApexCharts(document.querySelector("#TA_pie_chart"), options);
    chart.render();
</script>