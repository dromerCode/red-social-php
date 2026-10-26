<?php

// Clase Post
class Post {
    private $id;
    private $titulo;
    private $contenido;
    // nombre del archivo en img/posts (o null)
    private $imagen;
    private $fecha;
    private $usuario_id;
    private $nombreAutor;
    private $numComentarios;
    private $numLikes;

    public function __construct($id, $titulo, $contenido, $imagen, $fecha, $usuario_id, $nombreAutor, $numComentarios, $numLikes) {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->contenido = $contenido;
        $this->imagen = $imagen;
        $this->fecha = $fecha;
        $this->usuario_id = $usuario_id;
        $this->nombreAutor = $nombreAutor;
        $this->numComentarios = $numComentarios;
        $this->numLikes = $numLikes;
    }

    public function getId() {
        return $this->id;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function getContenido() {
        return $this->contenido;
    }

    public function getImagen() {
        return $this->imagen;
    }

    public function getFecha() {
        return $this->fecha;
    }

    public function getUsuarioId() {
        return $this->usuario_id;
    }

    public function getNombreAutor() {
        return $this->nombreAutor;
    }

    public function getNumComentarios() {
        return $this->numComentarios;
    }

    public function getNumLikes() {
        return $this->numLikes;
    }
}
