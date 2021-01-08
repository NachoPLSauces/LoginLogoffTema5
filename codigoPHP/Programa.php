<?php
/*
 * @author: Nacho del Prado Losada
 * @since: 06/01/2021
 * Descripción: Programa.php se muestra una vez el usuario se ha identificado correctamente
 */
//Llamada al fichero de almacenamiento de consantes en PDO
require_once '../config/confDBPDO.php';

// Recuperamos la información de la sesión
session_start();

//Se comprueba que el usuario se haya autentificado, en caso negativo, se le dirige al Login
if (!isset($_SESSION['usuarioDAW202AppLoginLogoff'])) {
    header('Location: ./Login.php');
}

//Si no existe la cookie que almacena el último idioma elegido por el usuario, se le da el valor "es"
if(!isset($_COOKIE['idioma'])){
    setcookie("idioma", "es", time()+86400);
    header("Location: Programa.php");
    exit;
}

if (isset($_REQUEST['salir'])) { //Si el usuario pulsa "Salir" se le dirige al Login
    session_destroy();
    header('Location: ./Login.php'); 
    exit;
} else if (isset($_REQUEST['editar'])){ //Si el usuario pulsa Editar se le dirige a editarPerfil
    header('Location: ./editarPerfil.php'); 
    exit;
} else if (isset($_REQUEST['detalle'])) { //Si el usuario pulsa "Detalle" se muestran las variables superglobales y phpinfo
    header('Location: ./Detalle.php'); 
    exit;
}

try{
    //Instanciar un objeto PDO y establecer la conexión con la base de datos
    $miDB = new PDO(DSN, USER, PASSWORD);
    //Establecer PDO::ERRMODE_EXCEPTION como valor del atributo PDO::ATTR_ERRMODE
    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    //Almaceno la consulta a sql en una variable
    $sql = "SELECT * FROM Usuario WHERE CodUsuario=:CodUsuario";
    //Ejecuto la consulta
    $consulta = $miDB->prepare($sql);
    
    $consulta->bindParam(":CodUsuario", $_SESSION['usuarioDAW202AppLoginLogoff']);
    $consulta->execute();
    
    $registro = $consulta->fetchObject();
    
    //Variables que guardan información del usuario
    $descripcionUsuario = $registro->DescUsuario;
    $numConexiones = $registro->NumConexiones;
    
   
}catch (PDOException $pdoe){
    echo "<p style='color: red'>ERROR: " . $pdoe->getMessage() . "</p>";
}
unset($miDB); //Finaliza la conexión con la base de datos
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
            <form name="input" action="<?php $_SERVER['PHP_SELF']?>" method="post">
                <fieldset>
                    <div>
                        <h2>
                        <?php 
                        ///Si el idioma elegido es 'español'
                        if($_COOKIE['idioma']=="es"){
                            echo 'Bienvenido ';
                        }
                        //Si el idioma elegido es 'inglés'
                        elseif($_COOKIE['idioma']=="en"){
                            echo 'Welcome ';
                        }
                        echo $descripcionUsuario; ?>
                        </h2>
                    </div>
                    
                    <div>
                        <p>Número de conexiones: <?php echo $numConexiones; ?></p>
                        <?php if(!empty($_SESSION['fechaHoraUltimaConexionAnterior'])){?>
                        <p>Última conexión: <?php echo date("d-m-Y H:i:s", $_SESSION['fechaHoraUltimaConexionAnterior']); ?></p>
                        <?php } ?>
                    </div>
                    
                    <div>
                        <input class="enviar" type="submit" name="detalle" value="Detalle"/>
                        <input class="enviar" type="submit" name="editar" value="Editar perfil"/>
                        <input class="enviar" type="submit" name="salir" value="Salir"/>
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
