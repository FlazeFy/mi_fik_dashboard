<style>
    #map-event {
        height:420px;
        border-radius: 10px;
        margin-top: 6px;
        margin-bottom: 6px;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
    }
</style>

<div class="my-3">
    @if($c->content_loc[0]['detail'])
        <div class="form-floating mb-2">
            <input type="text" class="form-control" id="floatingPassword" placeholder="{{$c->content_loc[0]['detail']}}" value="{{$c->content_loc[0]['detail']}}" required>
            <label for="floatingPassword">Location Name</label>
        </div>
    @else
        <div class="form-floating mb-2">
            <input type="text" class="form-control" id="floatingPassword" placeholder="" value="" required>
            <label for="floatingPassword">Location Name</label>
        </div>
    @endif
    <div id="map-event"></div>
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDXu2ivsJ8Hj6Qg1punir1LR2kY9Q_MSq8&callback=initMap&v=weekly" defer></script>

<script type="text/javascript">
    let map;

    function initMap() {
        //Map starter
        <?php 
            $full_coor = $c->content_loc[1]['detail'];
            $coor = explode(", ", $full_coor);
            echo "let latitude= ".$coor[0].";";
            echo "let longitude= ".$coor[1].";";
            echo "let markers= [{
                coords:{lat:".$coor[0].",lng:".$coor[1]."},
                content:'<div><h4>".$c->content_title."</h4><p>".$c->content_desc."</p></div>'
                },];";
        ?>

        map = new google.maps.Map(document.getElementById("map-event"), {
            center: { lat: latitude, lng: longitude},
            zoom: 15,
        });

        addMarker(markers[0]);

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