-- Datos de prueba. Los dos usuarios tienen la contraseña 1234

USE redsocial;

INSERT INTO usuario (nombre, email, contrasena) VALUES
  ('Ana', 'ana@redsocial.test', '$2y$12$1iDFNcISWOGtuL7l4/IiPeV4N.SKcTbhKtluu.OtrY4QcNA5owATC'),
  ('Luis', 'luis@redsocial.test', '$2y$12$1iDFNcISWOGtuL7l4/IiPeV4N.SKcTbhKtluu.OtrY4QcNA5owATC');
