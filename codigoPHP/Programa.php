<?php
/*
 * @author: Nacho del Prado Losada
 * @since: 01/12/2020
 * Descripción: Programa.php se muestra una vez el usuario se ha identificado correctamente
 */
//Llamada al fichero de almacenamiento de consantes en PDO
require_once '../config/confDBPDO.php';

// Recuperamos la información de la sesión
session_start();

//Si no ha introducido unas creedenciales validas no se muestra la página
//Se comprueba que el usuario se haya autentificado
if (!isset($_SESSION['usuarioDAW202AppLoginLogoff'])) {
    header('Location: ./Login.php');
}

//Si existe la cookie que almacena el último idioma elegido por el usuario se guarda en una variable
if(!isset($_COOKIE['idioma'])){
    setcookie("idioma", "es", time()+86400);
    header("Location: Programa.php");
    exit;
}
//Cambiamos el idioma si el usuario elige uno distinto al actual
if(isset($_REQUEST['es'])){
    //Creación de una cookie que guarda la selección del idioma 'español'
    setcookie("idioma", $_REQUEST['es'], time()+86400);
    header("Location: Programa.php");
    exit;
}
if(isset($_REQUEST['en'])){
    //Creación de una cookie que guarda la selección del idioma 'inglés'
    setcookie("idioma", $_REQUEST['en'], time()+86400);
    header("Location: Programa.php");
    exit;
}
    
//Se guarda el valor de la cookie idioma en una variable
$idioma = $_COOKIE['idioma'];

//Si pulsa el botón Salir se le dirige al índice del Tema 5 y se cierra la sesión
//Si pulsa el botón Detalle se le dirige al ejercicio00
if (isset($_REQUEST['salir'])) {
    session_destroy();
    header('Location: ../../proyectoDWES/indexProyectoDWES.php');
    
} else if (isset($_REQUEST['detalle'])) {
    header('Location: ./Detalle.php');
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
    
    $usuario = $_SESSION['usuarioDAW202AppLoginLogoff'];
    $consulta->bindParam(":CodUsuario", $usuario);
    $consulta->execute();
    
    $registro = $consulta->fetchObject();
    
    $descripcionUsuario = $registro->DescUsuario;
    $numConexiones = $registro->NumConexiones;
    
    
}catch (PDOException $pdoe){
    echo "<p style='color: red'>ERROR: " . $pdoe->getMessage() . "</p>";
}
unset($miDB);
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
                    <?php 
                    ///Si el idioma elegido es 'español'
                    if($idioma=="es"){
                    ?> 
                    <div>
                        <h2>Bienvenido <?php echo $descripcionUsuario; ?></h2>
                    </div>
                    
                    <div>
                        <p>Número de conexiones: <?php echo $numConexiones; ?></p>
                        <?php if(!empty($_SESSION['fechaHoraUltimaConexionAnterior'])){?>
                        <p>Última conexión: <?php echo date("d-m-Y H:i:s", $_SESSION['fechaHoraUltimaConexionAnterior']); ?></p>
                        <?php } ?>
                    </div>
                    
                    <div>
                        <input class="enviar" type="submit" name="detalle" value="Detalle"/>
                        <input class="enviar" type="submit" name="salir" value="Salir"/>
                    </div>
                    <?php 
                    }
                    //Si el idioma elegido es 'inglés'
                    if($idioma=="en"){
                    ?> 
                    <div>
                        <h2>Welcome <?php echo $descripcionUsuario; ?></h2>
                    </div>
                    
                    <div>
                        <p>Number of connections: <?php echo $numConexiones; ?></p>
                        <?php if(!empty($_SESSION['fechaHoraUltimaConexionAnterior'])){?>
                        <p>Last connection: <?php echo date("d-m-Y H:i:s", $_SESSION['fechaHoraUltimaConexionAnterior']); ?></p>
                        <?php } ?>
                    </div>
                    
                    <div>
                        <input class="enviar" type="submit" name="detalle" value="Details"/>
                        <input class="enviar" type="submit" name="salir" value="Logoff"/>
                    </div>
                    <?php } ?>
                    
                    <div class="idiomas">
                        <button type='submit' name='es' value="es"><img src="../doc/images/spain.png" width="30" height="30"></button>
                        <button type='submit' name='en' value="en"><img src="../doc/images/england.png" width="30" height="30"></button>
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
    
    <body>
             
    </body>
</html>
