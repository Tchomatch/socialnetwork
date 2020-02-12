function searchDyn(){
    // je vérifie que l'utilisateur a bien appuyé sur une touche dans la barre de recherche grace a l'id de l'input
    $( "#search" ).keyup(function() {
        
        $.ajax({
            method: "POST",
            // url de la page que je récupérer et inclure dans le code
            url: "/search2",
            // je récupère la valeur de mon input avec son name 
            data: { search: $(this).val() }
        })
        .done(function( dataSearch ) {
            //je récupère le code et les données dans l'url renseigné au dessus et l'inclure dans la balise contenant l'id searchDyn
            $('#searchDyn').html(dataSearch);
        });
        
    });
  
    // a chaque fois que je clique en dehors de l'input mes resultat de recherche ne s'affiche plus
    $( "#search" ).focusout(function() {
        $("#focus").css("display", "none") 
    });


}
