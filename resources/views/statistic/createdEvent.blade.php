<div class="position-relative">
    <h5 class="text-secondary fw-bold">{{ __('messages.ce') }}</h5>
    <button class="btn btn-transparent px-2 py-0 position-absolute" style="right:10px; top:0;" type="button" id="section-more-MOT" data-bs-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        <i class="fa-solid fa-ellipsis-vertical more"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="section-more-MOT">
        <label class="ms-3" style="font-size:12px;">{{ __('messages.chart_view') }}</label>
        @foreach($setting as $set)
            <form action="/statistic/update_ce/{{$set->id}}" method="POST">
                @csrf
                <input hidden name="CE_range" value="6">
                <button class="dropdown-item btn-transparent" type="submit">
                    @if($set->CE_range == 6)
                        <i class="fa-solid fa-check text-success"></i>
                    @endif
                    Semester</button>
            </form>
            <form action="/statistic/update_ce/{{$set->id}}" method="POST">
                @csrf
                <input hidden name="CE_range" value="12">
                <button class="dropdown-item btn-transparent" type="submit">
                    @if($set->CE_range == 12)
                        <i class="fa-solid fa-check text-success"></i>
                    @endif
                    {{ __('messages.year') }}</button>
            </form>
        @endforeach
        <hr>
        <a class="dropdown-item" data-bs-target="#ceChart" data-bs-toggle="modal"><i class="fa-solid fa-circle-info"></i> {{ __('messages.help') }}</a>
    </div>
    @if(count($createdEvent) != 0)
        @if(!$isMobile)
            <div id="CE_area_chart"></div>
        @else 
            <div class="chart-mobile-holder">
                <div id="CE_area_chart"></div>
            </div>
        @endif
    @else
        <img src="{{asset('assets/nodata.png')}}" class="img nodata-icon">
        <h6 class="text-center">{{ __('messages.no_data') }}</h6>
    @endif

    @include('popup.mini_help', ['id' => 'ceChart', 'title'=> 'Created Event Chart', 'location'=>'created_event_chart'])
</div>

<script type="text/javascript">
    var options = {
        series: [
        {
            name: 'All',
            data: [
                <?php
                    foreach($setting as $set){
                        $max = $set->CE_range; 
                    }

                    $arr = App\Helpers\Generator::getMonthList($max, "number");
                    
                    foreach(array_reverse($arr) as $ar => $val){
                        $i=0;
                        foreach($createdEvent as $ce){
                            if($ce->month == $val){
                                echo $ce->total.",";
                                $i++;
                            } 
                        }
                        if($i != 1){
                            echo "0".",";
                        }
                    }
                ?>
            ]
        }, 
    ],
        chart: {
        height: 260,
        <?php if($isMobile){echo 'width: 480,';} ?>
        type: 'area'
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        curve: 'smooth'
    },
    xaxis: {
        type: 'category',
        categories: [
            <?php
                foreach($setting as $set){
                    $max = $set->CE_range;
                }

                $arr = App\Helpers\Generator::getMonthList($max, "name");                
                
                foreach(array_reverse($arr) as $ar => $val){
                    echo $val;
                }
            ?>
        ]
    },
    colors: ['#42C9E7'],
    tooltip: {
        y: {
            formatter: function (val) {
                val = val.toFixed(0)
                if(val == 0 || val == 1){
                    return val + " event";
                } else {
                    return val + " events";
                }
            }
        },
    }
    };

    var chart = new ApexCharts(document.querySelector("#CE_area_chart"), options);
    chart.render();
</script>