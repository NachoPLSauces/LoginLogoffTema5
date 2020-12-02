<?php
/*
 * @author: Nacho del Prado Losada
 * @since: 01/12/2020
 * Descripción: Programa.php se muestra una vez el usuario se ha identificado correctamente
 */

// Recuperamos la información de la sesión
session_start();

//Si no ha introducido unas creedenciales validas no se muestra la página
//Se comprueba que el usuario se haya autentificado
if (!isset($_SESSION['usuarioDAW202AppLoginLogoff'])) {
    header('Location: ./Login.php');
}

//Si pulsa el botón Salir se le dirige al índice del Tema 5 y se cierra la sesión
//Si pulsa el botón Detalle se le dirige al ejercicio00
if (isset($_REQUEST['salir'])) {
    session_destroy();
    header('Location: ../../proyectoDWES/indexProyectoDWES.php');
    
} else if (isset($_REQUEST['detalle'])) {
    header('Location: ./Detalle.php');
}
?> 

<!DOCTYPE html>
<html>
    <head>
        <title>Ejercicios Tema 5</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
    </head>
    
    <body>
        <form>
            <h3>Bienvenido <?php echo $_SESSION['DescUsuario']; ?></h3>
            <form name="input" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <input type="submit" name="detalle" value="Detalle"/>
                <input type="submit" name="salir" value="Salir"/>
            </form> 
        </form>      
    </body>
</html>
