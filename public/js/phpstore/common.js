$.LoadingOverlaySetup({
    background      : "rgba(0, 0, 0, 0.5)",
    // image           : "img/custom.svg",
    imageAnimation  : "1.5s fadein",
    imageColor      : "#ffcc00"
});

function loadingOverlayStart() {
    $.LoadingOverlay('show', {
        image       : "",
        fontawesome : "fas fa-circle-notch fa-spin"
    }, 5000);
}

function loadingOverlayStop() {
    $.LoadingOverlay('hide');
}

function validateForm(form) {

}