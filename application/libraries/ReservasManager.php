<?php

/**
 * Administra el sistema de reservas. Tiene una agregacion respecto a un objecto de tipo
 * 'Reserva'.
 * @author unscathed18
 */
class ReservasManager {
    
    /**
     * Directorio base donde esta ubicado el archivo de configuracion.
     * @var String
     */
    private $base_dir = "";
    
    /**
     * Nombre del archivo de configuracion de las reservas
     * @var String 
     */
    private $config_filename = "reserva_config.json";
    
    /**
     * Puntero a instancia de Code Igniter
     * @var CI_core
     */
    private $CI = null;
    
    /**
     * Mantiene una referencia a un objeto de tipo "Reserva"
     * @var Reserva
     */
    private $reserva = null;
    
    /**
     * Fecha actual
     * @var DateTime
     */
    private $fechaActual;
    
    /**
     * Variable que almacena todas las opciones de personalizacion del Sistema de reservas.
     * Afecta al funcionamiento de este. Por ejemplo, puede escoger instanciar un objeto hijo u otro de
     * la clase 'Reserva' dependiendo de los parametros especificados en el archivo de configuracion.
     * @var String[]
     */
    protected $config = null;
    private $tipo = "";
    
    public function __construct() {
        $this->CI =& get_instance();
        $this->fechaActual = new DateTime();
        $this->loadConfig();
        if($this->checkConfigParams()){
            throw new Exception("El archivo de configuracion '".$this->config_filename."' tiene valores erroneos.");
        }
        $this->CI->load->library('customautoloader');
        
        if($this->tipo == "turnos"){
            $this->reserva = new ReservaPorTurnos();
        }else{
            $this->reserva = new ReservaPorTiempo();
        }
        
        if(!$this->reserva){
            throw new Exception('Clase "'.__CLASS__.'" no ha podido crear con exito el objeto "Reserva"');
        }
    }
    
    
    /**
     * @todo Implementar un sistema para calcular dias festivos
     * @return boolean
     */
    private function esFestivo(){
        
        return false; 
    }
    
    /**
     * NO UTILIZAR
     * @todo Buscar utilidad a esta funcion. Falta por implementar
     * @return boolean
     */
    private function esLaborable(){
        $dia_semana = $this->reserva->getDiaSemana();
        if($this->config['horario'][$dia_semana] == null){
            $festivo = false;
              // Buscar en Base de datos si el dia en esta fecha es festivo  
              // ...
              // Acabamos de buscar en BD
            if($festivo){
                return true;
            }
            return false;
        }
        return true;
    }
    
    
    private function loadConfig(){
        if(!file_exists(__DIR__."/".$this->config_filename)){
            throw new Exception('Clase "'.__CLASS__.'" --> Archivo de configuracion "'.$this->base_dir.$this->config_filename.'" no existe. ');
            return;
        }
        
        $json = file_get_contents(__DIR__."/".$this->config_filename);
        $this->config = json_decode($json,true);
    }
    
    /**
     * Verifica que los parametros insertados en el fichero de configuracion
     * sean correctos. En caso contrario, nos salta un error
     * @return boolean Description
     */
    private function checkConfigParams(){
        // PARAMETRO 'Sistema'. Opciones posibles: 'turnos' y 'tiempo'
        $this->config['sistema'] = strtolower($this->config['sistema']);
        $bSistema = false;
        
        if($this->config['sistema'] == "turnos"){
            $bSistema = true;
        }
        
        if($this->config['sistema'] == "tiempo"){
            $bSistema = true;
        }
        
        if(!$bSistema){
            return false;
        }
        
        /* Verificamos que existan los turnos */
        
        
        
        
        // PARAMETRO 'AFORO'. Logica: Debe ser un numero mas grande que 1.
        
        if(!is_int($this->config['aforo'])) return false;
        
        if($this->config['aforo'] <=1){
            return false;
        }
        
        return true;
    }
    
    /**
     * Funcion creada para debugear la clase.
     * @todo Implementar la funcionalidad.
     */
    private function dumpConfigFileOptions(){
        
    }
    
    private function loadTurnosFromDB(){
        
    }
    
    /**
     * Devuelve 'true' si esta disponible acorde a ese 'momento' en concreto
     * no es festivo, y quedan mesas disponibles.
     * @return boolean
     */
    public function disponible(){
        
        return true;
    }
    
    /**
     * Asigna las mesas 
     * @param int $numPersonas
     * @return int[] Devuelve los numeros de las mesas asignadas
     */
    private function asignarMesas($numPersonas){
        //return 
    }
    
    
    public function getReserva(){
        return $this->reserva;
    }
    
    public function nuevaReserva(){
        if($this->tipo == turnos){
            $this->reserva = new ReservaPorTurnos;
        }else{
            $this->reserva = new ReservaPorTiempo;
        }
    }
    
    public function __toString() {
        return "[Objeto] Sistema de reservas";
    }
}
