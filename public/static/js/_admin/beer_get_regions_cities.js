var cityObs = new PPObserver();
var regionObs = new PPObserver();
var countryObs = new PPObserver();

var _regions = new Array();
var _cities = new Array();

var _countryId;
var _regionId;
var _cityId;
var _cityName;

$(function() {
    _countryId = $('#country option:selected').val();
    _regionId = $('#region_id').val();
    _cityId = $('#city_id').val();
    
    var _finishedRequest = true;
    
    $('.form_auto_regions_cities').submit(function() {
        if(_regionId == null)
        {
            var regVal = $("#region").val();
            if(_regions[regVal] == undefined) {
                $("#region").val('');
                _regionId = '';
            } else {
                _regionId = _regions[regVal];
            }
        }
        $('#region_id').attr('value', _regionId); 
        
        if(_cityId == null)
        {
            var cityVal = $("#city").val();
            if(_cities[cityVal] == undefined) {
                $("#city").val('');
                _cityId = '';
            } else {
                _cityId = _cities[cityVal];
            }
        }
        $('#city_id').attr('value', _cityId);
    });
    
    $('#country').change(function() {
        _countryId = $(this).attr('value');
        if(_countryId == 0) {
            $('#region').attr('disabled', 'disabled');     
            $('#city').attr('disabled', 'disabled'); 
        } else {
            $('#region').removeAttr('disabled');
            $('#city').attr('disabled', 'disabled');
        }    
        
        _regionId = null;
        _cityId = null;
        _cityName = null;
        
        $('#region').val('');
        $('#city').val('');
        $('#region_id').val('');
        $('#city_id').val('');
        
        countryObs.notifyObservers();
    });
    
    $('#region').typeahead({
        minLength: 1,
        source: function(query, process) {
            if(!_finishedRequest) return;
            _finishedRequest = false;
            
            $.getJSON(base_url + 'beer/getregions',
                { countryId: _countryId, query: query }, 
                function (response) {   
                    _finishedRequest = true;
                    _regions = new Array();

                    var data = new Array(); 
                    for (var i in response) {
                        data.push(
                            response[i]['name']
                        );
                        _regions[response[i]['name']] = response[i]['id'];
                    }            
    
                    return process(data);
                })
                .error(function() {
                    alert('500 - Internal Server Error');
                });
        },
        updater: function(item) {
            _regionId = _regions[item];
            _cityName = null;
            _cityId = null;
            $('#region_id').attr('value', _regionId); 
            $('#city_id').val('');
            $('#city').removeAttr('disabled')
                      .val('');
                      
            regionObs.notifyObservers();
                     
            return item;
        }
    }).keypress(function() {
        _regionId = null;
    });
    
    $('#city').typeahead({
        minLength: 3,
        source: function(query, process) {
            if(!_finishedRequest) return;
            _finishedRequest = false;

            $.getJSON(base_url + 'beer/getcities',
                { regionId: _regionId, query: query }, 
                function (response) {   
                    _finishedRequest = true;
                    _cities = new Array();
                    
                    var data = new Array(); 
                    for (var i in response) {
                        data.push(
                            response[i]['name']
                        );
                        _cities[response[i]['name']] = response[i];
                    }
                     
                    return process(data);
                }).error(function() {
                    alert('500 - Internal Server Error');
                });
        },
        updater: function(item) {
            _cityName = item;
            _cityId = _cities[item]['id'];
            $('#city_id').attr('value', _cityId);  
            
            cityObs.notifyObservers();
            
            return item;
        }
    }).keypress(function() {
        _cityId = null;
    });
});