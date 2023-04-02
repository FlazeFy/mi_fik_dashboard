<style>
    <?php
        $color = ['#F9DB00','#009FF9','#FB8C00','#42C9E7', 
            '#F9DB00','#009FF9','#FB8C00','#42C9E7','#F9DB00','#009FF9','#FB8C00','#42C9E7'];

        $i = 1;

        foreach($mostViewed as $mv) {
            echo "#MVE_column_chart .apexcharts-series :nth-child(".$i.") {
                fill:".$color[$i].";
            }";
            $i++;
        }
    ?>
</style>

<div class="position-relative">
<h5 class="text-secondary fw-bold">Most Viewed Event</h5>
    <button class="btn btn-transparent px-2 py-0 position-absolute" style="right:10px; top:0px;" type="button" id="section-more-MVE" data-bs-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        <i class="fa-solid fa-ellipsis-vertical more"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="section-more-MVE">
        <span class="dropdown-item">
            <!--Chart Setting-->
            @foreach($setting as $set)
                <form action="/statistic/update_mve/{{$set->id}}" method="POST">
                    @csrf
                    <label for="floatingInputValue" style="font-size:12px;">Chart Range</label>
                    <input type="number" class="form-control py-1" name="MVE_range" min="3" max="10" value="{{$set->MVE_range}}" onblur="this.form.submit()" required>
                </form>
            @endforeach
        </span>
        <a class="dropdown-item" href=""><i class="fa-solid fa-circle-info"></i> Help</a>
        <a class="dropdown-item" href=""><i class="fa-solid fa-print"></i> Print</a>
    </div>
    @if(count($mostViewed) != 0)
        <div id="MVE_column_chart"></div>
    @else
        <img src="http://127.0.0.1:8000/assets/nodata.png" class="img nodata-icon">
        <h6 class="text-center">No Data Available</h6>
    @endif
</div>

<script type="text/javascript">
    var options = {
        series: [
        {
            name: 'Total',
            data: [
                <?php 
                    foreach($mostViewed as $mv){
                        echo $mv->total.",";
                    }    
                ?>
            ],
        }, 
        //....
    ],
        chart: {
        height: 260,
        type: 'bar'
    },
    dataLabels: {
        enabled: false,
    },
    stroke: {
        curve: 'smooth'
    },
    xaxis: {
        type: 'category',
        categories: [
            <?php 
                foreach($mostViewed as $mv){
                    echo "'".$mv->content_title."',";
                }    
            ?>
        ],
        labels: {
            formatter: function (val) {
                return val.toFixed(0);
            }
        },
    },
   
    plotOptions: {
        bar: {
            borderRadius: 6,
            horizontal: true,
        },
    },
    tooltip: {
        y: {
            formatter: function (val) {
                val = val.toFixed(0)
                if(val == 0 || val == 1){
                    return val + " view";
                } else {
                    return val + " views";
                }
            }
        },
    }
    };

    var chart = new ApexCharts(document.querySelector("#MVE_column_chart"), options);
    chart.render();
</script>