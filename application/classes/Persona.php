<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Este es un ejemplo para probar la clase CustomAutoLoader.
 * @author unscathed18 <unscathed21@hotmail.com>
 */


class Persona {
    private $nombre;
    private $edad;
    
    public function __construct($nombre, $edad) {
        $this->nombre = $nombre;
        $this->edad = $edad;
    }
    
    public function __toString(){
        return $this->nombre." : ".$this->edad." a&ntilde;os";
    }
}
