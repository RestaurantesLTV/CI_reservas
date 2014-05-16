<?php

//Load dependencies
get_instance()->customautoloader->load("Reserva");

/**
 * Description of ReservaPorTurnos
 *
 * @author unscathed18
 */
class ReservaPorTurnos extends Reserva{
    
    /**
     * Los numeros de turnos deben estar almacenados en algun sitio. Esto cambia segun
     * el restaurante y el numero de turnos que tiene.
     * @var int
     */
    private $turno = -1;
    private $turnos = array();
    
    public function __construct($turno, $telefono, $email, $fecha_hora, 
            $numPersonas, $observaciones){
        parent::__construct();
        
        
        /* Las variables estan limpias y con su formato gracias al trabajo
         * ejercicido por el controlador de tratar de limpiarlas
         * y establecer el formato adecuado.
         */
        $this->turno = $turno;
        $this->telefono = $telefono;
        $this->email = $email;
        $this->fecha_hora = $fecha_hora;
        $this->numPersonas = $numPersonas;
        $this->observaciones = $observaciones;
    }
    
    public function dumpObject(){
        $value  = "<p>Turno: ".$this->turno."</p>";
        $value .= "<p>Telefono: ".$this->telefono."</p>";
        $value .= "<p>Email: ".$this->email."</p>";
        $value .= "<p>Fecha: ".$this->fecha->format("Y-m-d H:i:s")."</p>";
        $value .= "<p>Numero de personas: ".$this->numPersonas."</p>";
        $value .= "<p>Observaciones: ".$this->observaciones."</p>";
        return $value;
    }
    
    public function getTurnos(){
        return $this->turnos;
    }
    
    
    /**
     * @todo Hacer checkeo de comprobaciones de incoherencia.
     * @param type $turno
     */
    public function setTurno($turno){
        $this->turno = $turno;
    }
    
    public function getTurno(){
        return $this->turno;
    }
    
    
    /**
     * Nos indica si el turno existe en el rango de turnos definidos en el restaurante.
     * @param int $turno
     * @return boolean
     * @throws Exception
     */
    private function validarTurno($turno){
        if(!is_int($turno)){
            throw new Exception("Parametro del metodo '".__METHOD__."' de la clase '".__CLASS__."' tiene"
                    . " un valor que no es un Integer. Valor introducido: ".$turno);
        }
        
        if(!$this->turnoEnRangoValido($turno)){
            return false;
        }
        
        return true;
    }
    
    /**
     * Funciona testeada.
     * @param int $turno
     */
    private function turnoEnRangoValido($turno){ 
        foreach($this->turnos as $index => $turnoNombre){
            if(($index+1) == $turno){
                return true;
            }
        }
        
        return false;
    }
}
