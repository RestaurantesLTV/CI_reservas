<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

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
        $this->reservaPorTurnos = new ReservaPorTurnos();
    }
    
    public function index(){
        
    }
    
    private function testLoadTurnosFromDB(){
        
    }
}
