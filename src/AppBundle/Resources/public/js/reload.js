(function ($) {
    "use strict";
    $.fn.reloadPage = function(options){
        var $this = $(this);
        $.ajax({
            type: 'GET', // define the type of HTTP verb we want to use (POST for our form)
            url: window.location.href, // the url where we want to POST
        }).done(function (data) {
            $this.replaceWith($(data).find(options.replace));
        });
    };
}(jQuery));