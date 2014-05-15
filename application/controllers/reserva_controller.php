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
        $this->load->library('ReservasManager');
        $this->customautoloader->load("ReservaPorTurnos");
    }

    public function index() {
        $templates_dir = "front-end/templates/";

        $this->load->view($templates_dir . "header");
        $d = new DateTime();
        $d->add(new DateInterval("P2M"));
        $this->load->view("front-end/" . "reserva", array('h' => new DateTime(), 'd' => $d));
    }

    private function _requestIsFromProxy() {
        //http://stackoverflow.com/questions/3003145/how-to-get-the-client-ip-address-in-php
        // http://stackoverflow.com/questions/4527345/determine-if-user-is-using-proxy
    }

    public function validar() {
        
        $this->form_validation->set_rules("nombre", "Nombre", "trim|required|alpha|max_length[40]");
        $this->form_validation->set_rules("apellido", "Apellido", "trim|required|alpha|max_length[40]");
        $this->form_validation->set_rules("telefono", "Telefono", "trim|required|numeric|is_natural");
        
        //Hora y tiempo
        $this->form_validation->set_rules("hora", "Hora", "trim|required|numeric|is_natural");
        $this->form_validation->set_rules("minuto", "Minuto", "trim|required|numeric|is_natural");
        $this->form_validation->set_rules("turno", "Turno", "trim|required|numeric|is_natural");
        
        //Otros
        $this->form_validation->set_rules("observaciones", "Observaciones", "trim|required|max_length[600]");
        $this->form_validation->set_rules("email", "Email", "trim|required|valid_email");
        $this->form_validation->set_rules("fecha", "Fecha", "trim|required|callback_fecha_check");

        if ($this->form_validation->run() === FALSE) {
            echo validation_errors();
        } else {
            //$r = new ReservaPorTurnos();
            echo "exito";
        }
    }
    
    function fecha_check($str){
        $fecha = explode("/", $str);
        if(count($fecha) == 3){
                            //Mes       //Dia       //Year
            if(!checkdate ($fecha[1] , $fecha[0] , $fecha[2] )){  
               $this->form_validation->set_message('fecha_check', 'Debe ser una fecha valida dentro del rango! Erroneo: %s');
               return FALSE;
            }else{
                return TRUE;
            }
        }
        return FALSE;
    }

}
