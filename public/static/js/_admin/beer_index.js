$(function() {   
    var starsContent;
    
    var beerHref;
    var visiblePopover;
    
    var opts = {
        lines: 13, // The number of lines to draw
        length: 4, // The length of each line
        width: 2, // The line thickness
        radius: 5, // The radius of the inner circle
        corners: 1, // Corner roundness (0..1)
        rotate: 0, // The rotation offset
        color: '#000', // #rgb or #rrggbb
        speed: 1, // Rounds per second
        trail: 60, // Afterglow percentage
        shadow: false, // Whether to render a shadow
        hwaccel: false, // Whether to use hardware acceleration
        className: 'spinner', // The CSS class to assign to the spinner
        zIndex: 2e9, // The z-index (defaults to 2000000000)
        top: 0, // Top position relative to parent in px
        left: 0 // Left position relative to parent in px
    };
        
    $('.btn-beer-rank').popover({
       'title': MSG_RANKING_TITLE,
       'placement' : 'left',
       'content' : '<span class="rating"></span>'
    }).click(function() {
        opts['left']=95;
        var target = $('span.rating');
        var spinner;
        if(target.length>1)
        {
            spinner = new Spinner(opts).spin(target[1]);
        }
        else
        {
            spinner = new Spinner(opts).spin(target[0]);
        }
        $.ajaxSetup({async:false});
        starsContent='';
 
        var $this = $(this);
        beerHref = $(this).attr('href');
    
        if ($this.data('popover').tip().hasClass('in')) {
            visiblePopover && visiblePopover.popover('hide');
            visiblePopover = $this;
                                    
            $.getJSON(beerHref, { checkRank: 1 },function(json) {
                if(json!=null)
                {
                    var checked = parseInt(json*2);
                    for(var i = 1; i <= stars; i++)
                    {
                         if(i%2==1)
                         {
                             if(stars-i<checked)
                             {
                                 starsContent += '<a class="star star_rotated star_checked"></a>';
                             }
                             else
                             {
                                 starsContent += '<a class="star star_rotated star_nonchecked"></a>';
                             }
                         }
                         else
                         {
                             if(stars-i<checked)
                             {
                                 starsContent += '<a class="star star_checked"></a>';
                             }
                             else
                             {
                                 starsContent += '<a class="star star_nonchecked"></a>';
                             }
                         }
                    }
                }
            });
            $.ajaxSetup({async:true});
            spinner.stop();
            $('span.rating').text('');
            $('span.rating').append(starsContent);
        } else {
            visiblePopover = '';
        }
        
        $('.rating .star').hover(function() {
            var rank = $(this).index();
            
            if(rank == 0) {
                $('.popover-title').text(MSG_RANKING_TITLE + ' - ' + MSG_RANKING_A);
            } else if(rank == 1) {
                $('.popover-title').text(MSG_RANKING_TITLE + ' - ' + MSG_RANKING_B);
            } else if(rank == 2) {
                $('.popover-title').text(MSG_RANKING_TITLE + ' - ' + MSG_RANKING_C);
            } else if(rank == 3) {
                $('.popover-title').text(MSG_RANKING_TITLE + ' - ' + MSG_RANKING_D);
            } else if(rank == 4) {
                $('.popover-title').text(MSG_RANKING_TITLE + ' - ' + MSG_RANKING_E);
            } else if(rank == 5) {
                $('.popover-title').text(MSG_RANKING_TITLE + ' - ' + MSG_RANKING_F);
            } else if(rank == 6) {
                $('.popover-title').text(MSG_RANKING_TITLE + ' - ' + MSG_RANKING_G);
            } else if(rank == 7) {
                $('.popover-title').text(MSG_RANKING_TITLE + ' - ' + MSG_RANKING_H);
            } else if(rank == 8) {
                $('.popover-title').text(MSG_RANKING_TITLE + ' - ' + MSG_RANKING_I);
            } else if(rank == 9) {
                $('.popover-title').text(MSG_RANKING_TITLE + ' - ' + MSG_RANKING_J);
            }
        });
        
        $('.rating .star').click(function() {
            opts['left']=-40;
            var target = $this[0];
            var spinner = new Spinner(opts).spin(target);

            var rank = (stars - $(this).index())/2;
                        
            $.ajax({
                type: 'POST',
                url: beerHref,
                data: { 'rank': rank },
                success: function(avg) {
                    visiblePopover = '';
                    $('.btn-beer-rank').popover('hide');
                    $this.text('');
                    $this.attr('data-original-title', MSG_RANKING_ADDED);
                    $this.append('<i class="icon-star"></i>');
                    var checked=avg*2;
                    var updateStars='';
                    updateStars += ' <span class="avg_rating">';
                    for(var i = 1; i <= stars; i++)
                    {
                         if(i%2==1)
                         {
                             if(stars-i<checked)
                             {
                                 updateStars += '<a class="star star_rotated star_checked"></a>';
                             }
                             else
                             {
                                 updateStars += '<a class="star star_rotated star_nonchecked"></a>';
                             }
                         }
                         else
                         {
                             if(stars-i<checked)
                             {
                                 updateStars += '<a class="star star_checked"></a>';
                             }
                             else
                             {
                                 updateStars += '<a class="star star_nonchecked"></a>';
                             }
                         }
                    }
                    updateStars += '</span>';
                    $this.parent().prev('td.avg').text(parseFloat(avg).toFixed(2)).append(updateStars);
                    spinner.stop();
                },
                error: function() {
                    alert('500 - Internal Server Error');
                    spinner.stop();
                }
            });
            
            return false;
        });

        return false;
    });      
});