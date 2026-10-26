<?php

// Clase Usuario
class Usuario{
    private $id;
    private $nombre;
    private $email;
    private $password;
    public function __construct($id, $nombre, $email, $password){
        $this->id = $id;
        $this->nombre = $nombre;
        $this->email = $email;
        $this->password = $password;
    }

    public function getId(){
        return $this->id;
    }
    public function getNombre(){
        return $this->nombre;
    }
    public function getEmail(){
        return $this->email;
    }
    public function getPassword(){
        return $this->password;
    }
}
