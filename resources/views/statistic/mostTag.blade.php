<div class="position-relative">
    <h5 class="text-secondary fw-bold">Most Used Tag</h5>
    <button class="btn btn-transparent px-2 py-0 position-absolute" style="right:10px; top:0px;" type="button" id="section-more-MOT" data-bs-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        <i class="fa-solid fa-ellipsis-vertical more"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="section-more-MOT">
        <span class="dropdown-item">
            <!--Chart Setting-->
            @foreach($setting as $set)
                <form action="/statistic/update_mot/{{$set->id}}" method="POST">
                    @csrf
                    <label for="floatingInputValue" style="font-size:12px;">Chart Range</label>
                    <input type="number" class="form-control py-1" name="MOT_range" min="3" max="10" value="{{$set->MOT_range}}" onblur="this.form.submit()" required>
                </form>
            @endforeach
        </span>
        <a class="dropdown-item" href=""><i class="fa-solid fa-circle-info"></i> Help</a>
        <a class="dropdown-item" href=""><i class="fa-solid fa-print"></i> Print</a>
    </div>
    <div id="MOT_pie_chart"></div>
</div>

<script type="text/javascript">
    var options = {
        series: [
            <?php 
                //Initial variable
                $val = [];
                foreach($setting as $set){
                    $max = $set->MOT_range; //Max tag to show.
                }
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

            foreach($mostTag as $mt){
                $tag = json_decode($mt->content_tag);
                
                foreach($tag as $tg){
                    //Insert tag name to new array
                    array_push($val, $tg->tag_name);
                }   
            }

            //Check if chart range is greater than location total
            foreach($setting as $set){
                if(count($val) > $set->MOT_range){
                    $max = $set->MOT_range; //Max loc to show.
                } else {
                    $max = null;
                }
            }

            //Count duplicate value w/ DESC keys sorting
            $result = array_count_values($val);
            arsort($result);

            //Get array keys
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

    var chart = new ApexCharts(document.querySelector("#MOT_pie_chart"), options);
    chart.render();
</script>