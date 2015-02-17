$(function() {
    var nr_parent = 0;

    $('.controls-beerfamily').each(function() {
        $(this).attr('id', 'parent_' + nr_parent);
        $(this).attr('name', 'parent_' + nr_parent);
        addRemoveButton(nr_parent);
        nr_parent++;
    });

    function addRemoveButton(id_element)
    {
        $("#parent_" + id_element).parent().append("<button class=\"btn-mini btn-danger btn\" type=\"button\" id=\"removeParent_" + nr_parent + "\" name=\"removeParent_" + nr_parent + "\"><i class=\"icon-minus\" />" + MSG_DELETE + "</button>");
        $("#removeParent_" + id_element).click(function() {
            nr_parent--;
            $(this).unbind();
            $(this).parent().parent().remove();
        });
    }

    $('#addParent').click(function() {     
        $(this).before("\
            <div class=\"control-group control-without-label\">\
                <div class=\"controls\"><select id=\"parent_" + nr_parent + "\" name=\"parent_" + nr_parent + "\" class=\"controls-beerfamily\"></select></div>\
            </div>");
        
        addRemoveButton(nr_parent);
        
        $("#parent option").each(function()
        {
            $("#parent_" + nr_parent).append($("<option></option>")
                                    .attr("value", $(this).val())
                                    .text($(this).text()));
        });
        
        nr_parent++;
        
        return false;
    });
});