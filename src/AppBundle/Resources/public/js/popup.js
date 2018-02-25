(function ($) {
    "use strict";
    $('[data-href]').attr('href', $('[data-href]').data('href'));

    $('body').on('click', function (e) {

        var $el = $(e.target);

        if(!$el.is('[data-href]')){
            $el = $(e.target).parents('[data-href]');
        }
        if($el.length > 0){
            e.preventDefault();
        }

        $.ajax({
            type: 'GET', // define the type of HTTP verb we want to use (POST for our form)
            url: $el.data('href'), // the url where we want to POST
            dataType: 'json', // what type of data do we expect back from the server
        }).done(function (data, textStatus) {
            var $popup = $(data.form).appendTo('body');
            $popup.find('form').attr('action', $el.data('href'));
            $popup.modal('show');
        })

    });
}(jQuery));