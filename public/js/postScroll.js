$body = $("body");

$(document).on({
    ajaxStart: function() { $body.addClass("loading");    },
    ajaxStop: function() { $body.removeClass("loading"); }    
});

function scrollPost(){
    
}

$(window).scroll(function() {
    if($(window).scrollTop() == $(document).height() - $(window).height()) {
        $.ajax({
            method: "POST",
            async: true,
            url: "/",
            success: function(){
                
            }
        })
        
    }
});
