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

$(function () {
    ControlPanelHead.init();
});