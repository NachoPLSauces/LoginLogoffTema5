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
                        <h2>Bienvenido <?php echo $_SESSION['DescUsuario']; ?></h2>
                    </div>

                    <div>
                        <input class="enviar" type="submit" name="detalle" value="Detalle"/>
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
    
    <body>
             
    </body>
</html>
