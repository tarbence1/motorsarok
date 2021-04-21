$('#file').bind('change', function() {
    if (this.files[0].size >= 2100000) {
        alert("A kép mérete nem lehet nagyobb 2Mb-nál. Kérjük válasszon másik képet!");
        $(this).val('');
        $('#file-data').empty();
    }

});