<?php

/**
 * Esta libreria nos permite cargar clases en cualquier parte del proyecto.
 * Esto borrar la limitacion de CodeIgniter, ya que no permite
 * instanciar clases.
 * @see http://stackoverflow.com/questions/9511636/custom-classes-in-codeigniter
 * @author unscathed18 <unscathed21@hotmail.com>
 */
class CustomAutoLoader{

    /**
     * Registar en la pila de llamadas de carga de clases de PHP predefinida, la funcion 'load'.
     */
    public function __construct(){
        spl_autoload_register(array($this, 'load')); // Registramos en la pila de funciones a llamar 'load', cuando PHP no encuentre una clase.
    }

    /**
     * @todo No permite incluir namespaces donde se tiene como criterio separa los namespaces por carpetas.
     * @param String $className Nombre de la clase a incluir.
     */
    public function load($className){
        require  APPPATH . "classes/"  .str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
    }

}
