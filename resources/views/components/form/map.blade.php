@props(['lat' => 24.815310807697905 , 'long' => 46.67454711582318 ])
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBN2eSffJ5hsO8Qay2hXPPuDBddQBw98gQ&callback=initMap" async defer></script>
<script>
    let map;

    function initMap() {
        map = new google.maps.Map(document.getElementById("map"), {
            center: {lat: {{$lat}}, lng: {{$long}} },
            zoom: 8,
            scrollwheel: true,
        });
        const uluru = {lat: {{$lat}}, lng: {{$long}} };
        let marker = new google.maps.Marker({
            position: uluru,
            map: map,
            draggable: true
        });
        google.maps.event.addListener(marker, 'position_changed',
            function () {
                let lat = marker.position.lat()
                let lng = marker.position.lng()
                $('#lat').val(lat)
                $('#lng').val(lng)
            })
        google.maps.event.addListener(map, 'click',
            function (event) {
                pos = event.latLng
                marker.setPosition(pos)
            })
    }
</script>
