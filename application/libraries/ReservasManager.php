<?php

/**
 * Administra el sistema de reservas. Tiene una agregacion respecto a un objecto de tipo
 * 'Reserva'.
 * @author unscathed18
 */
class ReservasManager {
    
    private $modelInitialized = false;

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
    private $turnos = array();

    public function __construct() {
        $this->CI = & get_instance();
        $this->fechaActual = new DateTime();
        $this->loadConfig();
        if ($this->checkConfigParams()) {
            throw new Exception("El archivo de configuracion '" . $this->config_filename . "' tiene valores erroneos.");
        }
        $this->CI->load->library('customautoloader');

        if ($this->config['sistema'] == "turnos") {
            //$this->reserva = new ReservaPorTurnos();
            $this->loadTurnosFromDB();
        } else {
            //$this->reserva = new ReservaPorTiempo();
        }
    }

    public function disponibilidadAforo() {
        $queryString = "SELECT IFNULL(SUM(num_personas),0) as aforo FROM reserva "
                . "WHERE fecha_reservada = '" . $this->reserva->getFecha() . "' AND id_turno = " . $this->reserva->getTurno();
        $query = $this->CI->db->query($queryString);
        $aforo_restante = $query->row()->aforo;
        $aforo_restante = $this->config['aforo'] - $aforo_restante;
        return $aforo_restante;
    }

    public function getTurnos() {
        return $this->turnos;
    }

    private function loadTurnosFromDB() {
        $query = $this->CI->db->query("SELECT * from turno");
        foreach ($query->result() as $row) {
            $this->turnos[] = $row->nombre;
        }
    }

    /**
     * @todo Implementar un sistema para calcular dias festivos
     * @return boolean
     */
    private function esFestivo() {
        /* if ($this->esLaborable()) {
          $query = $this->CI->db->query("SELECT * FROM festividad WHERE fecha = ".$this->reserva->getFecha());
          foreach ($query->result() as $row) {
          $this->turnos[$row->id - 1] = $row->nombre;
          }
          } */
        return false;
    }

    /**
     * NO UTILIZAR
     * @todo Buscar utilidad a esta funcion. Falta por implementar
     * @return boolean
     */
    private function esLaborable() {
        $dia_semana = $this->reserva->getDiaSemana();
        if ($this->config['horario'][$dia_semana] == null) {
            $festivo = false;
            // Buscar en Base de datos si el dia en esta fecha es festivo  
            // ...
            // Acabamos de buscar en BD
            if ($festivo) {
                return true;
            }
            return false;
        }
        return true;
    }

    private function loadConfig() {
        if (!file_exists(__DIR__ . "/" . $this->config_filename)) {
            throw new Exception('Clase "' . __CLASS__ . '" --> Archivo de configuracion "' . $this->base_dir . $this->config_filename . '" no existe. ');
            return;
        }

        $json = file_get_contents(__DIR__ . "/" . $this->config_filename);
        $this->config = json_decode($json, true);
    }

    /**
     * Verifica que los parametros insertados en el fichero de configuracion
     * sean correctos. En caso contrario, nos salta un error
     * @return boolean Description
     */
    private function checkConfigParams() {
        // PARAMETRO 'Sistema'. Opciones posibles: 'turnos' y 'tiempo'
        $this->config['sistema'] = strtolower($this->config['sistema']);
        $bSistema = false;

        if ($this->config['sistema'] == "turnos") {
            $bSistema = true;
        }

        if ($this->config['sistema'] == "tiempo") {
            $bSistema = true;
        }

        if (!$bSistema) {
            return false;
        }

        /* Verificamos que existan los turnos */




        // PARAMETRO 'AFORO'. Logica: Debe ser un numero mas grande que 1.

        if (!is_int($this->config['aforo']))
            return false;

        if ($this->config['aforo'] <= 1) {
            return false;
        }

        return true;
    }

    /**
     * Funcion creada para debugear la clase.
     * @todo Implementar la funcionalidad.
     */
    public function dumpConfigFileOptions() {
        
    }

    /**
     * Devuelve 'true' si esta disponible acorde a ese 'momento' en concreto
     * no es festivo, y quedan mesas disponibles.
     * @todo Acabar de completar
     * @return boolean
     */
    private function disponible() {
        $errores = "";
        if ($this->esFestivo()) {
            $errores .= "Este dia y turno es festivo.";
        }

        $aforo_disponible = $this->disponibilidadAforo();
        //echo "<p>Disponbilidad aforo: ".$aforo_disponible."</p>";
        $aforo_despues_de_reserva = $aforo_disponible - $this->reserva->getNumPersonas();
        //echo "<p>Aforo despues de reserva: ".$aforo_despues_de_reserva."</p>";

        if ($aforo_despues_de_reserva < 0) { //Quedan espacios disponibles para hacer esta reserva
            $errores .= "Aforo lleno. ";
            if ($aforo_disponible > 0) {
                $errores .= "Tan solo quedan " . $aforo_disponible . " huecos disponibles.";
            }
        }

        return $errores;
    }
    
    public function getNombre(){
        return $this->config['nombre'];
    }
    
    public function getEmail(){
        return $this->config['email'];
    }
    
    public function getEmailUser(){
        return $this->config['email-user'];
    }
    
    public function getEmailPass(){
        return $this->config['email-pass'];
    }

    /**
     * Si la reserva fue bien devuelve true, sino false.
     * @return boolean
     */
    public function reservar() {
        $errores = "";
        $errores .= $this->disponible();
        
        


        $disponible;    

        if ($errores == "") {
            $disponible = true;
        } else {
            $disponible = false;
        }


        if ($disponible) {
            //Reservamos si queda espacio
            $r = $this->reserva;
            $cod_reserva = $this->generarCodigoReserva();
            $this->getReserva()->setCodigo($cod_reserva);
            // Ejemplo de consulta: INSERT INTO reserva VALUES (null, "ASDADS", null, "2014-01-15", "07:13", 2, "192.168", 4, null, "93123229", "unscathed512@hotmail.com", 0)
            
            /* BEG INSERTAR*/
            $data = array(
                'codigo' 		=> $cod_reserva,
                'fecha_reservada'	=> $r->getFecha(),
                'hora_reservada' 	=> $r->getTiempo(),
                'id_turno' 		=> $r->getTurno(),
                'ip'			=> $this->CI->input->ip_address(),
                'observaciones'		=> $r->getObservaciones(),
                'telefono'		=> $r->getTelefono(),
                'email'			=> $r->getEmail(),
                'verificado'		=> 0,
                'num_personas'          => $r->getNumPersonas(),
                'checked_by_admin'      => 0
             );

            $this->CI->db->insert('reserva', $data); 
            $insert_id = $this->CI->db->insert_id();
            $this->CI->db->trans_complete();
            
            $this->reserva->setID($insert_id);
            /* END INSERTAR*/
        }
            return $errores;
    }
    
    

    public function aforoRestante() {
        $query = $this->CI->db->query("SELECT sum(num_personas) as num_personas FROM reserva WHERE = " . $this->reserva->getFecha());
        return $this->config['aforo'] - $query->row()->num_personas;
    }

    protected function generarCodigoReserva() {
        return mt_rand();
    }

    public function getReserva() {
        return $this->reserva;
    }

    public function nuevaReserva($r) {
        $this->reserva = $r;
    }

    /*     * ***************************************************Mantenimiento************************************************** */
    /*     * ***************************************************************************************************************** */

    /**
     * Este metodo tiene una utilidad crucial a la hora de poder verificar que es el cliente
     * que hizo realmente la reserva, y permitirle, por ejemplo modificar los detalles de una
     * reserva ya existente. Para reservarlo, necesitara como minimo proveer de un 
     * tipo de contacto (e.g: Email, telefono) y el codigo que se le envio a su e-mail. 
     * Devuelve true si las credenciales comprobadas coinciden.
     * De lo contrario devuelve false.
     * @param string $contacto Se inserta email o telefono. En la tercera variable se debe especificar el tipo de contacto proporcionado.
     * @param string $cod Esta encriptado
     * @param string $tipo
     * @return boolean
     */
    public function authReserva($contacto, $cod, $tipo = "email") {
        if ($tipo == "email") {
            $res = $this->CI->db->query("SELECT * from reserva WHERE codigo = {$cod}, email = '{$contacto}'");
            if ($res->num_rows() == 1) {
                return true;
            }
        } else {
            if ($tipo == "telefono") {
                $res = $this->CI->db->query("SELECT * from reserva WHERE codigo = {$cod}, telefono = '{$contacto}'");
                if ($res->num_rows() == 1) {
                    return true;
                }
            }
        }

        return false;
    }

    public function __toString() {
        return "[Objeto] Sistema de reservas";
    }
    
    public function getModel(){
        if(!$this->modelInitialized){
            $this->CI->load->model("be_reservas_model");
        }
        return $this->CI->be_reservas_model;
    }

}
