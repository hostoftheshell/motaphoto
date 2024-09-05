function setupThumbnailPreview() {
    const links = document.querySelectorAll('a[data-thumbnail]');
    const previewContainer = document.querySelector('.post-thumbnail-preview');
    const preview = previewContainer.querySelector('img');

    previewContainer.style.opacity = '0';
    previewContainer.style.visibility = 'hidden';
    previewContainer.style.transition = 'opacity 0.3s ease';

    links.forEach(link => {
        link.addEventListener('mouseover', function () {
            const thumbnailUrl = this.getAttribute('data-thumbnail');
            preview.src = thumbnailUrl;
            previewContainer.style.opacity = '1';
            previewContainer.style.visibility = 'visible';
        });

        link.addEventListener('mouseout', function () {
            previewContainer.style.opacity = '0';
            previewContainer.style.visibility = 'hidden';
        });
    });
}

document.addEventListener('DOMContentLoaded', setupThumbnailPreview);
