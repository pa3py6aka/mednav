var ControlPanelHead = {} || ControlPanelHead;
ControlPanelHead = (function () {

    function updateListeners() {
        $('[data-toggle="tooltip"]').tooltip();
    }

    function init() {
        updateListeners();
    }

    return {
        init: init
    };
})(jQuery);

var Mednav = Mednav || {};
Mednav = (function () {
    var Public = {
        pluralize: function (iNumber, aEndings) {
            var sEnding, i;
            iNumber = iNumber % 100;
            if (iNumber>=11 && iNumber<=19) {
                sEnding=aEndings[2];
            }
            else {
                i = iNumber % 10;
                switch (i)
                {
                    case (1): sEnding = aEndings[0]; break;
                    case (2):
                    case (3):
                    case (4): sEnding = aEndings[1]; break;
                    default: sEnding = aEndings[2];
                }
            }
            return sEnding;
        },
        scrollTo: function ($el, margin) {
            margin = margin ? margin : 0;
            $('html, body').animate({
                scrollTop: $el.offset().top + margin
            }, 700);
        },
        overlay: '<div class="overlay"><i class="fa fa-refresh fa-spin"></i></div>'
    };

    return {
        public: Public
    };
})();

$(function () {
    ControlPanelHead.init();
});