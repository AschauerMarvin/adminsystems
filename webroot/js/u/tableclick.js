$(function() {
    $(".tableclick").dblclick(function(evt) {
        //$( event.target ).closest('.doaction').css('display', 'none');

        if (evt.ctrlKey) {
            val = 1;
        } else {
            val = 0;
        }

        page = $(this).find('.doaction').get(val).href;
        if (!page && val == 1) {
            page = $(this).find('.doaction').get(0).href;
        }
        if (page) {
            window.location.href = page;
        }
        //alert($(this).find('.doaction').html());
        //alert($(this).html);
    });

});