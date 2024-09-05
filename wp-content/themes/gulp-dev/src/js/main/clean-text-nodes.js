function cleanUpTextNodes() {
    const parentElements = document.querySelectorAll('.contact-overlay__header-wrapper');

    parentElements.forEach((parentElement) => {
        parentElement.childNodes.forEach((node) => {
            if (node.nodeType === Node.TEXT_NODE && !node.nodeValue.trim()) {
                node.remove();
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', cleanUpTextNodes);
