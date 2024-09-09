<?php
session_start();
include('config/db.php');

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash da senha
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Inserir novo usuário
    $stmt = $conn->prepare("INSERT INTO usuarios (username, password) VALUES (?, ?)");
    $stmt->bind_param('ss', $username, $hashed_password);
    if ($stmt->execute()) {
        $success = "Usuário registrado com sucesso!";
    } else {
        $error = "Erro ao registrar o usuário: " . $stmt->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuário</title>
    <link rel="apple-touch-icon" sizes="180x180" href="img/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/favicon_io/favicon-16x16.png">
    <link rel="manifest" href="img/favicon_io/site.webmanifest">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            color: #005073;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #333;
            color: #fff;
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            z-index: 1000; /* Garante que a sidebar fique sobre o conteúdo */
        }
        .sidebar h3 {
            margin-top: 0;
        }
        .sidebar a {
            text-decoration: none;
            color: #fff;
            display: block;
            padding: 10px;
            margin: 5px 0;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }
        .sidebar a:hover {
            background-color: #575757;
        }
        .sidebar ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px); /* Ajusta a largura do conteúdo */
        }
        form {
            max-width: 500px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="text"],
        input[type="password"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #079DC2;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #005073;
        }
        .success-message {
            color: green;
            font-weight: bold;
        }
        .error-message {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php include('dashboard.php'); ?> <!-- Inclui o menu lateral -->

    <div class="content">
        <h2><center>Registrar Novo Usuário<center></h2>
        <form method="POST">
            <label for="username">Nome de Usuário:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Senha:</label>
            <input type="password" id="password" name="password" required>
            
            <input type="submit" value="Registrar">
        </form>
        <?php if (isset($success)) echo "<p class='success-message'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>
    </div>
</body>
</html>
