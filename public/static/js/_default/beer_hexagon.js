$(function() {
    function groupHexs()
    {
        var hexW = $('.hex').width();
        var boardW = $('#board').width();
        var maxH = Math.floor((boardW - hexW/2) / hexW);

        if(maxH <= 0) maxH = 1;

        var maxH2 = maxH*2;
        var i = maxH;

        $('.hex').each(function() {
            $(this).removeClass('hex-even');
            $(this).removeClass('hex-even2');
            if(i == maxH2) {
                $(this).addClass('hex-even');
                i = 0;
            }
            if(i < maxH) {
                $(this).addClass('hex-even2');
            }

            i++;
        });
    }

    groupHexs();

    $(window).resize(function() {
        groupHexs();
    });

    $('.hex-link').hover(
        function() { $(this).find('.hex-image').stop().fadeTo(300, 0); },
        function() { $(this).find('.hex-image').stop().fadeTo(300, 1); }
    );
}); 