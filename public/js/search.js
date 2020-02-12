function searchDyn(){
    $( "#search" ).keyup(function() {
        $.ajax({
            method: "POST",
            url: "/search2",
            // je récupère la valeur de mon input avec son name 
            data: { search: $(this).val() }
        })
        .done(function( dataSearch ) {
            $('#searchDyn').html(dataSearch);
        });
    });

 
}
