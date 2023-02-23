<div class="position-relative">
    <h5 class="text-secondary fw-bold">Most Used Location</h5>
    <button class="btn btn-transparent px-2 py-0 position-absolute" style="right:10px; top:0px;" type="button" id="section-more-MOL" data-bs-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        <i class="fa-solid fa-ellipsis-vertical more"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="section-more-MOL">
        <span class="dropdown-item">
            <!--Chart Setting-->
            @foreach($setting as $set)
                <form action="/statistic/update_mol/{{$set->id}}" method="POST">
                    @csrf
                    <label for="floatingInputValue" style="font-size:12px;">Chart Range</label>
                    <input type="number" class="form-control py-1" name="MOL_range" min="3" max="10" value="{{$set->MOL_range}}" onblur="this.form.submit()" required>
                </form>
            @endforeach
        </span>
        <a class="dropdown-item" href=""><i class="fa-solid fa-circle-info"></i> Help</a>
        <a class="dropdown-item" href=""><i class="fa-solid fa-print"></i> Print</a>
    </div>
    @if(count($mostLoc) != 0)
        <div id="MOL_pie_chart"></div>
    @else
        <img src="http://127.0.0.1:8000/assets/nodata.png" class="img nodata-icon">
        <h6 class="text-center">No Data Available</h6>
    @endif
</div>

<script type="text/javascript">
    var options = {
        series: [
            <?php 
                //Initial variable
                $val = [];

                foreach($mostLoc as $mt){
                    $loc = json_decode($mt->content_loc);
                    
                    foreach($loc as $lc){
                        //Insert loc name to new array
                        if($lc->type == "location"){
                            array_push($val, $lc->detail);
                        }
                    }   
                }

                foreach($setting as $set){
                    $max = $set->MOL_range; //Max loc to show.
                }
                $otherTotal = 0;

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

            foreach($mostLoc as $mt){
                $loc = json_decode($mt->content_loc);
                
                foreach($loc as $lc){
                    //Insert loc name to new array
                    if($lc->type == "location"){
                        array_push($val, $lc->detail);
                    }
                }   
            }

            //Check if chart range is greater than location total
            foreach($setting as $set){
                if(count($val) > $set->MOL_range){
                    $max = $set->MOL_range; //Max loc to show.
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
            // chart: {
            //     width: 160
            // },
            legend: {
                position: 'bottom'
            }
        }
    }]
    };

    var chart = new ApexCharts(document.querySelector("#MOL_pie_chart"), options);
    chart.render();
</script>