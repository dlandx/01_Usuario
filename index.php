<?php 
    // De cada Familia (en un select) mostrar todos los productos...
    spl_autoload_register(function ($clase) {
        require "$clase.php";
    });
    $usersBD = null; 
    $nameColumn = null; 
    $view = false; // Mostrar tabla de usuarios   
    $error = isset($_GET['info']) ? $_GET['info'] : null;
    
    // Instanciar clase BD -> contendra la conexion con la BBDD...
    $bd = new BD("localhost","root","");
    
    // Funcion caracteres(...) -> Añadir caracteres especiales 'los escapa'...
    $user = $bd->caracteres(filter_input(INPUT_POST, 'user')); // Login obtener user
    $pass = $bd->caracteres(filter_input(INPUT_POST, 'pass')); // Login obtener pass

    // Instanciar clase Funcion -> contendra validacion login, resultado para la tabla...
    $tabla = new Funcion();

    
    switch (filter_input(INPUT_POST, 'btn')) {
        case "Insertar":
            // Funcion validarLogin(...) Informa si ha insertado datos en los campos del login
            $error = $tabla->validarLogin($user, $pass);
            if ($error === false){ // Ha ingresado bien los datos en el login...
                $passEncrypt = md5("$pass"); // Cifrar la contraseña en MD5...
                $sql = "INSERT INTO usuarios (nombre, password) VALUES ('$user', '$passEncrypt')";
                // Funcion insert(...) -> Realizar sentencia SQL en BBDD y obtener el resultado (OK | Error)
                $error = $bd->insert($sql); // OK -> Usuario insertado | Error -> Informar del error...
            }
            break;
        
        case "Mostrar":
            // Funcion validarLogin(...) Informa si ha insertado datos en los campos del login
            $error = $tabla->validarLogin($user, $pass);
            if ($error === false){   
                // Validamos que el usuario ingresado esta en la BBDD...
                $error = $bd->existe($user, $pass);                
                if ($error === false){ // Si esta en la BD mostramos en la tabla las tuplas BD...
                    // Obtenemos el nombre de las columnas de la tabla de la BBDD...
                    $sqlNameCol="SHOW COLUMNS FROM usuarios";
                    $nameColumn = $bd->select($sqlNameCol);
                    //$nameColumn = $bd->nombres_campos("usuarios"); // Cambiar Funcion->tableHead->For values[0] por values

                    $sql = "SELECT * FROM usuarios"; // Obtener todos los usuarios de la BBDD...
                    $usersBD = $bd->select($sql);                

                    $view = true; // Mostrar la tabla...
                }
            }
            break;
        
        case "Acceder":
            // Funcion validarLogin(...) Informa si ha insertado datos en los campos del login
            $error = $tabla->validarLogin($user, $pass);
            if ($error === false){
                // Validamos que el usuario ingresado esta en la BBDD...
                $error = $bd->existe($user, $pass); 
                if ($error === false) {
                    
                    session_start(); // Crear sesion... 
                    $_SESSION['nombre'] = $user;
                    header("Location: home.php");
                    
                    /*
                    header("Location: home.php?user=$user");
                    exit();*/
                }
            }
            break;

        default:
            break;
    }

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Usuarios BD</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <div>
            <h1>Usuario en la Base de datos</h1>        
            <fieldset>
                <legend>Ingresar usuario</legend>
                <span><?=$error?></span>
                <form action="index.php" method="POST">
                    <div id="inputs">
                        <div class="filds">
                            <input type="text" placeholder="Ingresar usuario" name="user" value="<?=$user?>">
                            <label for="">Usuario</label>
                        </div>

                        <div class="filds">
                            <input type="password" name="pass" placeholder="Ingresar contraseña" value="<?=$pass?>">
                            <label for="">Password</label>
                        </div>
                    </div>
                    
                    <div id="btn">
                        <input type="submit" value="Insertar" name="btn">
                        <input type="submit" value="Mostrar" name="btn">
                        <input type="submit" value="Acceder" name="btn">
                    </div>
                    
                </form>
            </fieldset>

            <?php if ($view):?>
            <table>
                <thead>
                    <tr>
                        <?=$tabla->tableHead($nameColumn);?>
                    </tr>
                </thead>

                <tbody>
                    <?=$tabla->tableBody($usersBD);?> 
                </tbody>
            </table>
            <?php endif; ?>
        </div>                        
    </body>
</html>
