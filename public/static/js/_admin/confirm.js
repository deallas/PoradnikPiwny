$().ready(function() {
    $('#modal-from-dom').bind('show', function() {
        var msg;
        if(typeof MSG_DANGER_BTN === "undefined") {
            msg = 'Are you sure you want to do this?';
        } else {
            msg = MSG_DANGER_BTN;
        }
        
        var confirmMsg;
        if(typeof QUESTION_DANGER_BTN === "undefined") {
            confirmMsg = 'Question';
        } else {
            confirmMsg = QUESTION_DANGER_BTN;
        }
        
        var acceptMsg;
        if(typeof ACCEPT_DANGER_BTN === "undefined") {
            acceptMsg = 'Accept';
        } else {
            acceptMsg = ACCEPT_DANGER_BTN;
        }
        
        var cancelMsg;
        if(typeof CANCEL_DANGER_BTN === "undefined") {
            cancelMsg = 'Cancel';
        } else {
            cancelMsg = CANCEL_DANGER_BTN;
        }
        
        $(this).find('h3').text(confirmMsg);
        $(this).find('.modal-body').text(msg);
        $(this).find('.btn-yes').attr('href', $(this).data('href'))
                                .text(acceptMsg);                        
        $(this).find('.btn-no').text(cancelMsg);                                
    });

    $('.btn-confirm').click(function(e) {
        e.preventDefault();

        $('#modal-from-dom').data('href', $(this).attr('href')).modal('show');
    });
    
    $('.btn-no').click(function(e) {
        e.preventDefault();
        
        $('#modal-from-dom').modal('hide');
    });
});