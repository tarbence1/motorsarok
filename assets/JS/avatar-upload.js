$(document).ready(function() {
    $(document).on('change', '#file', function() {
        var property = document.getElementById('file').files[0];
        var image_name = property.name;
        var image_extension = image_name.split('.').pop().toLowerCase();
        if (jQuery.inArray(image_extension, ['gif', 'png', 'jpg', 'jpeg']) == -1) {
            alert('Csak kép feltöltése megengedett!');
        }
        var image_size = property.size;
        if (image_size > 2000000) {
            alert('A kép mérete maximum 2Mb lehet!');
        } else {
            var form_data = new FormData();
            form_data.append('file', property);
            $.ajax({
                url: 'avatar-upload.php',
                method: 'POST',
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    $('#avatar-image').attr('src', data);
                }
            })
        }
    });
});