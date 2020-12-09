<?php
/*
 * @author: Nacho del Prado Losada
 * @since: 30/11/2020
 * Descripción: Login.php permite controlar el acceso de los usuarios en la aplicación LoginLogoff
 */

//Si existe la cookie que almacena el último idioma elegido por el usuario se guarda en una variable
if(!isset($_COOKIE['idioma'])){
    setcookie("idioma", "es", time()+86400);
    header("Location: Login.php");
    exit;
}
//Cambiamos el idioma si el usuario elige uno distinto al actual
if(isset($_REQUEST['es'])){
    //Creación de una cookie que guarda la selección del idioma 'español'
    setcookie("idioma", $_REQUEST['es'], time()+86400);
    header("Location: Login.php");
    exit;
}
if(isset($_REQUEST['en'])){
    //Creación de una cookie que guarda la selección del idioma 'inglés'
    setcookie("idioma", $_REQUEST['en'], time()+86400);
    header("Location: Login.php");
    exit;
}
    
//Se guarda el valor de la cookie idioma en una variable
$idioma = $_COOKIE['idioma'];

//Llamada al fichero de almacenamiento de consantes en PDO
require_once '../config/confDBPDO.php';
$error = "";

//Comprobamos si el usuario ha elegido crear una cuenta
if(isset($_REQUEST['crear'])){
    header("Location: registro.php");
    exit;
}

//Comprobamos si se ha enviado el formulario
if(isset($_REQUEST['enviar'])){
    $usuario = $_REQUEST['usuario'];
    $password = $_REQUEST['password'];
    
    if($usuario == null || $password == null){
        $error = "Debes introducir un usuario y una contraseña";
    }
    else{
        try{
            //Instanciar un objeto PDO y establecer la conexión con la base de datos
            $miDB = new PDO(DSN, USER, PASSWORD);
            //Establecer PDO::ERRMODE_EXCEPTION como valor del atributo PDO::ATTR_ERRMODE
            $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            //Almaceno la consulta a sql en una variable
            $sql = "SELECT * FROM Usuario WHERE CodUsuario=:CodUsuario AND Password=:Password";
            //Ejecuto la consulta
            $consulta = $miDB->prepare($sql);
            $consulta->bindParam(":CodUsuario", $usuario);

            $passwordCodificado = hash("sha256", $usuario.$password);
            $consulta->bindParam(":Password", $passwordCodificado);
            $consulta->execute();

            $registro = $consulta->fetchObject();
            if($registro != null){
                //Establecemos como zona horaria la de Madrid
                date_default_timezone_set('Europe/Madrid'); 
                //Se guarda la última conexión
                $ultimaConexion = $registro->FechaHoraUltimaConexion; 
                
                //Se actualiza la última conexión registrada en la base de datos
                $sql2 = "UPDATE Usuario SET NumConexiones=NumConexiones+1, FechaHoraUltimaConexion=:fechaHoraUltimaConexion WHERE CodUsuario='{$usuario}'";
                $consulta2 = $miDB->prepare($sql2);
                $timestamp = time();
                $consulta2->bindParam(":fechaHoraUltimaConexion", $timestamp);
                $consulta2->execute(); 
                
                //Inicio de la sesión
                session_start();
                
                //Se guarda el código del usuario para comprobar si el usuario ha pasado por el Login al visualizar las demás páginas 
                $_SESSION['usuarioDAW202AppLoginLogoff'] = $registro->CodUsuario;    
                
                //Si no es la primera vez que el usuario se conecta, se guarda la última conexión
                if($ultimaConexion != null){
                    $_SESSION['fechaHoraUltimaConexionAnterior'] = $ultimaConexion;
                }  
                
                //Se dirige al usuario a Programa.php
                header('Location: ./Programa.php');
                exit;
            }
            else{
                //Si las creedenciales no son válidas se vuelven a pedir
                $error = "Usuario o contraseña incorrectos";
            }
        } catch (PDOException $pdoe){
            echo "<p style='color: red'>ERROR: " . $pdoe->getMessage() . "</p>";
        }
        unset($miDB);
    }
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
                        <h2>
                            <?php 
                            //Se muestra en el idioma guardado en la cookie 'idioma'
                            //Si no existe, se muestra en español
                            if($idioma=="en"){
                                echo 'Login';
                            }
                            elseif($idioma=="es"){
                                echo 'Iniciar sesión';
                            }
                            
                            ?>
                        </h2>
                    </div>

                    <div>
                        <label for='usuario'>
                            <?php 
                            //Se muestra en el idioma guardado en la cookie 'idioma'
                            if($idioma=="en"){
                                echo 'User ';
                            }
                            elseif($idioma=="es"){
                                echo 'Usuario ';
                            }
                            
                            ?> 
                        </label>
                        <input type='text' id='usuario' name='usuario'/>

                        <label for='password' >
                            <?php 
                            //Se muestra en el idioma guardado en la cookie 'idioma'
                            if($idioma=="en"){
                                echo 'Password ';
                            }
                            elseif($idioma=="es"){
                                echo 'Contraseña ';
                            }
                            
                            ?>
                        </label>
                        <input type='password' id="password" name='password'/>
                        
                        <span><?php echo $error; ?></span>
                        
                        <?php 
                        //Se muestra en el idioma guardado en la cookie 'idioma'
                        if($idioma=="en"){
                        ?>
                        <input class="enviar" type='submit' name='enviar' value='Login' />
                        <?php 
                        }
                        if($idioma=="es"){
                        ?>
                        <input class="enviar" type='submit' name='enviar' value='Iniciar sesión' />
                        <?php 
                        }
                        ?>
                        
                        <div class="idiomas">
                            <button type='submit' name='es' value="es"><img src="../doc/images/spain.png" width="30" height="30"></button>
                            <button type='submit' name='en' value="en"><img src="../doc/images/england.png" width="30" height="30"></button>
                        </div>
                        
                    </div>
                </fieldset>
                
                <div class="crearCuenta">
                    <p>¿Eres nuevo?</p>
                    <input class="enviar" type='submit' name='crear' value='Crea tu cuenta' />
                </div>
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
