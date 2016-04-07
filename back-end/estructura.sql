# Seguir instrucciones iniciales en el `forro.sql`
# - Crear base de datos
# - Crear el usuario
# - Le brindamos privilegios al usuario

# use pr1newsdb;

# Creamos nuestra tabla de usuarios
CREATE TABLE noticias(
  id INT NOT NULL AUTO_INCREMENT,
  url VARCHAR(70) NOT NULL,
  titulo VARCHAR(255) NOT NULL,
  contenido VARCHAR(255),
  url_imagen VARCHAR(70),
  PRIMARY KEY (id)
);
