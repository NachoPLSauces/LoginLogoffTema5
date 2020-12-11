<?php
/*
 * @author: Nacho del Prado Losada
 * @since: 09/12/2020
 * Descripción: registro.php permite crear usuarios en la aplicación LoginLogoff
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

//Almaceno los datos del usuario en variables para mostrarlos en el formulario
try{
    //Instanciar un objeto PDO y establecer la conexión con la base de datos
    $miDB = new PDO(DSN, USER, PASSWORD);
    //Establecer PDO::ERRMODE_EXCEPTION como valor del atributo PDO::ATTR_ERRMODE
    $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    //Almaceno la consulta a sql en una variable
    $sql = "SELECT DescUsuario FROM Usuario WHERE CodUsuario=:CodUsuario";

    //Ejecuto la consulta
    $consulta = $miDB->prepare($sql);
    $consulta->bindParam(":CodUsuario", $_SESSION['usuarioDAW202AppLoginLogoff']);
    $consulta->execute();
    
    $oUsuario = $consulta->fetchObject();
    
    //Almaceno en una variable la descripción actual del usuario
    $descUsuario = $oUsuario->DescUsuario; 

} catch (PDOException $pdoe) { 
    echo "<p style='color: red'>ERROR: " . $pdoe->getMessage() . "</p>";
}  
unset($miDB);
            
//Array de errores inicializado a null
$aErrores = ["descripcion" => null];

//Variable obligatorio inicializada a 1
define("OBLIGATORIO", 1);

//Varible de entrada correcta inicializada a true
$entradaOK = true;           

//Array de respuestas inicializado a null
$aRespuestas = ["descripcion" => null];

if(isset($_REQUEST['editar'])){
    //Comprobar que el campo descripción se ha rellenado con alfanuméricos
    $aErrores["descripcion"] = validacionFormularios::comprobarAlfaNumerico($_REQUEST['descripcion'], 200, 1, OBLIGATORIO);
    
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
    $aRespuestas = ["descripcion" => $_REQUEST['descripcion']];

    try{
        //Instanciar un objeto PDO y establecer la conexión con la base de datos
        $miDB = new PDO(DSN, USER, PASSWORD);
        //Establecer PDO::ERRMODE_EXCEPTION como valor del atributo PDO::ATTR_ERRMODE
        $miDB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //Almaceno la consulta a sql en una variable
        $sql = "UPDATE Usuario SET DescUsuario=:DescUsuario WHERE CodUsuario=:CodUsuario";

        //Ejecuto la consulta
        $consulta = $miDB->prepare($sql);
        $consulta->bindParam(":CodUsuario", $_SESSION['usuarioDAW202AppLoginLogoff']);
        $consulta->bindParam(":DescUsuario", $aRespuestas['descripcion']);
        $consulta->execute();

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
            <form name="input" action="<?php $_SERVER['PHP_SELF']?>" method="post">
                <fieldset>
                    <div>
                        <h2>Editar perfil</h2>
                    </div>
                    
                    <div>
                        <label for='usuario'>Usuario </label><br>
                        <input class="readonly" type='text' id='usuario' name='usuario' value="<?php echo $_SESSION['usuarioDAW202AppLoginLogoff']; ?>" readonly/>
                    
                        <label for='descripcion' >Descripción </label><br>
                        <span style="color:red">
                            <?php
                            //Imprime el error en el caso de que se introduzca mal el nombre
                            if($aErrores["descripcion"] != null){
                                echo $aErrores['descripcion'];
                            }
                            ?> 
                        </span>
                        <input type='text' id="descripcion" name='descripcion' value="<?php echo $descUsuario; ?>"/>
                    </div>
                    
                    <div>
                        <input class="enviar" type="submit" name="editar" value="Editar campos"/>
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
    
    <body>
             
    </body>
</html>
