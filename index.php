<?php
session_start();
include('config/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consulta segura usando prepared statements
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE username = ?");
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        // Verificar a senha
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;
            header('Location: dashboard.php');
            exit();
        } else {
            $error = "Nome de usuário ou senha incorretos!";
        }
    } else {
        $error = "Nome de usuário ou senha incorretos!";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SCE</title>
    <link rel="apple-touch-icon" sizes="180x180" href="img/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/favicon_io/favicon-16x16.png">
    <link rel="manifest" href="img/favicon_io/site.webmanifest">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-image: url('img/login.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4; /* Cor de fundo se a imagem não carregar */
        }
        .login-container {
            background-color: rgba(000, 000, 000, 0.8); /* Fundo branco com 80% de opacidade */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.9);
            max-width: 400px;
            width: 100%;
            color: #fff;
        }
        h2 {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin: 10px 0 5px;
            text-align: left;
        }
        input[type="text"],
        input[type="password"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            background-color: #0C6C81;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #095161;
        }
        .error {
            color: red;
            margin-top: 20px;
        }
        .footer {
            width: 100%;
            background-color: #2C3739;
            color: #fff;
            text-align: center;
            padding: 1px 0;
            position: fixed;
            bottom: 0;
            left: 0;
            font-size: 0.9em;
        }
    </style>
    
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form method="POST">
            <label for="username">Nome de Usuário:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Senha:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Login">
        </form>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
    </div>
    <div class="footer">
        Developed by Eugenio Oliveira
    </div>
</body>
</html>
