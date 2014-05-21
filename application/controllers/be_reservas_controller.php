<?php

/**
 * Pensado para el backend del sistema de reservas.
 * Este controlador esta preparado para responder a peticiones AJAX.
 * Cada funcion del modelo "be_reservas_model" tiene su equivalente con prefijo "ajax",
 * la cual convierte todos los resultados de la BD en format JSON. Esto permite
 * la directa manipulacion en el navegador con JS.
 * @author unscathed18
 */
class be_reservas_controller extends CI_Controller{
    
    /**
     *
     * @var modelo
     */
    private $model = null;
    
    public function __construct(){
        parent::__construct();
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $this->load->library('ReservasManager');
        $this->model = $this->reservasmanager->getModel();
    }
    
    public function index(){
        $this->load->view('front-end/be_reservas_view');
    }
    
    public function ajaxReservasNoVistas(){
        $no_vistas = $this->model->getReservasNoVistas();
        
        echo $this->dbResult_toJSON($no_vistas);
    }
    
    public function ajaxReservasUltimos7Dias(){
        $ultimos_dias = $this->model->getReservasUltimos7Dias();
        echo $this->dbResult_toJSON($ultimos_dias);
    }
    
    public function ajaxProximasReservas(){
        $proximas_reservas = $this->model->getProximasReservas();
        echo $this->dbResult_toJSON($proximas_reservas);
    }
    
    public function dbResult_toJSON($result){
        $i = 0;
        $json = null; /* Si la query no da ningun resultado (Por lo tanto no entrara 
                        * por el 'foreach' de debajo), esto permanecera NULL.*/
        
        foreach($result->result_array() as $row){
            foreach($row as $key => $value){
                $json[$i][$key] = $value;
            }
            $i++;
        }
        
        return json_encode($json);
    }
}
