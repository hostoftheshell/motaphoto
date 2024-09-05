jQuery(document).ready(function($) {
    // Fonction pour déclencher la requête AJAX pour les filtres
    function triggerAjaxRequest() {
        $.ajax({
            url: $('#filter-form').attr('action'), // Obtenir l'URL d'action du formulaire
            type: 'POST', // Utiliser la méthode POST
            data: $('#filter-form').serialize(), // Sérialiser les données du formulaire
            success: function(response) {
                if (response.success) {
                    $('#photo-catalog').html(response.data.posts_html); // Afficher les résultats dans #photo-catalog

                    // Mettre à jour ou retirer le bouton "Charger plus"
                    if (response.data.load_more_button) {
                        $('#load-more-container').html(response.data.load_more_button); // Mettre à jour le conteneur du bouton
                    } else {
                        $('#load-more-container').empty(); // Vider le conteneur s'il n'y a plus de bouton
                    }
                } 
            },
            error: function(xhr, status, error) {
                console.log('Erreur AJAX:', error); // Gérer les erreurs AJAX
            }
        });
    }

    // Fonction pour charger plus de posts lorsque le bouton "Charger plus" est cliqué
    function loadMorePosts(page) {
        var data = $('#filter-form').serialize() + '&paged=' + page;

        $.ajax({
            url: $('#filter-form').attr('action'), // Obtenir l'URL d'action du formulaire
            type: 'POST', // Utiliser la méthode POST
            data: data, // Envoyer les données sérialisées du formulaire avec le numéro de page actuel
            success: function(response) {
                if (response.success) {
                    $('#photo-catalog').append(response.data.posts_html); // Ajouter les nouveaux posts

                    // Mettre à jour ou retirer le bouton "Charger plus"
                    if (response.data.load_more_button) {
                        $('#load-more-container').html(response.data.load_more_button); // Mettre à jour le conteneur du bouton
                    } else {
                        $('#load-more-container').empty(); // Vider le conteneur s'il n'y a plus de bouton
                    }
                } else {
                    console.log('Erreur:', response.data); // Gérer les erreurs
                }
            },
            error: function(xhr, status, error) {
                console.log('Erreur AJAX:', error); // Gérer les erreurs AJAX
            }
        });
    }

    // Initialiser la fonctionnalité du bouton "Charger plus"
    function initializeLoadMore() {
        $(document).on('click', '#load-more', function() {
            var page = $(this).data('page'); // Obtenir le numéro de la page actuelle
            loadMorePosts(page); // Charger plus de posts
        });
    }

    // Chargement initial des posts lorsque la page se charge
    triggerAjaxRequest();

    // Déclencher la requête AJAX lorsque une option de filtrage change
    $('#filter-form select').on('change', function() {
        triggerAjaxRequest(); // Déclencher la requête AJAX lors du changement
    });

    // Initialiser lors de la préparation du document
    initializeLoadMore();
});
