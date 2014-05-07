<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Ejemplo de funcionamiento de la libreria 'CustomAutoLoader'.
 * @author unscathed18 <unscathed21@hotmail.com>
 */
class Ejem extends CI_Controller{
    public function __construct(){
        parent::__construct();
        
    }
    
    public function index(){
        $persona = new Persona("Leo", 18);
        echo $persona;
    }
}
