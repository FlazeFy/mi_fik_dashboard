<style>
    #map-discover {
        height:70vh;
        border-radius: 10px;
        margin-top: 6px;
        margin-bottom: 6px;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
    }
</style>

<div class="position-relative">
    <h5 class="text-secondary fw-bold"><span class="text-primary fw-bold">{{count($location)}}</span> Event Location</h5>
    <div id="map-discover"></div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDXu2ivsJ8Hj6Qg1punir1LR2kY9Q_MSq8&callback=initMap&v=weekly" defer></script>

<script type="text/javascript">
    let map;

    function initMap() {
        //Map starter
        var markers = [
            <?php 
                foreach($location as $loc){
                    $full_coor = $loc->content_loc;
                    $full_coor = json_decode($full_coor);

                    foreach($full_coor as $fc){
                        if($fc->type == "coordinate"){
                            $coor = explode(", ", $fc->detail);
                            echo "{
                                coords:{lat:".$coor[0].",lng:".$coor[1]."},
                                content:'<div><h4>".$loc->content_title."</h4><p>".$loc->content_desc."</p></div>'
                                },";
                        }
                    }
                    
                }
            ?>
        ];

        map = new google.maps.Map(document.getElementById("map-discover"), {
            center: { lat: -6.969350413790824, lng: 107.62818479205987},
            zoom: 15,
        });

        <?php 
            $total = count($location);

            for($i = 0; $i < $total; $i++){
                echo "addMarker(markers[".$i."]);";
            }
        ?>

        function addMarker(props){
            var marker = new google.maps.Marker({
                position:props.coords,
                map:map,
            });

            if(props.iconImage){
                marker.setIcon(props.iconImage);
            }
            if(props.content){
                var infoWindow = new google.maps.InfoWindow({
                content:props.content
            });
            marker.addListener('click', function(){
                infoWindow.open(map, marker);
            });
            }
        }
    }

    window.initMap = initMap;
</script>