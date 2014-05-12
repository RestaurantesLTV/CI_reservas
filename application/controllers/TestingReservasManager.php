<?php

/**
 * Description of TestingReservasManager
 *
 * @author unscathed18
 */
class TestingReservasManager extends CI_Controller{
    
    private $reservaPorTurnos = null;
    
    public function __construct() {
        parent::__construct();
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $this->load->library('unit_test');
        $this->customautoloader->load("ReservaPorTurnos");
    }
    
    public function index(){
        $this->testLoadTurnosFromDB();
    }
    
    /**
     * @todo Dificil de crear una funcion de testeo. Ya que no devuelve nada y trata con base datos.
     */
    private function testLoadTurnosFromDB(){
        // Make function public to test & return results.
        //$this->reservaPorTurnos = new ReservaPorTurnos;
        
    }
}
