/**
 * Created by forej_000 on 20.12.2016.
 */
$(document).ready(function() {

    $('.sport-tools a').click(function(e) {
        e.preventDefault();

        var sport_id = $(this).data('id');
        var sport_box = $('.sports-item-box[data-id="'+ sport_id +'"]');
        var tools_box = sport_box.find('.sport-tools-box');

        if(!sport_box.find('.checkbox input').is(':checked')) return;

        if(tools_box.css('display') == 'none') {
            tools_box.slideDown();
            $(this).find('.glyphicon-chevron-down').css('display', 'none');
            $(this).find('.glyphicon-chevron-up').css('display', 'block');
        } else {
            tools_box.slideUp();
            $(this).find('.glyphicon-chevron-up').css('display', 'none');
            $(this).find('.glyphicon-chevron-down').css('display', 'block');
        }
    });

});