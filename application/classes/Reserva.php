<?php

/**
 * Este objeto representa una reserva.
 *
 * @author unscathed18
 */
abstract class Reserva {
    
    // Dias de la semana y su valor numerico en PHP.
    const LUNES = 1;
    const MARTES = 2;
    const MIERCOLES = 3;
    const JUEVES = 4;
    const VIERNES = 5;
    const SABADO = 6;
    const DOMINGO = 0;
    
    /**
     * Esta variable es una descripcion de valoraciones y comentarios recogidos por 
     * ejemplo, un formulario web.
     * @var String
     */
    protected $observaciones = null;
    
    protected $cod_reserva;
    
    protected $id = -1;
    
    /**
     *
     * @var int Numero de personas
     */
    protected $numPersonas = 0;
    
    /**
     * Fecha
     * @var DateTime
     */
    protected $fecha_hora;
    
    protected $email;
    
    protected $telefono;
    
    /**
     * Puntero al nucleo del sistema de Code Igniter
     * @var CI_ref
     */
    protected $CI = null;
    
    
    public function __construct(){
        $this->CI =& get_instance();
    }
    
    public function setID($id){
        $this->id = $id;
    }
    
    public function getID(){
        return $this->id;
    }
    
    public function setCodigo($cod){
        $this->cod_reserva = $cod;
    }
    
    public function getCodigo(){
        return $this->cod_reserva;
        
    }
    
    /**
     * Nos devuelve la fecha en el formato "Year-Month-Day"
     * @return String
     */
    public function getFecha(){
        return $this->fecha_hora->format("Y-m-d");
    }

    
    /**
     * Devuelve en format de hora y minutos de MySQL
     * @return String
     */
    public function getTiempo(){
        return $this->fecha_hora->format("H:i:00");
    }
    
    public function getNumPersonas(){
        return $this->numPersonas;
    }
    
    public function getObservaciones(){
        return $this->observaciones;
    }
    
    
    /**
     * Devuelve el dia de la semana. El primer dia es el domingo. Y es el 0.
     * El ultimo dia es 6, y es el sabado.
     * @return int
     */
    public function getNumDiaSemana(){
        return $this->fecha->format("w");
    }
    
    /**
     * Convierte el numero obtenido representativo del dia de la semana de la 
     * funcion 'getNumDiaSemana' a String y lo devuelve.
     * @return String
     */
    public function getDiaSemana(){
        $num_dia = $this->getNumDiaSemana();
        $string_dia = "";
        switch($num_dia){
            case self::LUNES:
                $string_dia = "lunes";
            break;
            case self::MARTES:
                $string_dia = "martes";
            break;
            case self::MIERCOLES:
                $string_dia = "miercoles";
            break;
            case self::JUEVES:
                $string_dia = "jueves";
            break;
            case self::VIERNES:
                $string_dia = "viernes";
            break;
            case self::SABADO:
                $string_dia = "sabado";
            break;
            case self::DOMINGO:
                $string_dia = "domingo";
            break;
            default:
                throw new Exception("Indice del dia de la semana invalido");
                break;
        }
        return $string_dia;
    }
    
    public function getTelefono(){
        return $this->telefono;
    }
    
    public function getEmail(){
        return $this->email;
    }

}
