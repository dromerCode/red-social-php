<?php

require_once __DIR__ . '/../models/Post.php';

// Repositorio de posts: es la única clase que habla con la tabla "post".
class PostRepository{
    private mysqli $db;

    public function __construct(mysqli $db){
        $this->db = $db;
    }

    // Devuelve todos los posts, del más nuevo al más viejo, con el nombre del autor
    // y cuántos comentarios y likes tiene cada uno.
    public function findAll(){
        $resultado = $this->db->query("SELECT p.id_post, p.titulo, p.texto, p.imagen, p.fecha, p.id_usuario, u.nombre,
                (SELECT COUNT(*) FROM comentario c WHERE c.id_post = p.id_post) AS num_comentarios,
                (SELECT COUNT(*) FROM megusta m WHERE m.id_post = p.id_post) AS num_likes
            FROM post p
            JOIN usuario u ON p.id_usuario = u.id_usuario
            ORDER BY p.fecha DESC, p.id_post DESC");
        $posts = array();
        while ($fila = $resultado->fetch_assoc()) {
            $posts[] = new Post($fila['id_post'], $fila['titulo'], $fila['texto'], $fila['imagen'], $fila['fecha'], $fila['id_usuario'], $fila['nombre'], $fila['num_comentarios'], $fila['num_likes']);
        }
        return $posts;
    }

    // Guarda un post nuevo. El id y la fecha los pone la base de datos.
    public function save(Post $post){
        $stmt = $this->db->prepare("INSERT INTO post (titulo, texto, imagen, id_usuario) VALUES (?, ?, ?, ?)");
        $titulo = $post->getTitulo();
        $contenido = $post->getContenido();
        $imagen = $post->getImagen();
        $usuario_id = $post->getUsuarioId();
        $stmt->bind_param("sssi", $titulo, $contenido, $imagen, $usuario_id);
        $stmt->execute();
    }
}
