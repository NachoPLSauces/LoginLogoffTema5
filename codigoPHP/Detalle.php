<!DOCTYPE html>
<html>
    <head>
        <title>Ejercicios Tema 5</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <style>
            h2{
                text-align: center;
                margin: 1em;
            }
            
            table{
                border-collapse: collapse;
                border: 0;
                width: 934px;
                box-shadow: 1px 2px 3px #ccc; 
                color: #222;
                font-family: sans-serif;
                margin: 1em auto;
                text-align: left;
            }
            td, th {
                border: 1px solid #666;
                font-size: 75%;
                vertical-align: baseline;
                padding: 4px 5px;
            }
            tr th{
                background-color: #99c;
                font-weight: bold;
                text-align: center;
                position: static;
            }
            td{
                background-color: #ddd;
                max-width: 300px;
                overflow-x: auto;
                word-wrap: break-word;
            }
            tr td:first-of-type{
                background-color: #ccf;
                width: 300px;
                font-weight: bold;
            }
            
            .flecha{
                position: fixed;
                top: 15px;
                left: 15px;
            }
        </style>
    </head>
    
    <body>
        <?php
            /*
             * @author: Nacho del Prado Losada
             * @since: 01/12/2020
             * Descripción: Detalle.php se muestra cuando un usuario identificado pulsa el botón Detalle en Programa.php
             */
             session_start();
        ?>

        <h2>Variables superglobales</h2>
        <table>
            <tr>
                <th colspan="2">Contenido de \$_COOKIE</th>
            </tr>
            <?php
            foreach ($_COOKIE as $clave => $valor){
            print_r("<tr><td>".$clave . "</td><td>" . $valor . "</td></tr>");
            }
            ?>
            
            <tr>
                <th colspan="2">Contenido de \$_SESSION</th>
            </tr>
            <?php
            if(!empty($_SESSION)){
                foreach ($_SESSION as $clave => $valor){
                    print_r("<tr><td>".$clave . "</td><td>" . $valor . "</td></tr>");
                }
            }
            ?>
            
            <tr>
                <th colspan="2">Contenido de \$_ENV</th>
            </tr>
            <?php
            foreach ($_ENV as $clave => $valor){
                print_r("<tr><td>".$clave . "</td><td>" . $valor . "</td></tr>");
            }
            ?>
            
            <tr>
                <th colspan="2">Contenido de \$_FILES</th>
            </tr>
            <?php
            foreach ($_FILES as $clave => $valor){
                print_r("<tr><td>".$clave . "</td><td>" . $valor . "</td></tr>");
            }
            ?>
            
            <tr>
                <th colspan="2">Contenido de \$_GET</th>
            </tr>
            <?php
            foreach ($_GET as $clave => $valor){
                print_r("<tr><td>".$clave . "</td><td>" . $valor . "</td></tr>");
            }
            ?>
            
            <tr>
                <th colspan="2">Contenido de \$_POST</th>
            </tr>
            <?php
            foreach ($_POST as $clave => $valor){
                print_r("<tr><td>".$clave . "</td><td>" . $valor . "</td></tr>");
            }
            ?>
            
            <tr>
                <th colspan="2">Contenido de \$_REQUEST</th>
            </tr>
            <?php
            foreach ($_REQUEST as $clave => $valor){
                print_r("<tr><td>".$clave . "</td><td>" . $valor . "</td></tr>");
            }
            ?>
            
            <tr>
                <th colspan="2">Contenido de \$_SERVER</th>
            </tr>
            <?php
            foreach ($_SERVER as $clave => $valor){
                print_r("<tr><td>".$clave . "</td><td>" . $valor . "</td></tr>");
            }
            ?>
        </table>
        <br>
        <?php
            phpinfo();
        ?>
        
        <a href="Programa.php"><img class="flecha" src="../doc/images/flecha.png" height="30px"></a>
    </body>
</html>


