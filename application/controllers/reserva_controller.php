<?php

/**
 * Description of reservar
 *
 * @author unscathed18
 */
class Reserva_Controller extends CI_Controller {

    private $reserva = null; // Por turnos

    public function __construct() {
        parent::__construct();
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->customautoloader->load("ReservaPorTurnos");
    }

    public function index() {
        $templates_dir = "front-end/templates/";
        
        $this->load->view($templates_dir."header");
        $d = new DateTime();
        $d->add(new DateInterval("P2M"));
        $this->load->view("front-end/"."reserva",array('h' => new DateTime(), 'd' => $d));
    }

    private function _requestIsFromProxy() {
        //http://stackoverflow.com/questions/3003145/how-to-get-the-client-ip-address-in-php
        // http://stackoverflow.com/questions/4527345/determine-if-user-is-using-proxy
    }
    
    public function validar(){
        
    }
}
