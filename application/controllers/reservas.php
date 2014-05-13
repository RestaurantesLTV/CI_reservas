<?php

/**
 * Description of reservar
 *
 * @author unscathed18
 */
class Reservas extends CI_Controller {

    private $reserva = null; // Por turnos

    public function __construct() {
        parent::__construct();
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->customautoloader->load("ReservaPorTurnos");
    }

    public function index() {
        /*$this->reserva = new ReservaPorTurnos(1);
        //$this->load->view('front-end/reservas');
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('front-end/reservas');
        } else {
            $this->load->view('front-end/formsuccess');
        }*/
        //$data = getConfigArray();
        $this->_render("reservas");
    }
    
    private function getConfigArray(){
        $data = array();
        $data[''];
        return $data;
    }
    
    private function _render($viewName){
        $templates_dir = "front-end/templates/";
        $this->load->view($templates_dir."header");
        $this->load->view("front-end/".$viewName);
    }

    private function _requestIsFromProxy() {
        //http://stackoverflow.com/questions/3003145/how-to-get-the-client-ip-address-in-php
        // http://stackoverflow.com/questions/4527345/determine-if-user-is-using-proxy
    }
    
    public function getCalendar($day, $month, $year){
        $mes = "";
    }

}
