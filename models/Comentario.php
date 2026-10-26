<?php

// Clase Comentario
class Comentario {
    private $id;
    private $texto;
    private $fecha;
    private $idPost;
    private $usuario_id;
    // estos salen de la consulta, no son columnas
    private $nombreAutor;
    private $numLikes;

    public function __construct($id, $texto, $fecha, $idPost, $usuario_id, $nombreAutor, $numLikes) {
        $this->id = $id;
        $this->texto = $texto;
        $this->fecha = $fecha;
        $this->idPost = $idPost;
        $this->usuario_id = $usuario_id;
        $this->nombreAutor = $nombreAutor;
        $this->numLikes = $numLikes;
    }

    public function getId() {
        return $this->id;
    }

    public function getTexto() {
        return $this->texto;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function getIdPost() {
        return $this->idPost;
    }

    public function getUsuarioId() {
        return $this->usuario_id;
    }

    public function getNombreAutor() {
        return $this->nombreAutor;
    }

    public function getNumLikes() {
        return $this->numLikes;
    }
}
