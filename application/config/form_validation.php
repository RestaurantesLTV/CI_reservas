<?php

/**
 * trim ---> limpia de espacios en blanco al principio y al final 
 * alpha --> nombres alfabeticos solo
 * strip_tags|xss_clean --> Seguridad al codigo, evita ejecutar scripts
 * prep_url --> añade http://
 * callback_metodo --> comprueba si existe algo en la base de datos
 * 
 * Archivo de configuracion para la validacion de datos.
 * Contiene:
 *          -->controlador/funcion
 *          -->form/validar (entradas blog)
 *          -->login        (sistema de login)
 * 
 * @todo Falta hacer controlador para el login de usuarios
 */

$config = array(
    'form/validar' => array(
        array(
            'field' => 'titulo',
            'label' => 'Titulo',
            'rules' => 'trim|required|callback_check_email'
        //  'rules' => 'trim|required|max_length[35]|strip_tags|xss_clean' 
        ),
        array(
            'field' => 'cabecera',
            'label' => 'Cabecera',
            'rules' => 'trim|required|max_length[40]|min_length[15]|strip_tags|xss_clean'
        ),
        array(
            'field' => 'autor',
            'label' => 'Autor',
            'rules' => 'trim|required|max_length[25]|min_length[8]|strip_tags|xss_clean'
        ),
        array(
            'field' => 'opciones',
            'label' => 'Opciones',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'contenido',
            'label' => 'Contenido',
            'rules' => 'trim|required|max_length[300]|min_length[150]|strip_tags|xss_clean'
        )
    ),
    'login' => array(
        array(
            'field' => 'usuario',
            'label' => 'Usuario',
            'rules' => 'trim|required|max_length[15]|min_length[8]|strip_tags|xss_clean'
        ),
        array(
            'field' => 'password',
            'label' => 'Contraseña',
            'rules' => 'trim|required|max_length[16]|min_length[6]|matches["vonfirmar"]|strip_tags|xss_clean'
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|required|valid_email|callback_check_email|strip_tags|xss_clean'
        ),
    )
);