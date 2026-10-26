<?php
// Para subir y borrar las imagenes
class FileHelper {
    const CARPETA = __DIR__ . '/../img/posts/';
    const EXTENSIONES = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    const TAMANO_MAXIMO = 2 * 1024 * 1024; // 2 MB

    // comprueba que es una imagen y que no pasa de 2 MB
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
        // si no es una imagen de verdad devuelve false
        if (getimagesize($archivo['tmp_name']) === false) {
            return false;
        }
        return true;
    }

    // guarda la imagen con un nombre unico y devuelve el nombre
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
