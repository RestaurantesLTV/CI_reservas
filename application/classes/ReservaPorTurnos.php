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
    
    public function __construct($turno, $telefono = null, $email = null, 
            $minuto = null, $fecha = null, $hora = null, 
            $numPersonas = null, $descripcion = null){
        parent::__construct();
        $this->loadTurnosFromDB();
        
        // Comenzamos a asignar valores:
        if(!$this->validarTurno($turno)){
            throw new Exception(__CLASS__."/".__METHOD__.": "."Turno insertado invalido");
        }
        
        //Validacion telefono
        
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
    
    public function getMomento(){
        return $this->getTurno();
    }
    
    private function loadTurnosFromDB() {
        $query = $this->CI->db->query("SELECT * FROM turno");
        foreach ($query->result() as $row) {
            $this->turnos[$row->id - 1] = $row->nombre;
        }
    }
}
