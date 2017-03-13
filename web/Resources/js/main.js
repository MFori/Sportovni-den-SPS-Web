/**
 * Created by forej_000 on 14.12.2016.
 */
$(document).ready(function(){

    $('.menu-item').click(function(e){
        var menu = $(this).parent().find('.menu-drop');
        if(menu.css('display') == 'none') {
            $(this).find('.glyphicon-chevron-up').css('display', 'block');
            $(this).find('.glyphicon-chevron-down').css('display', 'none');
            menu.slideDown();
        } else {
            $(this).find('.glyphicon-chevron-up').css('display', 'none');
            $(this).find('.glyphicon-chevron-down').css('display', 'block');
            menu.slideUp();
        }
    });

});