<?php

require_once __DIR__ . '/../models/Post.php';

// Repositorio de posts: es la única clase que habla con la tabla "post" (y "megusta").
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

    // Busca un post por su id. Devuelve el Post o null si no existe.
    public function findById($id){
        $stmt = $this->db->prepare("SELECT p.id_post, p.titulo, p.texto, p.imagen, p.fecha, p.id_usuario, u.nombre,
                (SELECT COUNT(*) FROM comentario c WHERE c.id_post = p.id_post) AS num_comentarios,
                (SELECT COUNT(*) FROM megusta m WHERE m.id_post = p.id_post) AS num_likes
            FROM post p
            JOIN usuario u ON p.id_usuario = u.id_usuario
            WHERE p.id_post = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $fila = $stmt->get_result()->fetch_assoc();

        if (!$fila) {
            return null;
        }
        return new Post($fila['id_post'], $fila['titulo'], $fila['texto'], $fila['imagen'], $fila['fecha'], $fila['id_usuario'], $fila['nombre'], $fila['num_comentarios'], $fila['num_likes']);
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

    // Borra un post. Sus comentarios y likes se borran solos (ON DELETE CASCADE).
    public function delete($id){
        $stmt = $this->db->prepare("DELETE FROM post WHERE id_post = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }

    // ---------- LIKES ----------

    // Devuelve los ids de los posts a los que el usuario ha dado like (para pintar el corazón lleno)
    public function findLikesUsuario($idUsuario){
        $stmt = $this->db->prepare("SELECT id_post FROM megusta WHERE id_usuario = ?");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $ids = array();
        while ($fila = $resultado->fetch_assoc()) {
            $ids[] = $fila['id_post'];
        }
        return $ids;
    }

    // Si el usuario ya le había dado like al post se lo quita, y si no se lo pone
    public function cambiarLike($idPost, $idUsuario){
        $stmt = $this->db->prepare("SELECT id_post FROM megusta WHERE id_post = ? AND id_usuario = ?");
        $stmt->bind_param("ii", $idPost, $idUsuario);
        $stmt->execute();

        if ($stmt->get_result()->fetch_assoc()) {
            $stmt = $this->db->prepare("DELETE FROM megusta WHERE id_post = ? AND id_usuario = ?");
        } else {
            $stmt = $this->db->prepare("INSERT INTO megusta (id_post, id_usuario) VALUES (?, ?)");
        }
        $stmt->bind_param("ii", $idPost, $idUsuario);
        $stmt->execute();
    }
}
