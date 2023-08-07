<style>
    #map-event {
        height:420px;
        border-radius: var(--roundedSM);
        margin-top: 6px;
        margin-bottom: 6px;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
    }
</style>

<div class="my-3">
    <form action="/event/edit/update/loc/add/{{$c->slug_name}}" method="POST">
        @csrf
        <input hidden name="content_loc" id="content_loc">
        <input hidden name="content_title" value="{{$c->content_title}}">
        <span id="content_loc_msg"></span>
        <div id="map-event"></div>
        <div id="map-save-button-holder"></div>
    </form>
    @if($c->content_loc && $c->content_loc[0]['detail'])
        <div class="form-floating my-2">
            <input type="text" class="form-control" id="content_loc_name" placeholder="{{$c->content_loc[0]['detail']}}" value="{{$c->content_loc[0]['detail']}}" oninput="getContentLocation()">
            <label for="content_loc_name">{{ __('messages.loc_name') }}</label>
        </div>
    @else
        <div class="form-floating mb-2">
            <input type="text" class="form-control" id="content_loc_name" placeholder="" value="" oninput="getContentLocation()">
            <label for="content_loc_name">{{ __('messages.loc_name') }}</label>
        </div>
    @endif

    @if($c->content_loc && count($c->content_loc) != 2)
        @include('components.infobox', ['info' => $info, 'location'=> "invalid_location"])
    @endif
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDXu2ivsJ8Hj6Qg1punir1LR2kY9Q_MSq8&callback=initMap&v=weekly" defer></script>

<script type="text/javascript">
    let map;
    var coordinate_new;
    var coordinate_old;
    var loc_obj = [];
    <?php 
        if($c->content_loc && count($c->content_loc) == 2){
            echo 'coordinate_old = "'.$c->content_loc[1]['detail'].'"';
        }
    ?>

    function initMap() {
        <?php 
            if($c->content_loc && count($c->content_loc) == 2){
                $full_coor = $c->content_loc[1]['detail'];
                $coor = explode(", ", $full_coor);
                echo "let latitude= ".$coor[0].";";
                echo "let longitude= ".$coor[1].";";
                echo "let markers= [{
                    coords:{lat:".$coor[0].",lng:".$coor[1]."},
                    content:`<div><h4>".str_replace("'", "\'", $c->content_title)."</h4><p>".str_replace("'", "\'", $c->content_desc)."</p></div>`
                    }];";

                echo 'map = new google.maps.Map(document.getElementById(`map-event`), {
                    center: { lat: latitude, lng: longitude},
                    zoom: 15,
                });';
            } else {
                echo 'map = new google.maps.Map(document.getElementById(`map-event`), {
                    center: { lat: -6.969350413790824, lng: 107.62818479205987 },
                    zoom: 15,
                });';
            }
        ?>
        map.addListener("click", (e) => {
            initMap();
            placeMarkerAndPanTo(e.latLng, map);
            addContentCoor(e.latLng);
        });

        if(coordinate_old){
            addMarker(markers[0]);

            function addMarker(props){
                var marker = new google.maps.Marker({
                    position:props.coords,
                    map:map,
                    icon: {
                        url: `https://maps.google.com/mapfiles/ms/icons/orange-dot.png`,
                        scaledSize: new google.maps.Size(40, 40),
                    }
                });

                if(props.iconImage){
                    marker.setIcon(props.iconImage);
                }
                if(props.content){
                    var infoWindow = new google.maps.InfoWindow({
                    content:props.content
                });
                marker.addListener(`click`, function(){
                    infoWindow.open(map, marker);
                });
                }
            }
        }
    }

    function placeMarkerAndPanTo(latLng, map) {
        new google.maps.Marker({
            position: latLng,
            map: map,
        });
        map.panTo(latLng);
    }

    function addContentCoor(coor){
        coor = coor.toJSON();
        coordinate_new = coor[`lat`]+`, `+coor[`lng`];
        getContentLocation();
    }

    function getContentLocation(){
        var loc_name = $(`#content_loc_name`).val();

        loc_name == '' ? loc_name = null : null;

        if(coordinate_old){
            if(coordinate_new){
                loc_obj[1] = {
                    "type": "coordinate", 
                    "detail": coordinate_new, 
                };

                loc_obj[0] = {
                    "type": "name", 
                    "detail": loc_name, 
                };

                if(loc_name == '' || loc_name == null){
                    $("#content_loc_msg").text("Location is valid, but you can also provide the location name");
                    $("#content_loc_msg").css({"color":"var(--primaryColor)"});
                } else {
                    $("#content_loc_msg").text("Location is valid");
                    $("#content_loc_msg").css({"color":"var(--successBG)"});
                }
                document.getElementById('map-save-button-holder').innerHTML = `<button class="btn btn-submit mt-2" type="submit" onclick="getRichText()"><i class="fa-solid fa-floppy-disk"></i> {{ __('messages.save') }}</button>`; 
            } else {
                loc_obj[1] = {
                    "type": "coordinate", 
                    "detail": coordinate_old,
                };
                loc_obj[0] = {
                    "type": "name", 
                    "detail": loc_name, 
                };
                
                $("#content_loc_msg").text("Location coordinate is same as before");
                $("#content_loc_msg").css({"color":"var(--primaryColor)"});
                document.getElementById('map-save-button-holder').innerHTML = `<button class="btn btn-submit mt-2" type="submit" onclick="getRichText()"><i class="fa-solid fa-floppy-disk"></i> {{ __('messages.save') }}</button>`; 

                error = true
            }   
        } else {
            if(coordinate_new){
                loc_obj[1] = {
                    "type": "coordinate", 
                    "detail": coordinate_new, 
                };

                loc_obj[0] = {
                    "type": "name", 
                    "detail": loc_name, 
                };

                if(loc_name == '' || loc_name == null){
                    $("#content_loc_msg").text("Location is valid, but you can also provide the location name");
                    $("#content_loc_msg").css({"color":"var(--primaryColor)"});
                } else {
                    $("#content_loc_msg").text("Location is valid");
                    $("#content_loc_msg").css({"color":"var(--successBG)"});
                }

                document.getElementById('map-save-button-holder').innerHTML = `<button class="btn btn-submit mt-2" type="submit" onclick="getRichText()"><i class="fa-solid fa-floppy-disk"></i> {{ __('messages.save') }}</button>`; 
            } else {
                loc_obj[0] = {
                    "type": "name", 
                    "detail": loc_name, 
                };
                
                $("#content_loc_msg").text("Location is invalid, please provide the coordinate using maps");
                $("#content_loc_msg").css({"color":"var(--warningBG)"});

                error = true;
            }   
        }

        loc_obj ? (document.getElementById(`content_loc`).value = JSON.stringify(loc_obj)) : null;
    }
    
    window.initMap = initMap;
</script>