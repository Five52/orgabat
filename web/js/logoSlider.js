var target = 0;
var duration = 40000;
var width = 0;
window.addEventListener('load', initNosMarques);


function initMarque() {

    target = $(".block .view-id-marques .view-content .views-row-first img").width();
    $(".block .view-id-marques .view-content").animate({
        left: '-=' + target,
    }, {
        duration: Math.floor((target * duration) / width),
        easing: "linear",
        step: function(now, fx) {
            if (now === (-1 * target)) {
                $(".block .view-id-marques .views-row-first").appendTo(".block .view-id-marques .view-content");
                $(".block .view-id-marques .views-row-first").removeClass("views-row-first");
                $(".block .view-id-marques .views-row").first().addClass("views-row-first");
            }
        },
        complete: function() {
            $(".block .view-id-marques .view-content").css("left", 0);
            initMarque();
        }
    });
}

function initNosMarques() {
    $(".block .view-id-marques .view-content img").each(function() {
        width += $(this).width();
    });
    target = $(".block .view-id-marques .view-content .views-row-first img").width();
    $(".block .view-id-marques .view-content").width(width);
    $(".block .view-id-marques .view-content").mouseenter(
        function(e) {
            $(this).stop(true, false);
        }).mouseleave(
        function(e) {
            var $this = $(this);
            var cur = parseInt($this.css('left'));
            target = $(".block .view-id-marques .views-row-first img").width();
            $this.animate({
                "left": (-1 * target),
            }, {
                duration: Math.floor(((target - (cur * (-1))) * duration) / width),
                easing: 'linear',
                step: function(now, fx) {
                    if (now === (-1 * target)) {
                        $(".block .view-id-marques .views-row-first").appendTo(".block .view-id-marques .view-content");
                        $(".block .view-id-marques .views-row-first").removeClass("views-row-first");
                        $(".block .view-id-marques .views-row").first().addClass("views-row-first");
                        target = $(".block .view-id-marques .views-row-first img").width();
                    }
                },
                complete: function() {
                    $(".block .view-id-marques .view-content").css("left", 0);
                    initMarque();
                }
            });
        });
    initMarque();
}
