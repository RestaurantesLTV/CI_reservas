<?php

/**
 * Esta clase esta especialmente diseñada para atender a las requestes AJAX
 * hechas por cualquier cliente respecto al sistema de reservas.
 * @author unscathed18 <unscathed21@hotmail.com>
 */
class ReservasAsk extends CI_Controller {
    public function __construct(){
        parent::__construct();
        
    }
    
    public function index(){
        if(!_POST){
            die("POST NO EXISTE!");
        }
        $request = $this->input->post('request');
        
        if(!$request || empty($request)){
            die("Variable 'request' no especificada");
        }
        
        $result = $this->processRequest($request);
        
        echo $result;
    }
    
    /**
     * @param string $request Dependiendo del valor, se atendera una request u otra.
     * @param array $params  Array que contiene todos los parametros.
     */
    private function _processRequest($request, $params){
        $result = "";
        switch($request){
            case "nuevo_mes":
                $result = $this->_nuevo_mes();
                break;
            default:
                die("Variable 'request' tiene un valor incorrecto. No ha podido resolverse su peticion");
                break;
        }
        return $result;
    }
    
    private function _nuevo_mes(){
        /*
         * $.post("calendar", {day : 3, month: 4, year: 8, turno: 2})
         */
    }
}
