   //From card to garage
   $(document).ready(function() {
       //form when submit
       $("form[id*=upload-to-garage]").submit(function(e) {
           e.preventDefault();
           $.ajax({
               url: "upload-to-garage.php",
               method: "post",
               data: $(this).serialize() + "&garage=somevalue",
               dataType: "text",
               success: function(response) {
                   var errorMessages = JSON.parse(response);
                   if (errorMessages.ok) {
                       alertify.success(errorMessages.errorMsg);
                   } else {
                       alertify.error(errorMessages.errorMsg);
                   }
               }
           });
       });
   });

   //From modal to garage
   $(document).ready(function() {
       //form when submit
       $("form[id*=modal-to-garage]").submit(function(e) {
           e.preventDefault();
           $.ajax({
               url: "upload-to-garage.php",
               method: "post",
               data: $(this).serialize() + "&garage=somevalue",
               dataType: "text",
               success: function(response) {
                   var errorMessages = JSON.parse(response);
                   if (errorMessages.ok) {
                       alertify.success(errorMessages.errorMsg);
                   } else {
                       alertify.error(errorMessages.errorMsg);
                   }
               }
           });
       });
   });