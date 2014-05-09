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
    private $descripcion = null;
    
    /**
     *
     * @var int Numero de personas
     */
    private $numPersonas = 0;
    
    /**
     * Fecha
     * @var DateTime
     */
    private $fecha;
    /**
     * El formato de la hora es 24 horas. Rango [0-23]
     * @var int
     */
    private $hora;
    
    /**
     * El formato del minuto es 60. Rango [0-59]
     * @var int
     */
    private $minuto;
    
    /**
     * Puntero al nucleo del sistema de Code Igniter
     * @var CI_ref
     */
    protected $CI = null;
    
    
    public function __construct(){
        $this->CI =& get_instance();
    }
    
    public function setMinuto($min){
        if(!is_int($min)){
            throw new Exception("El metodo '".__METHOD__."de la clase'".__CLASS__."' da error: El minuto no es un integer."
                    ."Valor introducido --> ".$min);
        }
        
        if($min < 0 || $min >= 60){
            throw new Exception("El metodo '".__METHOD__."de la clase'".__CLASS__."' da error: El minuto no es valido."
                    ."Valor introducido --> ".$min);
        }
        $this->minuto = $min;
    }
    
    /**
     * 
     * @return int
     */
    public function getMinuto(){
        return $this->minuto;
    }
    
    public function setHora($hora){
        if(!is_int($hora)){
            throw new Exception("El metodo '".__METHOD__."de la clase'".__CLASS__."' da error: La hora no es un integer."
                    ."Valor introducido --> ".$hora);
        }
        
        if($hora < 0 || $hora >= 24){
            throw new Exception("El metodo '".__METHOD__."de la clase'".__CLASS__."' da error: La hora no es valida."
                    ."Valor introducido --> ".$hora);
        }
        $this->hora = $hora;
    }
    
    /**
     * 
     * @return int
     */
    public function getHora(){
        return $this->hora;
    }
    
    /**
     * Hacer Override en clase hija
     * @param Object $momento Description
     */
    public function setMomento($momento){ }
    
    /**
     * Hacer Override en clase hija
     */
    public function getMomento(){ }
    
    /**
     * 
     * @param type $numPersonas
     */
    public function setNumPersonas($numPersonas){
        if($numPersonas <= 0){
            throw new Exception("Numero de personas incorrecta --> ".$numPersonas);
        }
        $this->numPersonas = $numPersonas;
    }
    
    public function setDescripcion($descripcion){
        $this->descripcion = $descripcion;
    }
    
    public function getNumPersonas(){
        return $this->numPersonas();
    }
    
    public function getDescripcion(){
        return $this->descripcion;
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
}
