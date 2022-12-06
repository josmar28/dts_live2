/**
 * Created by Lourence on 12/4/2016.
 */
(function($){
    $('#main-menu').smartmenus({
        subMenusOffsetX : 6,
        subMenusOffsetY : -8
    });
    $('span').removeClass('caret');
})($);
$(function() {
    var $mainMenuState = $('#main-menu-state');
    if ($mainMenuState.length) {
        // animate mobile menu
        $mainMenuState.change(function(e) {
            var $menu = $('#main-menu');
            if (this.checked) {
                $menu.hide().slideDown(250, function() { $menu.css('display', ''); });
            } else {
                $menu.show().slideUp(250, function() { $menu.css('display', ''); });
            }
        });
        // hide mobile menu beforeunload
        $(window).bind('beforeunload unload', function() {
            if ($mainMenuState[0].checked) {
                $mainMenuState[0].click();
            }
        });
    }
});