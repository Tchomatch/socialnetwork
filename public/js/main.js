$('#registration_form_image').on('change', function () {
    // Je récupère le nom du fichier
    var fileName = $(this).val();
    // Et je l'affiche dans l'input 
    $(this).next('.custom-file-label').html(fileName);
})