$(function() {
    $("#b_options_container").click(function() {
        if($("#options_container_form").is(":hidden")) {
            $("#b_options_container i").removeClass();
            $("#b_options_container i").addClass("icon-caret-down");
        } else {
            $("#b_options_container i").removeClass();
            $("#b_options_container i").addClass("icon-caret-right");                    
        }
        $("#options_container_form").slideToggle();
        return false;
    });

    $("#b_options_container_right").click(function() {
        $("#options_container_orders_availables option:selected").remove().appendTo("#options_container_orders");
        return false;
    });
    $("#b_options_container_left").click(function() {
        $("#options_container_orders option:selected").remove().appendTo("#options_container_orders_availables");
        return false;
    });
    $("#b_options_container_up").click(function() {
        $("#options_container_orders option:selected").each( function() {
            var newPos = $("#options_container_orders option").index(this) - 1;
            if (newPos > -1) {
                $("#options_container_orders option").eq(newPos).before('<option value="' + $(this).val() + '" selected="selected">' + $(this).text() + '</option>');
                $(this).remove();
            }
        });            

        return false;
    });
    $("#b_options_container_down").click(function() {
        var countOptions = $("#options_container_orders option").size();
        $("#options_container_orders option:selected").each( function() {
            var newPos = $("#options_container_orders option").index(this) + 1;
            if (newPos < countOptions) {
                $("#options_container_orders option").eq(newPos).after('<option value="' + $(this).val() + '" selected="selected">' + $(this).text() + '</option>');
                $(this).remove();
            }
        });            

        return false;
    });

    $("#b_options_container_save").click(function() {
        $("#options_container_orders option").prop("selected", "selected");
    });
});