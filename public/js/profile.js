function myDisplay(){
    // Je séléctionne la classe george 
    $("#george").toggle(
        function(){
            $(this).addClass("george"); // ajout display
        }, function(){
            $(this).removeClass("george"); // display none
        }
    );

}