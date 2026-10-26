-- Esquema de la red social

CREATE DATABASE IF NOT EXISTS redsocial
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE redsocial;

-- Usuarios
CREATE TABLE usuario (
  id_usuario  INT UNSIGNED NOT NULL AUTO_INCREMENT,
  nombre      VARCHAR(80)  NOT NULL,
  email       VARCHAR(120) NOT NULL UNIQUE,
  -- Hash de password_hash(), nunca la contraseña en texto plano
  contrasena  VARCHAR(255) NOT NULL,
  PRIMARY KEY (id_usuario)
);

-- Publicaciones. La imagen es opcional (nombre del archivo en img/posts)
CREATE TABLE post (
  id_post     INT UNSIGNED NOT NULL AUTO_INCREMENT,
  titulo      VARCHAR(150) NOT NULL,
  texto       TEXT         NOT NULL,
  imagen      VARCHAR(255) NULL,
  fecha       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  id_usuario  INT UNSIGNED NOT NULL,
  PRIMARY KEY (id_post),
  FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario)
);

-- Comentarios de un post. Si se borra el post se borran sus comentarios
CREATE TABLE comentario (
  id_comentario  INT UNSIGNED NOT NULL AUTO_INCREMENT,
  texto          TEXT         NOT NULL,
  fecha          DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  id_post        INT UNSIGNED NOT NULL,
  id_usuario     INT UNSIGNED NOT NULL,
  PRIMARY KEY (id_comentario),
  FOREIGN KEY (id_post) REFERENCES post (id_post) ON DELETE CASCADE,
  FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario)
);

-- Likes. La clave de dos columnas impide dar dos likes al mismo post
CREATE TABLE megusta (
  id_post     INT UNSIGNED NOT NULL,
  id_usuario  INT UNSIGNED NOT NULL,
  PRIMARY KEY (id_post, id_usuario),
  FOREIGN KEY (id_post) REFERENCES post (id_post) ON DELETE CASCADE,
  FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario)
);

-- Likes de los comentarios. Igual que megusta pero apuntando al comentario
CREATE TABLE megusta_comentario (
  id_comentario  INT UNSIGNED NOT NULL,
  id_usuario     INT UNSIGNED NOT NULL,
  PRIMARY KEY (id_comentario, id_usuario),
  FOREIGN KEY (id_comentario) REFERENCES comentario (id_comentario) ON DELETE CASCADE,
  FOREIGN KEY (id_usuario) REFERENCES usuario (id_usuario)
);
