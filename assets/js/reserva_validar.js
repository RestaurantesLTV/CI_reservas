$(document).ready(function() {    
    $(function() {
        $('#frm').submit(function(e) {

            $.post($('#frm').attr('action'),
                    $('#frm').serialize(),
                    function(texto) {
                        HandleResponse(texto);
                        
                    }, 'html');

            return false;
        });
    });

    function HandleResponse(response) {
        alertify.set({delay: 30000});
        console.log("Respuesta: " + response);
        
        var patt = /&eacute;xito|exito/i;
        
        if(patt.test(response)){ // Exito
            alertify.success("<center>" + response + "</center>");
        }else{ // Algo ha ido mal
            alertify.error("<center>" + response + "</center>");
        }
    }
});



