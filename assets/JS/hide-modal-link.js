$(document).on('hide.bs.modal', ".modal", function(event) {
    history.replaceState("", document.title, window.location.pathname +
        window.location.search);
});