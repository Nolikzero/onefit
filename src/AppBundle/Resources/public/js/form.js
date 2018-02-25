(function ($) {
    "use strict";
    $('body').on('submit', function (e) {
        if($(e.target).is('[data-form]')) {
            var formData = $(e.target).serialize();
            console.log($(e.target));
            $.ajax({
                type: 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url: $(e.target).attr('action'), // the url where we want to POST
                data: formData, // our data object
            }).done(function (data, textStatus) {
                $('.modal.show').modal('hide');
                $('[data-content]').reloadPage({ replace: '[data-content]' });
            });

            e.preventDefault();
            return false;
        }
    });
}(jQuery));