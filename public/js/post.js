// initialisation d'une variable counter qui sera placée en get de la page load et qui sera donc la valeur de l'offset de la requête en base.
var counter = 6;

// création de la fonction scroll


//  Mise en place de la condition d'utilisation de la fonction  :
//  La fonction jQuery scrollTop renvois la position actuelle de la scrollbar
//  On compare cette position à la position de bas de page hauteur de la page - hauteur de la fenêtre

//  La scrollbar reste fixe sur le coté de la page et a donc pour hauteur celle de la fenêtre, en la comparant à la hauteur de la page - la hauteur de la fenêtre, on obtient donc la position minimale de la scroll bar. (cette explication est beaucoup trop longue mais c'est toujours mieux)

//  SI les deux valeurs sont égales (et donc que l'on se trouve au plus bas de la page), on charge une requête AJAX.
//  La requête va charger la page load/{id} ou {id} sera égal à la valeur de counter.
//  Au succès de cette requête :
//  |-> on ajoute 6 à counter pour que l'offset augmente en conséquence et que la requête suivante en   bdd corresponde aux éléments suivants enregistrés en base.
//  |-> on ajoute à l'élément #scrollPost les données récupérées.

$(window).scroll(function() {
    if($(window).scrollTop() == $(document).height() - $(window).height()) {
        
        $.ajax({
            method: "GET",
            async: true,
            url: "/load/"+counter,
            success: function(data){
                counter += 6;
                $("#scrollPost").append(data);
                console.log(counter);
                console.log(data);
            }            
        })        
    }
});


// petite animation de chargement sur la requête ajax
$body = $("body");


$(document).on({
    ajaxStart: function() { $body.addClass("loading");    },
    ajaxStop: function() { $body.removeClass("loading"); }    
}); 

function myDisplay(){
    // Je séléctionne la classe toggle-post 
    $("#toggle-post").toggle(
        function(){
            $(this).addClass("toggle-post"); // ajout display none
        }, function(){
            $(this).removeClass("toggle-post"); // remove display none
        }
    );

}