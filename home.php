<?php 
    session_start(); // Crear sesion...
    
    //$user = filter_input(INPUT_GET, 'user'); // header("Location: home.php?user=$user");


    $user = $_SESSION['nombre']; // Obtener la sessión...
    
    // Si NO estoy logueado...
    if(!isset($_SESSION['nombre'])){
        header("Location: index.php?info='Debes loguearte primero'");
        exit();
    }
    
    // Desconectar...
    if (isset($_POST['loguot'])) {    
        session_destroy();
        header("Location: index.php");
        exit();
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Bienvenido</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>       
        <div>
            <h2>Bienvenido usuario (<?=stripslashes($user)?>)</h2>
        
            <form action="index.php" method="POST">
                <input type="submit" value="Salir" name="loguot">
            </form>
        </div>        
    </body>
</html>

