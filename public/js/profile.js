function myDisplay(){

    $("#george").toggle(
        function() {
            $(this).addClass("george");
        }, function() {
            $(this).removeClass("george");
        }
    );

}