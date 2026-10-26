<?php
require_once __DIR__ . '/../repositories/PostRepository.php';
require_once __DIR__ . '/../repositories/ComentarioRepository.php';
require_once __DIR__ . '/../helpers/FileHelper.php';

$postRepository = new PostRepository($db);
$comentarioRepository = new ComentarioRepository($db);

// ---------- NUEVO POST ----------
if (isset($_POST['crear_titulo'], $_POST['crear_texto'])) {
    $titulo = trim($_POST['crear_titulo']);
    $texto = trim($_POST['crear_texto']);
    // La imagen es opcional: si no eligen ninguna se queda a null
    $imagen = null;
    $error = false;

    if ($titulo == '' || $texto == '') {
        $mensaje = "Debes rellenar el título y el texto del post";
        $error = true;
    } elseif ($_FILES['imagen']['error'] != UPLOAD_ERR_NO_FILE) {
        if (FileHelper::esImagenValida($_FILES['imagen'])) {
            $imagen = FileHelper::subirImagen($_FILES['imagen']);
        } else {
            $mensaje = "La imagen no es válida o pesa más de 2 MB";
            $error = true;
        }
    }

    if (!$error) {
        // Id y fecha los pone la BD. El autor es el de la sesión.
        $nuevoPost = new Post(null, $titulo, $texto, $imagen, null, $usuario->getId(), null, 0, 0);
        $postRepository->save($nuevoPost);
        // Volvemos a la lista para ver el post (y que al recargar no se publique otra vez)
        header("Location: index.php");
        exit;
    }
}

// ---------- BORRAR POST ----------
if (isset($_POST['borrar_post'])) {
    $post = $postRepository->findById($_POST['borrar_post']);
    // Solo puede borrarlo su autor. Antes borramos la imagen de la carpeta.
    if ($post && $post->getUsuarioId() == $usuario->getId()) {
        FileHelper::borrarImagen($post->getImagen());
        $postRepository->delete($post->getId());
    }
    header("Location: index.php");
    exit;
}

// ---------- LIKE A UN POST ----------
if (isset($_POST['like_post'])) {
    $postRepository->cambiarLike($_POST['like_post'], $usuario->getId());
    // Si el like se ha dado desde la página del post volvemos ahí, si no a la lista
    if (isset($_POST['volver']) && $_POST['volver'] == 'ver') {
        header("Location: index.php?var=ver&id=" . $_POST['like_post']);
    } else {
        header("Location: index.php");
    }
    exit;
}

// ---------- COMENTAR ----------
if (isset($_POST['comentar_texto'], $_POST['comentar_post'])) {
    $texto = trim($_POST['comentar_texto']);
    if ($texto != '') {
        $nuevoComentario = new Comentario(null, $texto, null, $_POST['comentar_post'], $usuario->getId(), null, 0);
        $comentarioRepository->save($nuevoComentario);
    }
    header("Location: index.php?var=ver&id=" . $_POST['comentar_post']);
    exit;
}

// ---------- BORRAR COMENTARIO ----------
if (isset($_POST['borrar_comentario'], $_POST['id_post'])) {
    // El repositorio solo lo borra si el comentario es de este usuario
    $comentarioRepository->delete($_POST['borrar_comentario'], $usuario->getId());
    header("Location: index.php?var=ver&id=" . $_POST['id_post']);
    exit;
}

// ---------- LIKE A UN COMENTARIO ----------
if (isset($_POST['like_comentario'], $_POST['id_post'])) {
    $comentarioRepository->cambiarLike($_POST['like_comentario'], $usuario->getId());
    header("Location: index.php?var=ver&id=" . $_POST['id_post']);
    exit;
}

// ---------- VISTAS ----------

// Formulario de nuevo post
if (isset($_GET['var']) && $_GET['var'] == 'nuevo') {
    require __DIR__ . '/../views/nuevoPostView.phtml';
    exit;
}

// Ids de los posts a los que les he dado like (para pintar el corazón lleno)
$misLikes = $postRepository->findLikesUsuario($usuario->getId());

// Un post con sus comentarios
if (isset($_GET['var']) && $_GET['var'] == 'ver' && isset($_GET['id'])) {
    $post = $postRepository->findById($_GET['id']);
    if ($post) {
        $comentarios = $comentarioRepository->findByPost($post->getId());
        $misLikesComentarios = $comentarioRepository->findLikesUsuario($usuario->getId());
        require __DIR__ . '/../views/verPostView.phtml';
        exit;
    }
    $mensaje = "Ese post no existe";
}

// Lista de posts
$posts = $postRepository->findAll();

require __DIR__ . '/../views/mainView.phtml';
