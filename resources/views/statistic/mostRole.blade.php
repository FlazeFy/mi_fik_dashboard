<div class="position-relative">
    <h5 class="text-secondary fw-bold">{{ __('messages.mar') }}</h5>
    @if(count($mostRole) != 0)
        <div id="MOR_pie_chart"></div>
    @else
        <img src="{{asset('assets/nodata.png')}}" class="img nodata-icon">
        <h6 class="text-center">{{ __('messages.no_data') }}</h6>
    @endif
</div>

<script type="text/javascript">
    var options = {
        series: [
            <?php 
                $val = [];
                foreach($setting as $set){
                    $max = $set->MOT_range;
                }
                $otherTotal = 0;
                
                foreach($mostRole as $mr){
                    $role = count($mr->role);
                    
                    for($i = 0; $i < $role; $i++){
                        array_push($val, $mr->role[$i]['tag_name']);
                    }   
                }

                $result = array_count_values($val);
                rsort($result);

                $main = array_slice($result, 0, $max);
                $others = array_slice($result, $max, count($result));

                foreach($main as $m){
                    echo $m.",";
                }

                if(count($result) > $max){
                    foreach($others as $o){
                        $otherTotal += $o;
                    }
                    echo $otherTotal.",";
                }
            ?>
        ],
        chart: {
        width: <?php if(!$isMobile){echo'360';} else {echo'300';} ?>,
        type: 'pie',
    },
    labels: [
        <?php 
            $val = [];

            foreach($mostRole as $mr){
                $role = count($mr->role);
                
                for($i = 0; $i < $role; $i++){
                    array_push($val, $mr->role[$i]['tag_name']);
                }   
            }

            foreach($setting as $set){
                if(count($val) > $set->MOT_range){
                    $max = $set->MOT_range; //Max loc to show.
                } else {
                    $max = null;
                }
            }

            $result = array_count_values($val);
            arsort($result);

            $new_arr = array_keys($result);
            if($max != null){
                for($i = 0; $i < $max; $i++){
                    echo "'".$new_arr[$i]."',";
                }
            } else {
                foreach($new_arr as $na){
                    echo "'".$na."',";
                }
            }
            echo "'Others'";
        ?>
    ],
    colors: ['#F9DB00','#009FF9','#F78A00','#42C9E7'],
    legend: {
        position: 'bottom'
    },
    responsive: [{
        // breakpoint: 480,
        options: {
            chart: {
                width: 160
            },
            legend: {
                position: 'bottom'
            }
        }
    }]
    };

    var chart = new ApexCharts(document.querySelector("#MOR_pie_chart"), options);
    chart.render();
</script>