<div class="position-relative">
    <button class="btn btn-transparent px-2 py-0 position-absolute" style="right:10px; top:-30px;" type="button" id="section-more-MOT" data-bs-toggle="dropdown" aria-haspopup="true"
        aria-expanded="false">
        <i class="fa-solid fa-ellipsis-vertical more"></i>
    </button>
    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="section-more-MOT">
        <a class="dropdown-item" data-bs-target="#msChart" data-bs-toggle="modal"><i class="fa-solid fa-circle-info"></i> Help</a>
        <a class="dropdown-item" href=""><i class="fa-solid fa-print"></i> Print</a>
    </div>

    @if(count($suggestion) > 0)
        <div id="MS_tree_chart"></div>
    @else
        <img src="{{ asset('/assets/nodata.png')}}" class="img nodata-icon">
        <h6 class="text-center">No Data Available</h6>
    @endif

    @include('popup.mini_help', ['id' => 'msChart', 'title'=> 'Most Suggestion Chart', 'location'=>'most_suggest_chart'])
</div>

<script type="text/javascript">
    var options = {
        series: [
            {
                data: [
                    <?php 
                        foreach($suggestion as $sg){
                            echo "{
                                x: '".$sg->category."',
                                y: ".$sg->total."
                            },";
                        }    
                    ?>
                ]
            }
        ],
          legend: {
          show: false
        },
        chart: {
          height: 350,
          type: 'treemap'
        },
        colors: ['#F9DB00','#009FF9','var(--primaryColor)','#42C9E7','#CDD7B6','#C1F666','#D43F97','#1E5D8C','#421243','#7F94B0','#EF6537','#C0ADDB'],
        plotOptions: {
            treemap: {
                distributed: true,
                enableShades: false
            }
        }
    };
    
    var chart = new ApexCharts(document.querySelector("#MS_tree_chart"), options);
    chart.render();
</script>