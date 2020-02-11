function charger(id){

    console.log(3);

    setTimeout( function(){
        
        chargerChat(id);
    }, 5000); // on exécute le chargement toutes les 5 secondes

}

function chargerChat(id) {

    $.ajax({
        method: "POST",
        url: "/chat/"+id, 
      })
    .done(function( dataMessages ) {
        charger(id);
        $('#chatMessages').html(dataMessages);
    });
}


