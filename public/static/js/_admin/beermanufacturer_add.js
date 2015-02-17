$(function() {
    var marker = null;
    var map;
    var mapIsLoaded = false;
    var isManuallyChangeLocation = false;

    var latitude = $("#latitude").val();
    var longitude  = $("#longitude").val();

    function placeMarker(location) {
        $("#latitude").val(location.lat().toFixed(5));
        $("#longitude").val(location.lng().toFixed(5));
        
        if (marker != null) {
            marker.setPosition(location);
        } else {
            marker = new google.maps.Marker({
                position: location,
                map: map
            });
        }
    }
    
    function resetFields()
    {
        $("#latitude").val("");
        $("#longitude").val("");
        $('#map_canvas').remove();
        mapIsLoaded = false;
        $('#latitude').attr('disabled', 'disabled');
        $('#longitude').attr('disabled', 'disabled');
    }
    
    function manuallyChangeLocation()
    {
        isManuallyChangeLocation = true;
        latitude = $("#latitude").val();
        longitude = $("#longitude").val();
        if(latitude && longitude)
        {
            cityObs.notifyObservers();
        }        
    }
    
    countryObs.addObserver(function() {
        resetFields();
    });
    
    regionObs.addObserver(function() {
        resetFields();
    });
    
    cityObs.addObserver(function() {
        var city = _cities[_cityName];
        
        if(city != undefined && !isManuallyChangeLocation) {
            latitude = city['latitude'];
            longitude = city['longitude'];
        }
        
        if(!mapIsLoaded)
        {
            mapIsLoaded = true;
            $('#city_id').after('<div id="map_field"><div id="map_canvas"></div></div>');
            var mapOptions = {
                zoom: 15,
                center: new google.maps.LatLng(latitude, longitude),
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
            google.maps.event.addListener(map, 'click', function(event) {
                placeMarker(event.latLng);
            }); 
        } else {
            map.setCenter(new google.maps.LatLng(latitude, longitude));
        }
        
        $('#latitude').removeAttr('disabled');
        $('#longitude').removeAttr('disabled');
        
        placeMarker(new google.maps.LatLng(latitude, longitude));
    });
    
    if(latitude != '' && longitude != '')
    {
        cityObs.notifyObservers();
    }
    
    $("#latitude").change(function() {
        manuallyChangeLocation();
    });
    
    $("#longitude").change(function() {
        manuallyChangeLocation();
    });
});