(function($){
    $.fn.pp_multilang_field = function(name) {
        if(name == undefined) return;
        if($(this).size() <= 1) return;
        
        var firstField = $(this).first();
        var fieldsContainer = firstField.parent();
        var groupContainer = $(fieldsContainer).parent();

        var langs = $('<ul></ul>').prependTo(fieldsContainer)
                                  .addClass('multilang-tab')
                                  .attr('id', name + '-langs');

        var listFields = $('<ul></ul>').prependTo(fieldsContainer)
                                       .addClass('multilang-field')
                                       .attr('id', name + '-list');

        var field;
        var pField;
        var ppField;

        var lang;
        var langId;

        var activeLang = null;
        var activeField = null;

        var msgField;
        var textField;
        var liField;
   
        this.each(function(key,val) {
            langId = $(val).attr('lang');

            field = $('<li></li>').appendTo(listFields);
            field.attr('id', name + '-multilang-tab-' + langId);

            pField = $(val).parent();
            ppField = $(pField).parent();

            lang = $('<li><a href="#' + name + '-' + langId + '" lang="' + langId + '">' + langId + '</a></li>').appendTo(langs);

            if($(ppField).hasClass('error')) {
                $(groupContainer).addClass('error');
                if(activeLang == null) {
                    activeLang = lang;
                    activeField = field;
                    msgField = $(pField).find('span');
                    $(langs).after(msgField);
                }
            }

            $(val).appendTo(field);

            if(key != 0) {
                $(ppField).remove();
            }
        });

        if(activeLang == null) {
            $('#' + name + '-list li:lt(1)').addClass('active');
            $('#' + name + '-langs li:lt(1)').addClass('active');         
        } else {
            $(activeField).addClass('active');
            $(activeLang).addClass('active');   
        }

        $('#' + name + '-langs li a').click(function() {       
            $('#' + name + '-langs li').removeClass('active');
            $(this).parent().addClass('active');

            $('#' + name + '-list li').removeClass('active');
            liField = $('#' + name + '-multilang-tab-' + $(this).attr('lang'));
            liField.addClass('active');
            
            textField = liField.find('textarea');
            textField.focus();

            return false;
        });
    };
})(jQuery);