<?php

/**
 * Este objeto representa una reserva.
 *
 * @author unscathed18
 */
abstract class Reserva {
    /**
     * Esta variable es una descripcion de valoraciones y comentarios recogidos por 
     * ejemplo, un formulario web.
     * @var String
     */
    private $descripcion = null;
    private $numPersonas = 0;
    protected $CI = null;
    
    public function __construct(){
        $this->CI =& get_instance();
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
}
