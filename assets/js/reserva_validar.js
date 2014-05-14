$(document).ready(function() {
    $(function() {
        $('#frm').submit(function(e) {

            $.post($('#frm').attr('action'),
                    $('#frm').serialize(),
                    function(texto) {
                        //var data = JSON.parse(json);

                        //$("#ajax_callback").text();
                        HandleResponse(texto);

                    }, 'html');

            return false;
        });
    });

    function HandleResponse(response) {
        switch (response) {
            case "exito":
                alertify.set({delay: 10000});
                alertify.success("Verifique su email para confirmar la reserva.");
                break;
            default:
                console.log("Respuesta del servidor desconocida.");
                break;
        }
    }
});

