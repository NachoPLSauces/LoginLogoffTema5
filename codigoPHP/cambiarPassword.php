<?php
/*
 * @author: Nacho del Prado Losada
 * @since: 07/01/2021
 * Descripción: cambiarPassword.php permite cambiar la contraseña
 */

//Llamada al fichero de almacenamiento de consantes en PDO
require_once '../config/confDBPDO.php';
require_once '../core/201020libreriaValidacion.php';

// Recuperamos la información de la sesión
session_start();

//Se comprueba que el usuario se haya autentificado
if (!isset($_SESSION['usuarioDAW202AppLoginLogoff'])) {
    header('Location: ./Login.php');
    exit;
}

//Si el usuario pulsa "Cancelar" le dirijo a Programa.php
if(isset($_REQUEST['cancelar'])){
    header("Location: ./Programa.php");
    exit;
}
//Si el usuario pulsa "Cerrar sesión" le dirijo al Login
if(isset($_REQUEST['salir'])){
    session_destroy();
    header("Location: ./Login.php");
    exit;
}

//Array de errores inicializado a null
$aErrores = ["passwordActual" => null,
             "passwordNuevo" => null,
             "confirmarPassword" => null];

//Variable obligatorio inicializada a 1
define("OBLIGATORIO", 1);

//Varible de entrada correcta inicializada a true
$entradaOK = true;           

//Si el usuario pulsa "Cambiar contraseña" se comprueba si se puede cambiar la contraseña
if(isset($_REQUEST['cambiar'])){
    //Se comprueba si el campo passwordActual corresponde a la contraseña actual
    try{
        //Instanciar un objeto PDO y establecer la conexión con la base de datos
        $miDB = new PDO(DSN, USER, PASSWORD);
        //Establecer PDO::ERRMODE_EXCEPTION como valor del atributo PDO::ATTR_ERRMODE
        $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //Almaceno la consulta a sql en una variable
        $sql = "SELECT Password FROM Usuario WHERE CodUsuario=:CodUsuario";
        //Ejecuto la consulta
        $consulta = $miDB->prepare($sql);
        $consulta->bindParam(":CodUsuario", $_SESSION['usuarioDAW202AppLoginLogoff']);
        $consulta->execute();

        $registro = $consulta->fetchObject();
        if(hash("sha256", $_SESSION['usuarioDAW202AppLoginLogoff'].$_REQUEST['passwordActual']) != $registro->Password){
            //Si las contraseñas no son iguales se almacena el error
            $aErrores["passwordActual"] = "Contraseña incorrecta";
        }
    } catch (PDOException $pdoe){
        echo "<p style='color: red'>ERROR: " . $pdoe->getMessage() . "</p>";
    }
    unset($miDB);
    
    //Comprobar que el campo password se ha rellenado con una contraseña válida
    $aErrores["passwordNuevo"] = validacionFormularios::validarPassword($_REQUEST['passwordNuevo'], 5, OBLIGATORIO);
    //Comprobar que el campo password se ha rellenado con una contraseña válida
    $aErrores["confirmarPassword"] = validacionFormularios::confirmarPassword($_REQUEST['confirmarPassword'], $_REQUEST['passwordNuevo'], OBLIGATORIO);
                
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
    try{
        //Instanciar un objeto PDO y establecer la conexión con la base de datos
        $miDB = new PDO(DSN, USER, PASSWORD);
        //Establecer PDO::ERRMODE_EXCEPTION como valor del atributo PDO::ATTR_ERRMODE
        $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //Almaceno la consulta a sql en una variable
        $sql = "UPDATE Usuario SET Password=:Password WHERE CodUsuario=:CodUsuario";

        //Ejecuto la consulta
        $consulta = $miDB->prepare($sql);
        $consulta->bindParam(":CodUsuario", $_SESSION['usuarioDAW202AppLoginLogoff']);
        $consulta->bindParam(":Password", hash("sha256", $_SESSION['usuarioDAW202AppLoginLogoff'].$_REQUEST['passwordNuevo']));
        $consulta->execute();

        //Se dirige al usuario a editarPerfil.php
        header('Location: ./editarPerfil.php');
        exit;
    }catch (PDOException $pdoe){
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
                        <h2>Cambiar contraseña</h2>
                    </div>

                    <div>
                        <label for='passwordActual'>Contraseña actual </label><br>
                        <span style="color:red">
                            <?php
                            //Imprime el error en el caso de que se introduzca mal la contraseña
                            if($aErrores["passwordActual"] != null){
                                echo $aErrores['passwordActual'];
                            }
                            ?> 
                        </span>
                        
                        <input type='password' id="passwordActual" name='passwordActual' value="<?php 
                            //Devuelve la contraseña actual si se había introducido correctamente
                            if(isset($_REQUEST['passwordActual']) && $aErrores["passwordActual"] == null){
                                echo $_REQUEST['passwordActual'];
                            }
                        ?>"/>
                        
                        <label for='passwordNuevo' >Contraseña nueva </label><br>
                        <span style="color:red">
                            <?php
                            //Imprime el error en el caso de que se introduzca una contraseña no válida
                            if($aErrores["passwordNuevo"] != null){
                                echo $aErrores['passwordNuevo'];
                            }
                            ?> 
                        </span>
                        
                        <input type='password' id="passwordNuevo" name='passwordNuevo' value="<?php 
                            //Devuelve la contraseña si era válida
                            if(isset($_REQUEST['passwordNuevo']) && $aErrores["passwordNuevo"] == null){
                                echo $_REQUEST['passwordNuevo'];
                            }
                        ?>"/>
                        
                        <label for='confirmarPassword' >Confirmar contraseña </label><br>
                        <span style="color:red">
                            <?php
                            //Imprime el error en el caso de que las contraseñas no sean iguales
                            if($aErrores["confirmarPassword"] != null){
                                echo $aErrores['confirmarPassword'];
                            }
                            ?> 
                        </span>
                        
                        <input type='password' id="confirmarPassword" name='confirmarPassword' value="<?php 
                            //Devuelve la contraseña si se había introducido correctamente
                            if(isset($_REQUEST['confirmarPassword']) && $aErrores["confirmarPassword"] == null){
                                echo $_REQUEST['confirmarPassword'];
                            }
                        ?>"/>
                    </div>
                    
                    <div>
                        <input class="enviar" type="submit" name="cambiar" value="Cambiar contraseña"/>
                        <input class="enviar" type="submit" name="cancelar" value="Cancelar"/>
                        <input class="enviar" type="submit" name="salir" value="Cerrar sesión"/>
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
