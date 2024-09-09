<?php
session_start();
include('config/db.php');

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $new_password = $_POST['new_password'];

    // Hash da nova senha
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    // Atualizar senha do usuário
    $stmt = $conn->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
    $stmt->bind_param('si', $hashed_password, $id);
    if ($stmt->execute()) {
        $success = "Senha atualizada com sucesso!";
    } else {
        $error = "Erro ao atualizar a senha: " . $stmt->error;
    }
    $stmt->close();
}

// Buscar usuário para preencher o formulário
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $conn->prepare("SELECT username FROM usuarios WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->bind_result($username);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Senha</title>
    <link rel="apple-touch-icon" sizes="180x180" href="img/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/favicon_io/favicon-16x16.png">
    <link rel="manifest" href="img/favicon_io/site.webmanifest">
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #005073;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
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
            z-index: 1000;
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
            width: calc(100% - 250px);
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
        <h2>Alterar Senha para <?php echo htmlspecialchars($username); ?></h2>
        <form method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <label for="new_password">Nova Senha:</label>
            <input type="password" id="new_password" name="new_password" required>
            <input type="submit" value="Alterar Senha">
        </form>
        <?php if (isset($success)) echo "<p class='success-message'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>
    </div>
</body>
</html>
