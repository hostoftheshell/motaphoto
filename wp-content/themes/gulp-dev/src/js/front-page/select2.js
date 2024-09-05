jQuery(document).ready(function($) {
    console.log("Initializing Select2...");
    const $selectElement = $(".js-select2");

    if ($selectElement.length) {
        console.log("Select2 element found");
        $selectElement.select2({
            tags: true,
            width: '16.25rem',
        });
    } else {
        console.error("Select2 element not found");
    }
});