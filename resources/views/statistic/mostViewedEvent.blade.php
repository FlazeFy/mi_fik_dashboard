<style>
    <?php
        if(session()->get('selected_view_mve_chart') == "All"){
            $color = ['#F9DB00','#009FF9','var(--primaryColor)','#42C9E7', 
                '#F9DB00','#009FF9','var(--primaryColor)','#42C9E7','#F9DB00','#009FF9','var(--primaryColor)','#42C9E7'];

            $i = 1;

            foreach($mostViewed as $mv) {
                echo "#MVE_column_chart .apexcharts-series :nth-child(".$i.") {
                    fill:".$color[$i].";
                }";
                $i++;
            }
        }
    ?>
</style>

<div class="position-relative">
<h5 class="text-secondary fw-bold">Most Viewed Event</h5>
    <button class="btn btn-transparent px-2 py-0 position-absolute" style="right:10px; top:0;" type="button" id="section-more-MVE" data-bs-toggle="dropdown" aria-haspopup="true"
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
        </span><hr>
        @php($set = session()->get('selected_view_mve_chart'))
        <label class="ms-3" style="font-size:12px;">Chart View</label>
        <form action="/statistic/update_mve_view" method="POST">
            @csrf
            <input hidden name="MVE_view" value="All">
            <button class="dropdown-item btn-transparent" type="submit">
                @if($set == "All")
                    <i class="fa-solid fa-check text-success"></i>
                @endif
                All</button>
        </form>
        <form action="/statistic/update_mve_view" method="POST">
            @csrf
            <input hidden name="MVE_view" value="Separated">
            <button class="dropdown-item btn-transparent" type="submit">
                @if($set == "Separated")
                    <i class="fa-solid fa-check text-success"></i>
                @endif
                Separated</button>
        </form><hr>
        <a class="dropdown-item" data-bs-target="#mvChart" data-bs-toggle="modal"><i class="fa-solid fa-circle-info"></i> Help</a>
    </div>
    @if(count($mostViewed) != 0)
        @if(!$isMobile)
            <div id="MVE_column_chart"></div>
        @else 
            <div class="chart-mobile-holder">
                <div id="MVE_column_chart"></div>
            </div>
        @endif
    @else
        <img src="{{asset('assets/nodata.png')}}" class="img nodata-icon">
        <h6 class="text-center">No Data Available</h6>
    @endif

    @include('popup.mini_help', ['id' => 'mvChart', 'title'=> 'Most Viewed Chart', 'location'=>'most_viewed_chart'])
</div>

<script type="text/javascript">
    var options = {
        series: [
        <?php 
            if(session()->get('selected_view_mve_chart') == "All"){
                echo "
                    {
                        name: 'Total',
                        data: [
                            "; 
                                foreach($mostViewed as $mv){
                                    echo $mv->total.",";
                                }    
                            echo "
                        ],
                    }, 
                ";
            } else {
                echo "
                    {
                        name: 'Total Lecturer & Staff',
                        data: [
                            "; 
                                foreach($mostViewed as $mv){
                                    echo $mv->total_lecturer.",";
                                }    
                            echo "
                        ],
                    }, 
                    {
                        name: 'Total Student',
                        data: [
                            "; 
                                foreach($mostViewed as $mv){
                                    echo $mv->total_student.",";
                                }    
                            echo"
                        ],
                    }, 
                ";
            }
        ?>
        //....
    ],
    chart: {
        height: <?php 
            if(session()->get('selected_view_mve_chart') == "All"){
                echo '360';
            } else {
                echo '480';
            } ?>,
        <?php if($isMobile){echo 'width: 480,';} ?>
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
        marker: false,
        followCursor: true
    },
    <?php
        if(!session()->get('selected_view_mve_chart') != "All"){
            echo "stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            colors: ['#F9DB00','#009FF9'],
            ";
        }
    ?>
    };

    var chart = new ApexCharts(document.querySelector("#MVE_column_chart"), options);
    chart.render();
</script>