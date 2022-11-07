<div class="position-relative">
    <h5 class="text-secondary fw-bold">Most Used Tag</h5>
    <div id="chart"></div>
</div>

<script type="text/javascript">
    var options = {
        series: [
            <?php 
                //Initial variable
                $val = [];
                $max = 5; //Tags to show
                $otherTotal = 0;

                foreach($mostTag as $mt){
                    $tag = json_decode($mt->content_tag);
                    
                    foreach($tag as $tg){
                        //Insert tag name to new array
                        array_push($val, $tg->tag_name);
                    }   
                }

                //Count duplicate value w/ DESC sorting
                $result = array_count_values($val);
                rsort($result);

                //Separate top used and the others
                $main = array_slice($result, 0, $max);
                $others = array_slice($result, $max, count($result));

                //The top used & the others frequency
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
        width: 360,
        type: 'pie',
    },
    labels: [
        <?php 
            //Initial variable
            $val = [];
            $max = 5; //Tags to show

            foreach($mostTag as $mt){
                $tag = json_decode($mt->content_tag);
                
                foreach($tag as $tg){
                    //Insert tag name to new array
                    array_push($val, $tg->tag_name);
                }   
            }

            //Make unique array
            sort($val);
            $result = array_unique($val);
            $main = array_slice($result, 0, $max);

            foreach($main as $m){
                echo "'".$m."',";
            }

            if(count($result) > $max){
                echo "'Others'";
            }
        ?>
    ],
    responsive: [{
        breakpoint: 480,
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

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
</script>