$(function() {   
    function cleanManufacturer()
    {
        $('#manufacturer').attr('disabled', 'disabled')
                          .find('option').remove();
    }

    $('#distributor').change(function() {
        disId = $(this).attr('value');
        if(disId == 0) {
            cleanManufacturer();
        } else {
             $.getJSON(base_url + "beer/getmanufacturers", 
                    { id: disId },
                    function(data) {
                        $('#manufacturer').find('option').remove();
                        $.each(data, function(key, val) {
                            $('#manufacturer').append('<option value="' + key + '">' + val + '</option>');
                        });
                        $('#manufacturer').removeAttr('disabled');
                    })
                    .error(function() {
                        cleanManufacturer();
                        alert('500 - Internal Server Error');
                    })
        }
    });
});