<?php
require_once __DIR__ . '/../repositories/PostRepository.php';
require_once __DIR__ . '/../repositories/ComentarioRepository.php';
require_once __DIR__ . '/../helpers/FileHelper.php';

$postRepository = new PostRepository($db);
$comentarioRepository = new ComentarioRepository($db);

// nuevo post
if (isset($_POST['crear_titulo'], $_POST['crear_texto'])) {
    $titulo = trim($_POST['crear_titulo']);
    $texto = trim($_POST['crear_texto']);
    // la imagen es opcional
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
        $nuevoPost = new Post(null, $titulo, $texto, $imagen, null, $usuario->getId(), null, 0, 0);
        $postRepository->save($nuevoPost);
        header("Location: index.php");
        exit;
    }
}

// borrar post
if (isset($_POST['borrar_post'])) {
    $post = $postRepository->findById($_POST['borrar_post']);
    // solo si es mio, y borro tambien la imagen
    if ($post && $post->getUsuarioId() == $usuario->getId()) {
        FileHelper::borrarImagen($post->getImagen());
        $postRepository->delete($post->getId());
    }
    header("Location: index.php");
    exit;
}

// like a un post
if (isset($_POST['like_post'])) {
    $postRepository->cambiarLike($_POST['like_post'], $usuario->getId());
    // volver al post o a la lista
    if (isset($_POST['volver']) && $_POST['volver'] == 'ver') {
        header("Location: index.php?var=ver&id=" . $_POST['like_post']);
    } else {
        header("Location: index.php");
    }
    exit;
}

// comentar
if (isset($_POST['comentar_texto'], $_POST['comentar_post'])) {
    $texto = trim($_POST['comentar_texto']);
    if ($texto != '') {
        $nuevoComentario = new Comentario(null, $texto, null, $_POST['comentar_post'], $usuario->getId(), null, 0);
        $comentarioRepository->save($nuevoComentario);
    }
    header("Location: index.php?var=ver&id=" . $_POST['comentar_post']);
    exit;
}

// borrar comentario
if (isset($_POST['borrar_comentario'], $_POST['id_post'])) {
    // solo borra si el comentario es mio
    $comentarioRepository->delete($_POST['borrar_comentario'], $usuario->getId());
    header("Location: index.php?var=ver&id=" . $_POST['id_post']);
    exit;
}

// like a un comentario
if (isset($_POST['like_comentario'], $_POST['id_post'])) {
    $comentarioRepository->cambiarLike($_POST['like_comentario'], $usuario->getId());
    header("Location: index.php?var=ver&id=" . $_POST['id_post']);
    exit;
}

// vistas

if (isset($_GET['var']) && $_GET['var'] == 'nuevo') {
    require __DIR__ . '/../views/nuevoPostView.phtml';
    exit;
}

// posts a los que he dado like
$misLikes = $postRepository->findLikesUsuario($usuario->getId());

// ver un post
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

// lista de posts
$posts = $postRepository->findAll();

require __DIR__ . '/../views/mainView.phtml';
