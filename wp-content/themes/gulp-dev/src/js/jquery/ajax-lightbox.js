document.addEventListener('DOMContentLoaded', () => {

    function handleLightboxClick(e) {
        e.preventDefault();

        const data = new FormData(e.currentTarget);
        data.append('action', 'filter_results');

        fetch(ajax_filters_params.ajaxurl, {
            method: 'POST',
            body: data,
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const lightboxImages = data.data.lightbox_images;
                    Lightbox.loadImages(lightboxImages);
                } else {
                    console.error('Erreur dans les données de réponse:', data.data);
                }
            })
            .catch(error => {
                console.error('Échec de la requête AJAX:', error);
            });
    }

    function attachListeners() {
        const form = document.getElementById('filter-form');
        if (form && !form.hasAttribute('data-listener-attached')) {
            form.addEventListener('submit', handleLightboxClick);
            form.setAttribute('data-listener-attached', 'true');
        }

        const lightboxTrigger = document.getElementById('open-lightbox');
        if (lightboxTrigger && !lightboxTrigger.hasAttribute('data-listener-attached')) {
            lightboxTrigger.addEventListener('click', handleLightboxClick);
            lightboxTrigger.setAttribute('data-listener-attached', 'true');
        }
    }

    attachListeners();

    const observer = new MutationObserver(() => {
        attachListeners();
    });

    observer.observe(document.body, { childList: true, subtree: true });
});

class Lightbox {
    constructor(url, images, currentPostId, postTitle, category, reference) {
        this.images = images;
        this.currentPostId = currentPostId;
        this.postTitle = postTitle;
        this.category = category;
        this.reference = reference;
        this.currentIndex = this.images.findIndex(image => image.lightbox === url);
        this.createLightbox(url);
        this.bindCloseButton();
        this.bindEscapeKey();
        this.bindNavigationButtons();
    }

    static init() {
        this.attachEventListeners();

        const observer = new MutationObserver(mutations => {
            mutations.forEach(mutation => {
                if (mutation.addedNodes.length > 0) {
                    this.attachEventListeners();
                }
            });
        });

        observer.observe(document.body, { childList: true, subtree: true });
    }

    static attachEventListeners() {
        const lightboxTriggers = document.querySelectorAll('.lightbox-trigger');

        lightboxTriggers.forEach(lightboxTrigger => {
            if (!lightboxTrigger.hasAttribute('data-listener-attached')) {
                lightboxTrigger.addEventListener('click', e => {
                    e.preventDefault();
                    const imageUrl = e.currentTarget.getAttribute('href');
                    const images = JSON.parse(e.currentTarget.getAttribute('data-images'));
                    const postId = e.currentTarget.getAttribute('data-post-id');
                    const postTitle = e.currentTarget.getAttribute('data-post-title');
                    const category = e.currentTarget.getAttribute('data-category');
                    const reference = e.currentTarget.getAttribute('data-reference');
                    if (postId) {
                        new Lightbox(imageUrl, images, postId, postTitle, category, reference);
                    } else {
                        console.error('L\'ID du post est manquant dans le déclencheur.');
                    }
                });

                lightboxTrigger.setAttribute('data-listener-attached', 'true');
            }
        });
    }

    createLightbox(url) {
        const imageData = this.images.find(image => image.lightbox === url);
        
        if (imageData) {
            const reference = this.reference;
            const category = this.category;
            const element = this.buildMarkup(url, reference, category);
            document.body.appendChild(element);
            this.updateImage(url);
        } else {
            console.error('Aucune donnée d\'image trouvée pour l\'URL:', url);
        }
    }

    buildMarkup(url, reference, category) {
        const buildLightbox = document.createElement('div');
        buildLightbox.classList.add('lightbox');
        buildLightbox.innerHTML = `
            <button class="lightbox__close">Fermer</button>
            <button class="lightbox__next">Suivante</button>
            <button class="lightbox__prev">Précédente</button>
            <div class="lightbox__container">
                <img src="${url}" alt="">
                <div class="lightbox__info">
                    <p class="lightbox__reference">${reference || ''}</p>
                    <p class="lightbox__category">${category || ''}</p>
                </div>
            </div>
        `;
        return buildLightbox;
    }

    bindCloseButton() {
        const closeButton = document.querySelector('.lightbox__close');
        if (closeButton) {
            closeButton.addEventListener('click', () => this.close());
        }
    }

    bindEscapeKey() {
        document.addEventListener('keydown', this.handleKeydown.bind(this));
    }

    handleKeydown(event) {
        if (event.key === 'Escape' || event.keyCode === 27) {
            this.close();
        }
    }

    bindNavigationButtons() {
        const nextButton = document.querySelector('.lightbox__next');
        const prevButton = document.querySelector('.lightbox__prev');

        if (nextButton) {
            nextButton.addEventListener('click', () => {
                this.loadNextPost();
            });
        }

        if (prevButton) {
            prevButton.addEventListener('click', () => {
                this.loadPrevPost();
            });
        }
    }

    updateImage(url) {
        const imgElement = document.querySelector('.lightbox__container img');
        const lightboxContainer = document.querySelector('.lightbox__container');
    
        if (imgElement && lightboxContainer) {
            lightboxContainer.classList.remove('portrait');
    
            imgElement.src = url;
    
            const checkOrientation = () => {
                const isPortrait = imgElement.naturalHeight > imgElement.naturalWidth;
                if (isPortrait) {
                    lightboxContainer.classList.add('portrait');
                }
            };
    
            if (imgElement.complete) {
                checkOrientation();
            } else {
                imgElement.onload = checkOrientation;
            }
        } else {
            console.error('Élément image ou conteneur lightbox non trouvé.');
        }
    }
    
    updateReference(reference) {
        const referenceElement = document.querySelector('.lightbox__reference');
        if (referenceElement) {
            referenceElement.textContent = reference || 'Aucune référence';
        } else {
            console.error('Élément référence non trouvé dans le conteneur lightbox.');
        }
    }

    updateCategory(category) {
        const categoryElement = document.querySelector('.lightbox__category');
        if (categoryElement) {
            categoryElement.textContent = category;
        } else {
            console.error('Élément catégorie non trouvé dans le conteneur lightbox.');
        }
    }

    loadNextPost() {
        const data = new FormData();
        data.append('action', 'get_next_post');
        data.append('filter_nonce', ajax_filters_params.nonce);
        data.append('current_post_id', this.currentPostId);
    
        fetch(ajax_filters_params.ajaxurl, {
            method: 'POST',
            body: data,
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.images = data.data.lightbox_images;
                this.currentPostId = data.data.post_id;
                this.reference = this.images[0].reference;
                this.category = data.data.category;
    
                if (this.images.length > 0) {
                    const newImage = this.images[0].lightbox;
                    this.updateImage(newImage);
                    this.updateReference(this.reference);
                    this.updateCategory(this.category);
                } else {
                    console.error('Aucune image disponible dans le post suivant.');
                }
            } else {
                console.error('Erreur lors du chargement du post suivant:', data.data);
            }
        })
        .catch(error => {
            console.error('Échec du chargement du post suivant:', error);
        });
    }
    
    loadPrevPost() {
        // Check if ajax_filters_params is defined
        if (!ajax_filters_params || !ajax_filters_params.ajaxurl || !ajax_filters_params.nonce) {
            console.error('Configuration AJAX manquante.');
            return;
        }
    
        // Prepare the data for the AJAX request
        const data = new FormData();
        data.append('action', 'get_prev_post');
        data.append('filter_nonce', ajax_filters_params.nonce);
        data.append('current_post_id', this.currentPostId);
    
        // Make the AJAX request
        fetch(ajax_filters_params.ajaxurl, {
            method: 'POST',
            body: data,
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok.');
            }
            return response.json();
        })
        .then(data => {
            // Check if the request was successful
            if (data.success) {
                if (data.data.has_prev_post) {
                    this.images = data.data.lightbox_images || [];
                    this.currentPostId = data.data.post_id;
                    this.reference = this.images.length > 0 ? this.images[0].reference : null;
                    this.category = data.data.category || '';
    
                    // Update UI if images are available
                    if (this.images.length > 0) {
                        const newImage = this.images[0].lightbox;
                        this.updateImage(newImage);
                        this.updateReference(this.reference);
                        this.updateCategory(this.category);
                    } else {
                        console.error('Aucune image disponible dans le post précédent.');
                    }
                } else {
                    console.error('Aucun post précédent disponible.');
                }
            } else {
                console.error('Erreur lors du chargement du post précédent:', data.data.message || 'Erreur inconnue');
            }
        })
        .catch(error => {
            console.error('Échec du chargement du post précédent:', error);
        });
    }
    

    close() {
        const lightboxElement = document.querySelector('.lightbox');
        if (lightboxElement) {
            document.body.removeChild(lightboxElement);
        }
    }
}

Lightbox.init();
