<style>
    #map {
        height:300px;
        border-radius: 10px;
        margin-top: 6px;
        margin-bottom: 6px;
        box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
    }
</style>

<div id="map"></div>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDXu2ivsJ8Hj6Qg1punir1LR2kY9Q_MSq8&callback=initMap&v=weekly" defer></script>

<script type="text/javascript">
    let map;

    function initMap() {
        map = new google.maps.Map(document.getElementById("map"), {
            center: { lat: -6.969350413790824, lng: 107.62818479205987 },
            zoom: 16,
        });
    }
    
    window.initMap = initMap;
</script>