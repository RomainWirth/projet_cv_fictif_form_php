$(() => {
    $('#contact-form').submit((e) => { // élément déclencheur : submit de 'contact-form'. à la soumission du formulaire de contact, on exécute le code suivant
        e.preventDefault(); // permet d'enlever le comportement par défaut à la soumission du formulaire
        $('.comments').empty(); // ici, on supprime les valeurs et commentaires d'erreur du formulaire (sous les champs du formulaire)
        let postdata = $('#contact-form').serialize(); // on crée la variable postdata qui permet de récupérer les infos du formulaire pour les 'sérialiser'
        // on débute AJAX
        $.ajax({ // objet json
            type: 'POST',
            url: 'php/contact.php',
            data: postdata,
            dataType: 'json',
            success: (result) => {
                if (result.isSuccess) {
                    $('#contact-form').append("<p id='thank-you'>Votre message a bien été envoyé. Merci et à bientôt</p>");
                    $('#contact-form')[0].reset();
                } else {
                    $('#firstname + .comments').html(result.firstnameError);
                    $('#name + .comments').html(result.nameError);
                    $('#email + .comments').html(result.emailError);
                    $('#phone + .comments').html(result.phoneError);
                    $('#message + .comments').html(result.messageError);
                }
            }
        })
    });
})