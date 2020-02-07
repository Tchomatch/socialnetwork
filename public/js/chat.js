function charger(){

    setTimeout( function(){
        // on lance une requête AJAX
        $.ajax({
            url : "/profile/5/chat",
            type : GET,
            success : function(html){
                
            }
        });

        charger(); // on relance la fonction

    }, 5000); // on exécute le chargement toutes les 5 secondes

}

charger();
