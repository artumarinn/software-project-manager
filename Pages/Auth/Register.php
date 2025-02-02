<?php
include_once '../../Database/connection.php';

$registerMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['createAccount'])) {
    $dni = $_POST['dni'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $sql ="SELECT dni FROM software_project_manager.employee WHERE dni = ('$dni')";  
    $result=mysqli_query($conn, $sql);

    if ($result->num_rows > 0) {

        if ($password == $confirm_password) {
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
    
            try {
                $sql = "UPDATE employee SET password = '$password_hashed' WHERE dni = '$dni'";
                if ($conn->query($sql) === TRUE) {
                    $registerMessage = "Registro exitoso";
                } else {
                    throw new Exception($conn->error, $conn->errno);
                }
            } catch (Exception $e) {
                if ($e->getCode() == 1062) {
                    $registerMessage = "Usuario con DNI $dni ya está registrado.";
                } else {
                    $registerMessage = "Error: " . $e->getMessage();
                }
            }
        } else {
            $registerMessage = "Las contraseñas no coinciden.";
        }

    } else {

        $registerMessage = "ERROR. El DNI no se encuentra en la base de datos";
        
    }

    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Styles/styleAuth.css">
    <title>Regitro</title>
</head>
<body>
    <header>
        <h1>Gestor de Proyectos de Software</h1>
        <nav>
        <a href="http://localhost/UCH/BASE-DE-DATOS/Software-Project-Manager/Pages/Auth/Login.php">Login</a>
        </nav>
    </header>
    <h1>Registro</h1>
    <div>
        <?php if ($registerMessage) { echo "<p>$registerMessage</p>"; } ?>
        <form method="POST">
            <label for="dni">DNI:</label><br>
            <input type="number" id="dni" name="dni" required><br><br>
            <label for="password">Contraseña:</label><br>
            <input type="password" id="password" name="password" required><br><br>
            <label for="confirm_password">Confirmar Contraseña:</label><br>
            <input type="password" id="confirm_password" name="confirm_password" required><br><br>
            <button type="submit" name="createAccount">Crear usuario</button>
        </form>
    </div>
</body>
</html>