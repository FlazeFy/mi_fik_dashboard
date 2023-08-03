<div class="position-relative">
    <h5 class="text-secondary fw-bold">Most Used Location</h5>
    <button class="btn btn-transparent px-2 py-0 position-absolute" style="right:10px; top:0;" type="button" id="section-more-MOL" data-bs-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        <i class="fa-solid fa-ellipsis-vertical more"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="section-more-MOL">
        <span class="dropdown-item">
            @foreach($setting as $set)
                <form action="/statistic/update_mol/{{$set->id}}" method="POST">
                    @csrf
                    <label for="floatingInputValue" style="font-size:12px;">Chart Range</label>
                    <input type="number" class="form-control py-1" name="MOL_range" min="3" max="10" value="{{$set->MOL_range}}" onblur="this.form.submit()" required>
                </form>
            @endforeach
        </span>
        <a class="dropdown-item" data-bs-target="#mlChart" data-bs-toggle="modal"><i class="fa-solid fa-circle-info"></i> Help</a>
    </div>
    @if(count($mostLoc) != 0)
        <div id="MOL_pie_chart"></div>
    @else
        <img src="{{asset('assets/nodata.png')}}" class="img nodata-icon">
        <h6 class="text-center">No Data Available</h6>
    @endif

    @include('popup.mini_help', ['id' => 'mlChart', 'title'=> 'Most Location Chart', 'location'=>'most_loc_chart'])
</div>

<script type="text/javascript">
    var options = {
        series: [
            <?php 
                $val = [];

                foreach($mostLoc as $mt){
                    $loc = json_decode($mt->content_loc);
                    
                    foreach($loc as $lc){
                        if($lc->type == "name" && $lc->detail != null){
                            array_push($val, $lc->detail);
                        }
                    }   
                }

                foreach($setting as $set){
                    $max = $set->MOL_range;
                }
                $otherTotal = 0;

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

            foreach($mostLoc as $mt){
                $loc = json_decode($mt->content_loc);
                
                foreach($loc as $lc){
                    if($lc->type == "name" && $lc->detail != null){
                        array_push($val, $lc->detail);
                    }
                }   
            }

            foreach($setting as $set){
                if(count($val) > $set->MOL_range){
                    $max = $set->MOL_range; 
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
    colors: ['#F9DB00','#009FF9','var(--primaryColor)','#42C9E7'],
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

    var chart = new ApexCharts(document.querySelector("#MOL_pie_chart"), options);
    chart.render();
</script>