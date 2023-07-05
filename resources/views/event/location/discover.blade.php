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
    <h5 class="text-secondary fw-bold"><span class="text-primary fw-bold">
        @if($location)
            {{count($location)}}
        @endif
    </span> Event Location</h5>
    @if(count($location) != 0)
        <div id="map-discover"></div>
    @else 
        <img src="{{asset('assets/noloc.png')}}" class="img nodata-icon">
        <h6 class="text-center text-secondary">You have no event to see with location</h6>
    @endif
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDXu2ivsJ8Hj6Qg1punir1LR2kY9Q_MSq8&callback=initMap&v=weekly" defer></script>

<script type="text/javascript">
    let map;

    function initMap() {
        //Map starter
        var markers = [
            <?php 
                if($location){
                    foreach($location as $loc){
                        $full_coor = $loc->content_loc;
                        $full_coor = json_decode($full_coor);

                        foreach($full_coor as $fc){
                            if($fc->type == "name"){
                                $name = $fc->detail;
                            }
                            if($fc->type == "coordinate"){
                                $coor = explode(", ", $fc->detail);
                                echo '{
                                    coords: {lat: '.$coor[0].', lng: '.$coor[1].'},
                                    content: \'<div><h6>'.str_replace("'", "\'", $loc->content_title).'</h6><p>'.str_replace("'", "\'", $loc->content_desc).'</p><b><i class="fa-solid fa-house"></i> '.$name.'</b><br><b><i class="fa-regular fa-circle-dot"></i> '.$coor[0].', '.$coor[0].'</b><hr><a class="btn btn-primary py-1 px-2" onclick="location.href=`/event/detail/'.$loc->slug_name.'`;">See Detail</a></div>\'
                                },';
                            }
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
            if($location){
                $total = count($location);

                for($i = 0; $i < $total; $i++){
                    echo "addMarker(markers[".$i."]);";
                }
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