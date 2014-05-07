<?php

/**
 * Description of reservar
 *
 * @author unscathed18
 */
class Reservas extends CI_Controller {
    public function __construct() {
        parent::__construct();
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $this->load->library('ReservasManager');
    }
    
    public function index(){
        
        echo "En reservas";
    }
}
