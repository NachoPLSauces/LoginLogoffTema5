<?php
/*
 * @author: Nacho del Prado Losada
 * @since: 30/11/2020
 * Descripción: Login.php permite controlar el acceso de los usuarios en la aplicación LoginLogoff
 */

//Llamada al fichero de almacenamiento de consantes en PDO
require_once '../config/confDBPDO.php';
$error = "";

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
                session_start();
                $_SESSION['usuarioDAW202AppLoginLogoff'] = $registro->CodUsuario;
                $_SESSION['DescUsuario'] = $registro->DescUsuario;
                
                header("Location: Programa.php");
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
    </head>
    
    <body>
        <form name="input" action="<?php $_SERVER['PHP_SELF']?>" method="post">
            <fieldset>
                <legend>Login</legend>
                <div>
                    <span style="color: red"><?php echo $error; ?></span>
                </div>
                
                <div>
                    <label for='usuario'>Usuario: </label><br/>
                    <input type='text' id='usuario' name='usuario'/><br/>
                
                    <label for='password' >Contraseña: </label><br/>
                    <input type='password' id="password" name='password'/><br/>
                
                    <input type='submit' name='enviar' value='Enviar' />
                </div>
            </fieldset>
        </form>
        
        
    </body>
</html>