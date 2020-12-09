<?php
/*
 * @author: Nacho del Prado Losada
 * @since: 09/12/2020
 * Descripción: registro.php permite crear usuarios en la aplicación LoginLogoff
 */

//Si el usuario pulsa "Salir" le dirijo al Login
if(isset($_REQUEST['salir'])){
    header("Location: ./Login.php");
    exit;
}

//Llamada al fichero de almacenamiento de consantes en PDO
require_once '../config/confDBPDO.php';
require_once '../core/201020libreriaValidacion.php';
            
//Array de errores inicializado a null
$aErrores = ["usuario" => null,
             "descripcion" => null,
             "password" => null];

//Variable obligatorio inicializada a 1
define("OBLIGATORIO", 1);

//Varible de entrada correcta inicializada a true
$entradaOK = true;           

//Array de respuestas inicializado a null
$aRespuestas = ["usuario" => null,
                "descripcion" => null,
                "password" => null];

if(isset($_REQUEST['enviar'])){
    //Comprobar que el campo nombre se ha rellenado con alfabéticos
    $aErrores["usuario"] = validacionFormularios::comprobarAlfabetico($_REQUEST['usuario'], 15, 3, OBLIGATORIO);
    
    if($aErrores["usuario" == null]){
        try{
            //Instanciar un objeto PDO y establecer la conexión con la base de datos
            $miDB = new PDO(DSN, USER, PASSWORD);
            //Establecer PDO::ERRMODE_EXCEPTION como valor del atributo PDO::ATTR_ERRMODE
            $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            //Almaceno la consulta a sql en una variable
            $sql = "SELECT CodUsuario FROM Usuario WHERE CodUsuario=:CodUsuario";
            
            //Ejecuto la consulta
            $consulta = $miDB->prepare($sql);
            $consulta->bindParam(":CodUsuario", $_REQUEST['usuario']);
            $consulta->execute();

            $registro = $consulta->fetchObject();
            if($registro != null){
                $aErrores['usuario'] = "El nombre introducido está en uso";
            }
        } catch (PDOException $pdoe){
            echo "<p style='color: red'>ERROR: " . $pdoe->getMessage() . "</p>";
        }
        unset($miDB);
    }  
        
    //Comprobar que el campo apellido1 se ha rellenado con alfabéticos
    $aErrores["descripcion"] = validacionFormularios::comprobarAlfaNumerico($_REQUEST['descripcion'], 200, 1, OBLIGATORIO);
    //Comprobar que el campo apellido2 se ha rellenado con alfabéticos
    $aErrores["password"] = validacionFormularios::validarPassword($_REQUEST['password'], 1, OBLIGATORIO);
    
    //Comprobar si algún campo del array de errores ha sido rellenado
    foreach ($aErrores as $clave => $valor) {
        //Comprobar si el campo ha sido rellenado
        if($valor!=null){
            $_REQUEST[$clave] = "";
            $entradaOK = false;
        }
    }

}
else{
    $entradaOK = false;
}

if($entradaOK){
    //Si los datos han sido introducidos correctamente
    $aRespuestas = ["usuario" => $_REQUEST['usuario'],
                    "descripcion" => $_REQUEST['descripcion'],
                    "password" => $_REQUEST['password']];

    try{
        //Establecemos como zona horaria la de Madrid
        date_default_timezone_set('Europe/Madrid'); 
                
        //Instanciar un objeto PDO y establecer la conexión con la base de datos
        $miDB = new PDO(DSN, USER, PASSWORD);
        //Establecer PDO::ERRMODE_EXCEPTION como valor del atributo PDO::ATTR_ERRMODE
        $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //Almaceno la consulta a sql en una variable
        $sql = "INSERT INTO Usuario (CodUsuario, DescUsuario, Password, NumConexiones, FechaHoraUltimaConexion) VALUES (:CodUsuario, :DescUsuario, :Password, 1, :FechaHoraUltimaConexion)";

        //Ejecuto la consulta
        $consulta = $miDB->prepare($sql);
        $consulta->bindParam(":CodUsuario", $aRespuestas['usuario']);
        $consulta->bindParam(":DescUsuario", $aRespuestas['descripcion']);
        $consulta->bindParam(":Password", $aRespuestas['password']);
        $consulta->bindParam(":FechaHoraUltimaConexion", time());
        $consulta->execute();

        //Inicio de la sesión
        session_start();

        //Se guarda el código del usuario para comprobar si el usuario ha pasado por el Login al visualizar las demás páginas 
        $_SESSION['usuarioDAW202AppLoginLogoff'] = $registro->CodUsuario;    
        
        $_SESSION['fechaHoraUltimaConexionAnterior'] = null;

        //Se dirige al usuario a Programa.php
        header('Location: ./Programa.php');
        exit;
    } catch (PDOException $pdoe){
        echo "<p style='color: red'>ERROR: " . $pdoe->getMessage() . "</p>";
    }
    unset($miDB);
}
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Ejercicios Tema 5</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../webroot/css/estilo.css">
    </head>
    
    <body>
        <header>
            <div class="title">
                <h1>Login Logoff</h1>				
            </div>
        </header>
        
        <main>
            <div class="title">
                <div class="logo">
                    <img src="../doc/images/logo.png" alt="logo">
                </div>

                <div>
                    <h2><a href="../../proyectoDWES/indexProyectoDWES.php">Proyecto DWES</a></h2>
                </div>				
            </div>
            
            <form name="input" action="<?php $_SERVER['PHP_SELF']?>" method="post">
                <fieldset>
                    <div>
                        <h2>Crear cuenta</h2>
                    </div>

                    <div>
                        <label for='usuario'>Usuario </label><br>
                        
                        <span style="color:red">
                            <?php
                            //Imprime el error en el caso de que se introduzca mal el nombre
                            if($aErrores["usuario"] != null){
                                echo $aErrores['usuario'];
                            }
                            ?> 
                        </span>
                        
                        <input type='text' id='usuario' name='usuario' value="<?php 
                            //Devuelve el campo nombre si se había introducido correctamente
                            if(isset($_REQUEST['usuario']) && $aErrores["usuario"] == null){
                                echo $_REQUEST['usuario'];
                            }
                        ?>"/>
                    
                        

                        <label for='descripcion' >Descripción </label><br>
                    
                        <span style="color:red">
                            <?php
                            //Imprime el error en el caso de que se introduzca mal el nombre
                            if($aErrores["descripcion"] != null){
                                echo $aErrores['descripcion'];
                            }
                            ?> 
                        </span>
                        
                        <input type='text' id="descripcion" name='descripcion' value="<?php 
                            //Devuelve el campo nombre si se había introducido correctamente
                            if(isset($_REQUEST['descripcion']) && $aErrores["descripcion"] == null){
                                echo $_REQUEST['descripcion'];
                            }
                        ?>"/>
                        
                        <label for='password' >Contraseña </label><br>
                    
                        <span style="color:red">
                            <?php
                            //Imprime el error en el caso de que se introduzca mal el nombre
                            if($aErrores["password"] != null){
                                echo $aErrores['password'];
                            }
                            ?> 
                        </span>
                        
                        <input type='password' id="password" name='password' value="<?php 
                            //Devuelve el campo nombre si se había introducido correctamente
                            if(isset($_REQUEST['password']) && $aErrores["password"] == null){
                                echo $_REQUEST['password'];
                            }
                        ?>"/>
                        
                        <input class="enviar" type='submit' name='enviar' value='Crear cuenta' />
                        
                        <input class="enviar" type='submit' name='salir' value='Salir' />
                    </div>
                </fieldset>
            </form>
        </main>
        
        <footer>
            <div class="enlaces">
                <a href="https://github.com/NachoPLSauces" target="_blank"><img src="../doc/images/github-icon.png" alt="github"></a>
                <a href="http://daw202.ieslossauces.es/" target="_blank"><img src="../doc/images/1and1-icon.png" alt="github"></a>
            </div>
            <div class="nombre">
                <h3>Nacho del Prado Losada</h3>
                <h3>ignacio.pralos@educa.jcyl.es</h3>
            </div>
        </footer>
    </body>
</html>