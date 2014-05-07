<?php

/**
 * Description of ReservasManager
 *
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
    private $config = null;
    
    public function __construct() {
        $this->CI =& get_instance();
        $this->fechaActual = new DateTime();
        $this->loadConfig();
        $this->load->library('customautoloader');
    }
    
    
    /**
     * @todo Implementar un sistema para calcular dias festivos
     * @return boolean
     */
    private function esFestivo(){
        return false; 
    }
    
    private function loadConfig(){
        if(!file_exists($this->config_filename)){
            //throw new Exception('Clase "'.__CLASS__.'" --> Archivo de configuracion "'.$this->base_dir.'/'.$this->config_filename.'" no existe. ');
            return;
        }
        $json = file_get_contents($this->config_filename);
        $this->config = json_decode($json);
    }
    
    /**
     * Funcion creada para debugear la clase.
     * @todo Implementar la funcionalidad.
     */
    private function dumpConfigFileOptions(){
        
    }
    
    public function __toString() {
        return "[Objeto] Sistema de reservas";
    }
}
