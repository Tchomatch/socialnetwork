function charger(id){
    // j'utilise un setTimeout avec un parametre de 5000 ms = 5s
    setTimeout( function(){
        // j'appelle ma fonction chargerChat pour appeler mon ajax et remplacer les anciens par les anciens et  nouveaux messages 
        chargerChat(id);
    }, 5000); // on exécute le chargement toutes les 5 secondes

}

function chargerChat(id) {

    $.ajax({
        method: "POST",
         // url de la page que je récupérer et inclure dans le code + l'id de l'utilisateur
        url: "/chat/"+id, 
      })
    .done(function( dataMessages ) {
        // je rappelle la fonction charger pour recharger mes messages toutes les 5 secondes
        charger(id);
        //je récupère le code et les données dans l'url renseigné au dessus et l'inclure dans la balise contenant l'id searchDyn
        $('#chatMessages').html(dataMessages);
    });
}


