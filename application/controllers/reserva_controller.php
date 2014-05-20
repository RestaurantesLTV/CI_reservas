<?php

/**
 * Description of reservar
 *
 * @author unscathed18
 */
class Reserva_Controller extends CI_Controller {
//http://davidwalsh.name/gmail-php-imap
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

    /* public function validar(){
      echo "EXITO!";
      } */

    public function validar() {


        $this->form_validation->set_rules("nombre", "Nombre", "trim|required|alpha|max_length[40]");
        $this->form_validation->set_rules("apellido", "Apellido", "trim|required|alpha|max_length[40]");
        $this->form_validation->set_rules("telefono", "Telefono", "trim|required|numeric|is_natural");

        //Hora y tiempo
        $this->form_validation->set_rules("hora", "Hora", "trim|required|numeric|is_natural");
        $this->form_validation->set_rules("minuto", "Minuto", "trim|required|is_natural");
        $this->form_validation->set_rules("turno", "Turno", "trim|required|is_natural");

        //Otros
        $this->form_validation->set_rules("observaciones", "Observaciones", "trim|max_length[600]");
        $this->form_validation->set_rules("email", "Email", "trim|required|valid_email");
        $this->form_validation->set_rules("fecha", "Fecha", "trim|required|callback_fecha_check");
        $this->form_validation->set_rules("num_personas", "N&uacute;mero de personas", "trim|required|is_natural_no_zero");

        if ($this->form_validation->run() === FALSE) {
            echo validation_errors();
        } else {
            /* Adpatamos y verificamos el INPUT */
            $exito = "<p>Enviado con &eacute;xito!</p>";
            $hora = $this->input->post('hora') . ":" . $this->input->post('minuto') . ":00";
            $fecha_YMD = explode("/", $this->input->post('fecha'));
            $fecha_YMD = $fecha_YMD[2] . "/" . $fecha_YMD[1] . "/" . $fecha_YMD[0];

            $hora_fecha = $fecha_YMD . " " . $hora;
            $d = new DateTime($hora_fecha);

            $r = new ReservaPorTurnos($this->input->post('turno'), $this->input->post('telefono'), $this->input->post('email'), $d, $this->input->post('num_personas'), $this->input->post('observaciones'));

            $this->reservasmanager->nuevaReserva($r);

            // Reservamos (Dentro del metodo verificamos si esta o no disponible la reserva)
            $reservado = $this->reservasmanager->reservar();

            if ($reservado != "") {
                die($reservado);
            }

            //Se puede reservar. Ahora hace falta comprobar si exceden de las 16 personas.
            if ($this->input->post('num_personas') > 16) {
                $exito .= "<p>El restaurante se pondra en contacto con usted en breve para confirmar su reserva.</p>";
            } else {
                $subject = $this->input->post("nombre") . " " . $this->input->post("apellido") . ", usted esta a un paso mas de confirmar su reserva.";
                $id = $reserva_id = $this->reservasmanager->getReserva()->getID();
                $this->_enviarEmail($subject, $id ,$this->input->post('email'), $this->reservasmanager->getReserva()->getCodigo());
                
                $exito .= "<p>Verifique su email para confirmar la reserva.</p>";
            }

            echo $exito;
        }
    }

    private function _enviarEmail($subject, $id_row, $email, $cod_reserva) {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        $config = Array(
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.googlemail.com',
            'smtp_port' => 465,
            'smtp_user' => $this->reservasmanager->getEmailUser(),
            'smtp_pass' => $this->reservasmanager->getEmailPass(),
            'mailtype' => 'html',
            'charset' => 'iso-8859-1'
        );
        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");




        /*         * **************************************************************** */

        $this->email->from($this->reservasmanager->getEmailUser(), $this->reservasmanager->getNombre());
        $this->email->to($email);
        
        $url_comprobacion = site_url("verificar?id=").$id_row ."&email=". $email . "&cod_reserva=" . $cod_reserva;
        $mensaje = '<p>Su reserva ha ido bien! Vaya al siguiente link para confirmarla:</p>';
        $mensaje .= "<p><a href='{$url_comprobacion}'>" . $url_comprobacion . "</a></p>";

        $this->email->subject($subject);
        $this->email->message($mensaje);

        $this->email->send();
        
        echo "Verifique su email para confirmar la reserva";

        //echo $this->email->print_debugger();
        
    }

    /**
     * Verifica el email recibido por el usuario para confirmar
     * finalmente la reserva hecha. El email enviado tiene un link con
     * con parametros GET del ID de la base de datos de la reserva, el codigo,
     * y el email del reservante.
     */
    public function verificarReserva() {
        $cod_reserva = $this->input->get('cod_reserva');
        $email = $this->input->get('email');
        $id = $this->input->get('id');
        

        $data = array(
            'verificado' => 1,
        );

        //codigo, email

        $this->db->where('codigo', $cod_reserva);
        $this->db->where('email', $email);
        $this->db->where('id', $id);
        $this->db->update('reserva', $data);

        $affected_rows = $this->db->affected_rows();
        
        if ($affected_rows == 0) {
            die("Codigo invalido :( ");
        }
        echo "Verificado con &eacute;xito! Su reserva ya esta hecha por completo.";
    }

    function fecha_check($str) {
        $fecha = explode("/", $str);
        if (count($fecha) == 3) {
            //Mes       //Dia       //Year
            if (!checkdate($fecha[1], $fecha[0], $fecha[2])) {
                $this->form_validation->set_message('fecha_check', 'Debe ser una fecha valida dentro del rango! Erroneo: %s');
                return FALSE;
            } else {
                return TRUE;
            }
        }
        return FALSE;
    }

}
