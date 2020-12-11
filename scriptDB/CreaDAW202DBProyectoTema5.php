<?php
        /**
            *@author: Nacho del Prado Losada
            *@since: 11/12/2020
        */ 
            
        require_once "../config/confDBPDO.php"; 
        
            try {
                $miDB = new PDO(DNS,USER,PASSWORD);
                $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                
                $sql = <<<EOD
                        CREATE TABLE IF NOT EXISTS Departamento(
                            CodDepartamento VARCHAR(3) PRIMARY KEY,
                            DescDepartamento VARCHAR(255) NOT NULL,
                            FechaCreacionDepartamento INT NOT NULL,
                            VolumenNegocio FLOAT NOT NULL,
                            FechaBajaDepartamento INT DEFAULT NULL
                        )ENGINE=INNODB;
                        CREATE TABLE IF NOT EXISTS Usuario(
                            CodUsuario VARCHAR(10) PRIMARY KEY,
                            Password VARCHAR(64) NOT NULL,
                            DescUsuario VARCHAR(255) NOT NULL,
                            NumConexiones INT DEFAULT 0,
                            FechaHoraUltimaConexion INT,
                            Perfil enum('administrador', 'usuario') DEFAULT 'usuario',
                            ImagenUsuario mediumblob NULL
                        )ENGINE=INNODB;
EOD;
                
                $miDB->exec($sql);
                
                echo "<h3> <span style='color: green;'>"."Tablas creadas correctamente</span></h3>";
            }
            catch (PDOException $excepcion) {
                $errorExcepcion = $excepcion->getCode();
                $mensajeExcepcion = $excepcion->getMessage();
                
                echo "<span style='color: red;'>Error: </span>".$mensajeExcepcion."<br>";
                echo "<span style='color: red;'>Código del error: </span>".$errorExcepcion;
            } finally {
                unset($miDB);
            }
?>