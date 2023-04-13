<div class="position-relative">
    @if(count($suggestion) > 0)
        <div id="MS_tree_chart"></div>
    @else
        <img src="{{ asset('/assets/nodata.png')}}" class="img nodata-icon">
        <h6 class="text-center">No Data Available</h6>
    @endif
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
        colors: ['#F9DB00','#009FF9','#FB8C00','#42C9E7','#CDD7B6','#C1F666','#D43F97','#1E5D8C','#421243','#7F94B0','#EF6537','#C0ADDB'],
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