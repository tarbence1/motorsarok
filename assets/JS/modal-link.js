$(document).on('show.bs.modal', ".modal", function(event) {
    window.location.hash = event.currentTarget.id;
});