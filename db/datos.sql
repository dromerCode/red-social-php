-- Datos de prueba. Los dos usuarios tienen la contraseña 1234

USE redsocial;

INSERT INTO usuario (nombre, email, contrasena) VALUES
  ('Ana', 'ana@redsocial.test', '$2y$12$1iDFNcISWOGtuL7l4/IiPeV4N.SKcTbhKtluu.OtrY4QcNA5owATC'),
  ('Luis', 'luis@redsocial.test', '$2y$12$1iDFNcISWOGtuL7l4/IiPeV4N.SKcTbhKtluu.OtrY4QcNA5owATC');

INSERT INTO post (titulo, texto, id_usuario) VALUES
  ('Mi primer post', 'Hola a todos, este es mi primer post en la red social.', 1),
  ('Recomendación de libro', 'Me he leído El nombre del viento y me ha encantado.', 2),
  ('Fin de semana', 'Este finde me voy a la sierra, ya contaré qué tal.', 1);

INSERT INTO comentario (texto, id_post, id_usuario) VALUES
  ('¡Bienvenida!', 1, 2),
  ('Yo también me lo leí, muy bueno.', 2, 1),
  ('Gracias, a ver si me lo leo.', 2, 2);

INSERT INTO megusta (id_post, id_usuario) VALUES
  (1, 2),
  (2, 1),
  (2, 2);

INSERT INTO megusta_comentario (id_comentario, id_usuario) VALUES
  (1, 1),
  (2, 2);
