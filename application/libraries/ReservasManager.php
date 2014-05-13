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
            $this->reserva = new ReservaPorTurnos();
            loadTurnosFromDB();
        } else {
            $this->reserva = new ReservaPorTiempo();
        }

        if (!$this->reserva) {
            throw new Exception('Clase "' . __CLASS__ . '" no ha podido crear con exito el objeto "Reserva"');
        }
    }

    /**
     * @todo Implementar un sistema para calcular dias festivos
     * @return boolean
     */
    private function esFestivo() {
        if ($this->esLaborable()) {
            $query = $this->CI->db->query("SELECT * FROM festividad WHERE fecha = ");
            foreach ($query->result() as $row) {
                $this->turnos[$row->id - 1] = $row->nombre;
            }
        }
        return false;
    }

    /**
     * Este metodo imita la "sobrecarga" de otros lenguajes. Dependiendo de si se
     * introduce un argumento o 3 de ellos se ejecuta una subrutina u otra. 
     * 
     * - Version 1: Coge un objeto de tipo DateTime
     * - Version 2: Coge 3 integers con la siguiente firma: dia, mes y anio.
     * @param DateTime(version1)/dia(version2) $datetime
     * @param int $mes
     * @param int $year
     * @return String Devuelve un String que puede interpretar la base de datos.
     */
    protected function formatToDBDateFormat($datetime, $mes = null, $year = null) {
        if ($mes != null) { // Rutina: Version 2
            if ($year != null) {
                throw new Exception("Por implementar version 2 de esta funcion.");
                if (!is_int($mes) || !is_int($year) || !is_int($datetime)) {
                    throw new Exception("Los argumentos dados no son Integer!");
                }
            }
        } else { // Rutina: Version 1
            // Ejemplo de sintaxis MySQL de fecha: "2014-05-12"
            return $datetime->format("Y-m-d");
        }
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
    private function dumpConfigFileOptions() {
        
    }


    private function momentoValido() {
        $momento = $this->reserva->getMomento();

        if (!is_int($momento)) {
            throw new Exception("No es un integer.");
        }

        // Comparacion segun sistema de reserva.
        if ($this->config['sistema'] == "turnos") {
            foreach ($this->turnos as $turno_num => $turno) {
                if ($turno_num == $momento) {
                    return true;
                }
            }
            return false;
        } else {
            if ($this->config['sistema'] == "tiempo") {
                throw new Exception("Falta por implementar");
                return true;
            }
        }

        throw new Exception("Metodo " . __METHOD__ . " de la clase " . __CLASS__ . ", " . " debe saber el sistema que se escoge.");
    }

    /**
     * Devuelve 'true' si esta disponible acorde a ese 'momento' en concreto
     * no es festivo, y quedan mesas disponibles.
     * @todo Acabar de completar
     * @return boolean
     */
    public function disponible() {
        if ($this->esFestivo()) {
            return false;
        }



        return true;
    }

    /**
     * Si la reserva fue bien devuelve true, sino false.
     * @return boolean
     */
    public function reservar(Reserva $r) {
        if ($this->disponible()) {
            //Reservamos si queda espacio
            if ($this->aforoRestante() > $this->r->getNumPersonas()) {
                $cod_reserva = $this->generarCodigoReserva();
                $cod_encriptado = $this->encrypt->encode($msg);
                // Ejemplo de consulta: INSERT INTO reserva VALUES (null, "ASDADS", null, "2014-01-15", "07:13", 2, "192.168", 4, null, "93123229", "unscathed512@hotmail.com", 0)
                $result = $this->CI->db->query("INSERT INTO reserva VALUES (null,'{$cod_reserva}',{$r->getFecha()},{$r->getHora()},{$r->getMomento()}, {$_SERVER['HTTP_X_FORWARDED_FOR']}, {$r->getNumPersonas()}, {$r->getDescripcion()}, {$r->getTelefono()}, {$r->getEmail()}, 0 ");
            }
        }
        return false;
    }

    /**
     * Asigna las mesas 
     * @param int $numPersonas
     * @return int[] Devuelve los numeros de las mesas asignadas
     */
    /* private function asignarMesas($numPersonas){
      //return
      } */



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

    public function nuevaReserva() {
        if ($this->tipo == turnos) {
            $this->reserva = new ReservaPorTurnos;
        } else {
            $this->reserva = new ReservaPorTiempo;
        }
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

}
