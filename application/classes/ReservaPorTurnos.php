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
    private $turno;
    private $turnos = array();
    
    public function __construct(){
        parent::__construct();
        $this->loadTurnosFromDB();
    }
    
    public function loadTurnosFromDB(){
        $query = $this->CI->db->query("SELECT * FROM turno");
        foreach($query->result() as $row){
            $this->turnos[$row->id] = $row->nombre;
        }
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
        
        if(!turnoEnRangoValido($turno)){
            return false;
        }
        
        return true;
    }
    
    /**
     * 
     * @param int $turno
     */
    private function turnoEnRangoValido($turno){ 
        foreach($this->turnos as $index => $turnoNombre){
            if($index == $turno){
                return true;
            }
        }
        
        return false;
    }
}
