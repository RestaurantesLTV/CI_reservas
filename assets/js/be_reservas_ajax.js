//Environment variables

var gTurno = "";
var gDatos = new Array();


$(document).ready(function(){
    $.get(  "reserva/proximasreservas",
            function(data){
                gDatos = data;
                PrintReservasTable(data, true);
    },"json");
});

function PrintReservasTable(data, debug){
    CleanTable();
    
    if(!debug){
        debug = false;
    }else{
        
    }
    
    for(var i=0; i < data.length; i++){
        for(var colName in data[i]){
                if(!debug){
                    
                }else{
                    console.log(i + ": " + colName + " --> "  +data[i][colName]);
                }
        }
    }
    
}

function CleanTable(){
    pTabla = $(".reserva-backend table tr:not(:first)"); // Seleccionamos toda la tabla excepto su encabezado
    $(pTabla).html("");
}
