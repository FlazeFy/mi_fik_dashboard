<div class="position-relative">
    <h5 class="text-secondary fw-bold">Created Event</h5>
    <button class="btn btn-transparent px-2 py-0 position-absolute" style="right:10px; top:0px;" type="button" id="section-more-MOT" data-bs-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        <i class="fa-solid fa-ellipsis-vertical more"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="section-more-MOT">
        <button class="dropdown-item btn-transparent" href="">Semester</button>
        <button class="dropdown-item btn-transparent" href="">Year</button>
        <hr>
        <a class="dropdown-item" href=""><i class="fa-solid fa-circle-info"></i> Help</a>
        <a class="dropdown-item" href=""><i class="fa-solid fa-print"></i> Print</a>
    </div>
    <div id="CE_area_chart"></div>
</div>

<script type="text/javascript">
    var options = {
        series: [
        {
            name: 'All',
            data: [
                <?php
                    $max = 6; //Max month to show
                    $date = new DateTime(date("Y/m/d")); 

                    //Array to store month. First month is the current month.
                    $arr = [$date->format('m')];
                    for ($i = 1; $i < $max; $i++) {
                        $date->modify("-1 month");
                        array_push($arr, $date->format('m'));
                    }
                    
                    //Print array from backward.
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
        //....
    ],
        chart: {
        height: 260,
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
                $max = 6; //Max month to show
                $date = new DateTime(date("Y/m/d")); 

                //Array to store month. First month is the current month.
                $arr = ["'".substr($date->format('F'), 0, 3)."', ", ];
                for ($i = 1; $i < $max; $i++) {
                    $date->modify("-1 month");
                    array_push($arr, "'".substr($date->format('F'), 0, 3)."',");
                }
                
                //Print array from backward.
                foreach(array_reverse($arr) as $ar => $val){
                    echo $val;
                }
            ?>
        ]
    },
    tooltip: {
        // x: {
        // format: 'MMM'
        // },
    },
    };

    var chart = new ApexCharts(document.querySelector("#CE_area_chart"), options);
    chart.render();
</script>