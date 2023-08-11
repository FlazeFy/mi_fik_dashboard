<style>
    #map {
        height:400px;
        border-radius: var(--roundedSM);
        margin-top: 6px;
        margin-bottom: 6px;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
    }
</style>

<div>
    <div id="map"></div>
    <span id="content_loc_msg"></span>

    <div class="form-floating mt-3">
        <input type="text" class="form-control nameInput" id="content_loc_name" oninput="getContentLocation()">
        <label for="titleInput_event">{{ __('messages.loc_name') }}</label>
    </div>
    <input hidden name="content_loc" id="content_loc">
</div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDXu2ivsJ8Hj6Qg1punir1LR2kY9Q_MSq8&callback=initMap&v=weekly" defer></script>

<script type="text/javascript">
    let map;
    var coordinate;
    var loc_obj = [];

    function initMap() {
        map = new google.maps.Map(document.getElementById("map"), {
            center: { lat: -6.969350413790824, lng: 107.62818479205987 },
            zoom: 16,
        });

        map.addListener("click", (e) => {
            initMap();
            placeMarkerAndPanTo(e.latLng, map);
            addContentCoor(e.latLng);
        });
    }

    function placeMarkerAndPanTo(latLng, map) {
        new google.maps.Marker({
            position: latLng,
            map: map,
            icon: {
                url: 'https://maps.google.com/mapfiles/ms/icons/orange-dot.png',
                scaledSize: new google.maps.Size(40, 40),
            }
        });
        map.panTo(latLng);
    }

    function addContentCoor(coor){
        coor = coor.toJSON()
        coordinate = coor['lat']+", "+coor['lng']
        getContentLocation()
    }

    function getContentLocation(){
        var loc_name = $("#content_loc_name").val();

        if(loc_name == ''){
            loc_name = null
        }

        if(coordinate){
            loc_obj[1] = {
                "type": "coordinate", 
                "detail": coordinate, 
            };

            loc_obj[0] = {
                "type": "name", 
                "detail": loc_name, 
            };

            if(loc_name == '' || loc_name == null){
                $("#content_loc_msg").text("Location is valid, but you can also provide the location name")
                $("#content_loc_msg").css({"color":"var(--primaryColor)"})
            } else {
                $("#content_loc_msg").text("Location is valid")
                $("#content_loc_msg").css({"color":"var(--successBG)"})
            }
        } else {
            loc_obj[0] = {
                "type": "name", 
                "detail": loc_name, 
            };
            
            $("#content_loc_msg").text("Location is invalid, please provide the coordinate using maps")
            $("#content_loc_msg").css({"color":"var(--warningBG)"})

            error = true
        }   

        if(loc_obj){
            document.getElementById('content_loc').value = JSON.stringify(loc_obj);
        }
    }

    
    window.initMap = initMap;
</script>