<div class="position-relative">
    <h5 class="text-secondary fw-bold">Most Used Tag</h5>
    <button class="btn btn-transparent px-2 py-0 position-absolute" style="right:10px; top:0;" type="button" id="section-more-MOT" data-bs-toggle="dropdown" aria-haspopup="true"
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
        <a class="dropdown-item" data-bs-target="#mtChart" data-bs-toggle="modal"><i class="fa-solid fa-circle-info"></i> {{ __('messages.help') }}</a>
    </div>
    @if(count($mostTag) != 0)
        <div id="MOT_pie_chart"></div>
    @else
        <img src="{{asset('assets/nodata.png')}}" class="img nodata-icon">
        <h6 class="text-center">No Data Available</h6>
    @endif

    @include('popup.mini_help', ['id' => 'mtChart', 'title'=> 'Most Tag / Role Chart', 'location'=>'most_tag_chart'])
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

                foreach($mostTag as $mt){
                    $tag = json_decode($mt->content_tag);
                    
                    foreach($tag as $tg){
                        array_push($val, $tg->tag_name);
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

            foreach($mostTag as $mt){
                $tag = json_decode($mt->content_tag);
                
                foreach($tag as $tg){
                    array_push($val, $tg->tag_name);
                }   
            }

            foreach($setting as $set){
                if(count($val) > $set->MOT_range){
                    $max = $set->MOT_range;
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

    var chart = new ApexCharts(document.querySelector("#MOT_pie_chart"), options);
    chart.render();
</script>