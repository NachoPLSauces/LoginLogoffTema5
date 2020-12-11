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
                        DROP TABLE Departamento;
                        DROP TABLE Usuario;
EOD;
                
                $miDB->exec($sql);
                
                echo "<h3> <span style='color: green;'>"."Tablas borrada</span></h3>";
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