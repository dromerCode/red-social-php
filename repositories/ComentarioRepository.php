<?php

require_once __DIR__ . '/../models/Comentario.php';

// Repositorio de comentarios: es la única clase que habla con las tablas "comentario" y "megusta_comentario".
class ComentarioRepository{
    private mysqli $db;

    public function __construct(mysqli $db){
        $this->db = $db;
    }

    // Devuelve los comentarios de un post, del más viejo al más nuevo, con el autor y sus likes
    public function findByPost($idPost){
        $stmt = $this->db->prepare("SELECT c.id_comentario, c.texto, c.fecha, c.id_post, c.id_usuario, u.nombre,
                (SELECT COUNT(*) FROM megusta_comentario m WHERE m.id_comentario = c.id_comentario) AS num_likes
            FROM comentario c
            JOIN usuario u ON c.id_usuario = u.id_usuario
            WHERE c.id_post = ?
            ORDER BY c.fecha, c.id_comentario");
        $stmt->bind_param("i", $idPost);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $comentarios = array();
        while ($fila = $resultado->fetch_assoc()) {
            $comentarios[] = new Comentario($fila['id_comentario'], $fila['texto'], $fila['fecha'], $fila['id_post'], $fila['id_usuario'], $fila['nombre'], $fila['num_likes']);
        }
        return $comentarios;
    }

    // Guarda un comentario nuevo. El id y la fecha los pone la base de datos.
    public function save(Comentario $comentario){
        $stmt = $this->db->prepare("INSERT INTO comentario (texto, id_post, id_usuario) VALUES (?, ?, ?)");
        $texto = $comentario->getTexto();
        $idPost = $comentario->getIdPost();
        $usuario_id = $comentario->getUsuarioId();
        $stmt->bind_param("sii", $texto, $idPost, $usuario_id);
        $stmt->execute();
    }

    // Borra un comentario, pero solo si es de ese usuario (así nadie borra los de otros)
    public function delete($id, $idUsuario){
        $stmt = $this->db->prepare("DELETE FROM comentario WHERE id_comentario = ? AND id_usuario = ?");
        $stmt->bind_param("ii", $id, $idUsuario);
        $stmt->execute();
    }

    // ---------- LIKES ----------

    // Devuelve los ids de los comentarios a los que el usuario ha dado like
    public function findLikesUsuario($idUsuario){
        $stmt = $this->db->prepare("SELECT id_comentario FROM megusta_comentario WHERE id_usuario = ?");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $ids = array();
        while ($fila = $resultado->fetch_assoc()) {
            $ids[] = $fila['id_comentario'];
        }
        return $ids;
    }

    // Si el usuario ya le había dado like al comentario se lo quita, y si no se lo pone
    public function cambiarLike($idComentario, $idUsuario){
        $stmt = $this->db->prepare("SELECT id_comentario FROM megusta_comentario WHERE id_comentario = ? AND id_usuario = ?");
        $stmt->bind_param("ii", $idComentario, $idUsuario);
        $stmt->execute();

        if ($stmt->get_result()->fetch_assoc()) {
            $stmt = $this->db->prepare("DELETE FROM megusta_comentario WHERE id_comentario = ? AND id_usuario = ?");
        } else {
            $stmt = $this->db->prepare("INSERT INTO megusta_comentario (id_comentario, id_usuario) VALUES (?, ?)");
        }
        $stmt->bind_param("ii", $idComentario, $idUsuario);
        $stmt->execute();
    }
}
