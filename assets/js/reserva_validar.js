$(document).ready(function() {
    $("#fecha").on("propertychange change keyup paste input", function(){
        console.log("ha cambiado");
        if($("#fecha").text() != ""){
            $("#submit_reservar").removeAttr("disabled");
        }
    });
    
    
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
        console.log("Respuesta: " + response);
        switch (response) {
            case "exito":
                alertify.set({delay: 10000});
                alertify.success("<center>Enviado con &eacute;xito!<br/>Verifique su email para confirmar la reserva.</center>");
                break;
            default:
                alertify.set({delay: 10000});
                alertify.error(response);
        }
    }
});



