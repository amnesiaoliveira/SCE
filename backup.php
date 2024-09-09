<?php
session_start();
include('config/db.php');

// Verifica se o usuário está autenticado
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Configurações do backup
    $backup_file = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
    $command = "mysqldump --user={$username} --password={$password} --host={$servername} {$database} > {$backup_file}";

    // Executa o comando de backup
    $output = shell_exec($command);

    if (file_exists($backup_file)) {
        // Fornece o arquivo para download
        header('Content-Type: application/octet-stream');
        header("Content-Disposition: attachment; filename=\"$backup_file\"");
        header('Content-Length: ' . filesize($backup_file));
        readfile($backup_file);

        // Remove o arquivo após o download
        unlink($backup_file);
        exit();
    } else {
        $error = "Erro ao criar o backup. Verifique se o comando mysqldump está disponível e configurado corretamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Backup da Base de Dados</title>
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
            background-color: #f4f4f4;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #2C3739;
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
            display: flex;
            flex-direction: column;
            align-items: center;
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
        .success-message,
        .error-message {
            text-align: center;
            font-weight: bold;
            margin-top: 20px;
        }
        .success-message {
            color: green;
        }
        .error-message {
            color: red;
        }
    </style>
</head>
<body>
    <?php include('dashboard.php'); ?> <!-- Inclui o menu lateral -->

    <div class="content">
        <h2>Backup da Base de Dados</h2>
        <form method="POST">
            <input type="submit" value="Criar Backup">
        </form>
        <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>
    </div>
</body>
</html>
