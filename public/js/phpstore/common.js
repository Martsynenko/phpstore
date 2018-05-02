$.LoadingOverlaySetup({
    background      : "rgba(0, 0, 0, 0.5)",
    // image           : "img/custom.svg",
    imageAnimation  : "1.5s fadein",
    imageColor      : "#ffcc00"
});

$(document)
    .ajaxStart(function () {
        $.LoadingOverlay('show', {
            image       : "",
            fontawesome : "fas fa-circle-notch fa-spin"
        }, 60000);
    })
    .ajaxStop(function () {
        $.LoadingOverlay('hide');
    });