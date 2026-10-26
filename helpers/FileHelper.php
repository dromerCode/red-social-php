<?php
// Funciones para subir y borrar las imágenes de los posts
class FileHelper {
    // Carpeta donde se guardan las imágenes (en la web se ven como img/posts/nombre.jpg)
    const CARPETA = __DIR__ . '/../img/posts/';
    const EXTENSIONES = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    const TAMANO_MAXIMO = 2 * 1024 * 1024; // 2 MB

    // Comprueba que el archivo se ha subido bien, que es una imagen y que no pesa demasiado
    public static function esImagenValida(array $archivo): bool {
        if ($archivo['error'] != UPLOAD_ERR_OK) {
            return false;
        }
        if ($archivo['size'] > self::TAMANO_MAXIMO) {
            return false;
        }
        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, self::EXTENSIONES)) {
            return false;
        }
        // getimagesize devuelve false si el archivo no es una imagen de verdad
        if (getimagesize($archivo['tmp_name']) === false) {
            return false;
        }
        return true;
    }

    // Mueve la imagen a la carpeta con un nombre único y devuelve ese nombre para guardarlo en la BD
    public static function subirImagen(array $archivo): string {
        $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
        $nombre = uniqid('post_') . '.' . $extension;
        move_uploaded_file($archivo['tmp_name'], self::CARPETA . $nombre);
        return $nombre;
    }

    public static function borrarImagen(?string $nombre) {
        if ($nombre != null && file_exists(self::CARPETA . $nombre)) {
            unlink(self::CARPETA . $nombre);
        }
    }
}
