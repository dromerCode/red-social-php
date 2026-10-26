<?php
require_once __DIR__ . '/../repositories/PostRepository.php';

$postRepository = new PostRepository($db);

if (isset($_POST['crear_titulo'], $_POST['crear_texto'])) {
    $titulo = trim($_POST['crear_titulo']);
    $texto = trim($_POST['crear_texto']);
    
    if ($titulo == '' || $texto == '') {
        $mensaje = "Debes rellenar el título y el texto del post";
    } else {
        // Id y fecha los pone la BD. La imagen todavía no. El autor es el de la sesión.
        $nuevoPost = new Post(null, $titulo, $texto, null, null, $usuario->getId(), null, 0, 0);
        $postRepository->save($nuevoPost);
        // Volvemos a la lista para ver el post (y que al recargar no se publique otra vez)
        header("Location: index.php");
        exit;
    }
}

if (isset($_GET['var']) && $_GET['var'] == 'nuevo'){
    require __DIR__ . '/../views/nuevoPostView.phtml';
    exit;
}

$posts = $postRepository->findAll();

require __DIR__ . '/../views/mainView.phtml';
