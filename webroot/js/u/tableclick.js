$(function() {
    $(".tableclick").dblclick(function(evt) {
        //$( event.target ).closest('.doaction').css('display', 'none');

        if (evt.ctrlKey) {
            val = 1;
        } else {
            val = 0;
        }

        page = $(this).find('.doaction').get(val).id;
        if (!page && val == 1) {
            page = $(this).find('.doaction').get(0).id;
        }
        if (page) {
            window.location.href = window.location.pathname + '/' + page;
        }
        //alert($(this).find('.doaction').html());
        //alert($(this).html);
    });

});