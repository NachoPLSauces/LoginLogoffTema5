-- CREACION BASE DE DATOS
-- Creacion de la base de datos DAW202DBProyectoTema5
CREATE DATABASE if NOT EXISTS DAW202DBProyectoTema5;
-- Creacion de tablas de la base de datos
CREATE TABLE IF NOT EXISTS DAW202DBProyectoTema5.Departamento(
    CodDepartamento VARCHAR(3) PRIMARY KEY,
    DescDepartamento VARCHAR(255) NOT NULL,
    FechaCreacionDepartamento INT NOT NULL,
    VolumenNegocio FLOAT NOT NULL,
    FechaBajaDepartamento INT DEFAULT NULL
)ENGINE=INNODB;

CREATE TABLE IF NOT EXISTS DAW202DBProyectoTema5.Usuario(
    CodUsuario VARCHAR(10) PRIMARY KEY,
    Password VARCHAR(64) NOT NULL,
    DescUsuario VARCHAR(255) NOT NULL,
    NumConexiones INT DEFAULT 0,
    FechaHoraUltimaConexion INT,
    Perfil enum('administrador', 'usuario') DEFAULT 'usuario',
    ImagenUsuario mediumblob NULL
)ENGINE=INNODB;

-- CREACION USUARIO ADMINISTRADOR
-- Creacion de usuario administrador de la base de datos: usuarioDAW215DBDepartamentos / paso
CREATE USER 'usuarioDAW202DBProyectoTema5'@'%' IDENTIFIED BY 'P@ssw0rd';
-- Permisos para la base de datos
GRANT ALL PRIVILEGES ON DAW202DBProyectoTema5.* TO 'usuarioDAW202DBProyectoTema5'@'%';