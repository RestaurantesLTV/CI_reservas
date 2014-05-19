$(document).ready(function() {
    alertify.set({delay: 30000});
    $(function() {
        
        $('#frm').submit(function(e) {
            alertify.success("<center>" + "Peticion enviada, esperese un momento. " + "</center>");
            $.post($('#frm').attr('action'),
                    $('#frm').serialize(),
                    function(texto) {
                        HandleResponse(texto);
                        
                    }, 'html');

            return false;
        });
        console.log("Enviado");
    });

    function HandleResponse(response) {
        console.log("Respuesta: " + response);
        
        var patt = /&eacute;xito|exito/i;
        
        if(patt.test(response)){ // Exito
            alertify.success("<center>" + response + "</center>");
        }else{ // Algo ha ido mal
            alertify.error("<center>" + response + "</center>");
        }
    }
});



