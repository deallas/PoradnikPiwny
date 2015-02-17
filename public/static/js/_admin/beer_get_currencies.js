$(function() {   
    var nr_price = 0;
    var l_currencies = currencies.length;
    
    function addRemoveButton(id_element)
    {
        var pPrice = $("#price_currency_" + id_element).parent();
        var but = "<button class=\"btn-mini btn-danger btn\" type=\"button\" id=\"removePrice_" + nr_price + "\" name=\"removePrice_" + nr_price + "\"><i class=\"icon-minus\" />" + MSG_DELETE + "</button>";
        var mField = $(pPrice).find('span.help-inline');

        if(mField.length) {
            mField.before(but);
        } else {
            $(pPrice).append(but);
        }
        $("#removePrice_" + id_element).click(function() {
            nr_price--;
            $(this).unbind();
            $(this).parent().parent().remove();
        });
    }
    
    function setupField(element, id, name)
    {
        $(element).attr('id', 'price_' + name + '_' + id);
        $(element).attr('name', 'price_' + id + '[' + name + ']');        
    }
    
    function setupCurrencyChanger(id)
    {
        $('#price_currency_' + id).change(function() {
            $(this).parent().find('.add-on-number').html(
                $(this).find('option:selected').attr('symbol')
            ); 
        });
    }
    
    $('.t_price_number').each(function() {
        setupField(this, nr_price, 'number');
        nr_price++;
    });
    
    nr_price = 0;
    
    $('.s_price_currency').each(function() {
        setupField(this, nr_price, 'currency');
        setupCurrencyChanger(nr_price);
        
        nr_price++;        
    });
    
    nr_price = 0;
    
    $('.t_price_sizeOfBottle').each(function() {
        setupField(this, nr_price, 'sizeOfBottle');
        addRemoveButton(nr_price);
        nr_price++;        
    });
    
    $('#addPrice').click(function() { 
        $(this).before("<label class=\"control-label optional\" for=\"price_" + nr_price + "\">[" + (nr_price+1) + "]</label>\
                        <div class=\"control-group\">" +
                            "<div class=\"controls controls-price\">" +
                                "<div class=\"input-append t_price_number_container\">" +
                                    "<input type=\"text\" class=\"t_price_number\" value=\"\" id=\"price_number_" + nr_price + "\" name=\"price_" + nr_price + "[number]\"><span class=\"add-on add-on-number\"></span>" +
                                "</div>" +
                                "<select class=\"s_price_currency\" id=\"price_currency_" + nr_price + "\" name=\"price_" + nr_price + "[currency]\"></select>" +
                                "<div class=\"input-append t_price_sizeOfBottle_container\">" +
                                    "<input type=\"text\" class=\"t_price_sizeOfBottle\" value=\"\" id=\"price_sizeOfBottle_" + nr_price + "\" name=\"price_" + nr_price + "[sizeOfBottle]\"><span class=\"add-on add-on-sizeOfBottle\">ml</span>" +
                                "</div>" +
                            "</div>" +
                        "</div>");  
        
        addRemoveButton(nr_price);
        setupCurrencyChanger(nr_price);
        
        var c = null;
        for (var i = 0; i < l_currencies; i++) {
            c = currencies[i];
            $('#price_currency_' + nr_price).append('<option value="' + c.id + '" symbol="' + c.symbol + '">' + c.name + '</option>');
        }
        $("#price_number_" + nr_price).parent().find('span').html(
            $('#price_currency_' + nr_price + ' option:first-child').attr('symbol')
        );
        
        nr_price++;
        
        return false;
    });
});