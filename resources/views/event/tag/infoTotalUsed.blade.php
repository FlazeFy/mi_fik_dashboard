<div class="modal fade" id="infoTotalUsed-{{$tg->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">   
            <div class="modal-body text-center pt-4">
                <button type="button" class="custom-close-modal" data-bs-dismiss="modal" aria-label="Close" title="Close pop up"><i class="fa-solid fa-xmark"></i></button>
                <h6>Total <span class="text-primary"><span id="total_used_{{$tg->slug_name}}"></span> {{$tg->tag_name}}</span> tag used in content and user</h6>
                <div id="MOT_pie_chart_{{$tg->id}}"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var <?php echo "total_user_".$tag_code; ?>  = 0;
    var <?php echo "total_content_".$tag_code; ?>  = 0;

    function getTagTotal<?php echo $tag_code; ?>() {
        $.ajax({
            url: '/api/v1/tag/{{$tg->slug_name}}',
            type: 'get',
            dataType: 'json',
            beforeSend: function (xhr) {
                xhr.setRequestHeader("Accept", "application/json");
                xhr.setRequestHeader("Authorization", "Bearer <?= session()->get("token_key"); ?>");
            },
            success: function(response){
                var response = response.data; 
                <?php echo "total_content_".$tag_code; ?> = response.total_content;
                <?php echo "total_user_".$tag_code; ?> = response.total_user;
                document.getElementById("total_used_{{$tg->slug_name}}").innerHTML = response.total;
                
                var options<?php echo $tag_code; ?> = {
                    series: [<?php echo "total_user_".$tag_code; ?>, <?php echo "total_content_".$tag_code; ?>],
                    chart: {
                    width: <?php if(!$isMobile){echo'360';} else {echo'300';} ?>,
                    type: 'pie',
                },
                labels: ["User","Content"],
                colors: ['#F78A00','#009FF9'],
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

                var chart<?php echo $tag_code; ?> = new ApexCharts(document.querySelector("#MOT_pie_chart_{{$tg->id}}"), options<?php echo $tag_code; ?>);
                chart<?php echo $tag_code; ?>.render();
            },
        })
        .fail(function (jqXHR, ajaxOptions, thrownError) {
            console.log(jqXHR.responseJSON.message);
            failResponse(jqXHR, ajaxOptions, thrownError, "#MOT_pie_chart_{{$tg->id}}", false, null, null);
        });;
    }
</script>