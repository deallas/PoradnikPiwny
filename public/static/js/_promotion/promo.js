var _email;
var _statute;

var MSG_EMAIL_WRONG = 'Podano niepoprawny adres email';
var MSG_STATUTE_NOT_ACCEPT = 'Nie zaakceptowano zgody na otrzymywanie emaili';
var MSG_OK = 'Dziękujemy za podanie adresu email.<br /> Powiadomimy Cię jak serwis zostanie uruchomiony.';
var MSG_ERROR = 'Coś poszło nie tak ;(';

$(function() {
    var f_height = $(window).height() - $("#container").height()-142;
    if(f_height > 150) {
        $("#footer").height(f_height);
    }
    
    $('#form_newsletter').submit(function() {
        $('#form_newsletter input').attr('disabled', 'disabled');
        $("#form_msg").hide();

        _email = $('#i_email').val();
        _statute = $('#i_statute').is(':checked');
        $.ajax({
            cache: false,
            type: "POST",
            url: base_url + "index/addnewsletter", 
            dataType: "JSON",
            data: { email: _email, statute: _statute ? 1:0 },
            success: function(data) {
                // reset errors
                $('#form_newsletter input').removeAttr('disabled');
                $('#i_email').removeClass('error_email');      
                $('#t_statute').removeClass('error_statute');
                $("#form_msg").removeClass('error_msg');

                if(data.status == 1) {
                    $("#form_msg").html(MSG_OK);
                    $("#form_newsletter").fadeOut(function() {
                        $("#form_msg").fadeIn();
                    });
                } else {
                    $("#form_msg").addClass('error_msg');
                    if(data.msg == 'email_wrong') {
                        $("#form_msg").html(MSG_EMAIL_WRONG);
                        $("#i_email").addClass('error_email');
                    } else if(data.msg == 'statute_not_accept') {
                        $("#form_msg").html(MSG_STATUTE_NOT_ACCEPT);
                        $("#t_statute").addClass('error_statute');
                    }
                    $("#form_msg").fadeIn();
                }
            },
            error : function() {
                $('#form_newsletter input').removeAttr('disabled');
                alert(MSG_ERROR);
            }
        });

        return false;
    });
});