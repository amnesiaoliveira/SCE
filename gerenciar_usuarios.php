<?php
session_start();
include('config/db.php');

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

// Buscar usuários
$query = "SELECT id, username FROM usuarios";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Erro ao buscar usuários: " . mysqli_error($conn));
}
$usuarios = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Usuários</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ccc;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        button, a.button {
            background-color: #079DC2;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        button:hover, a.button:hover {
            background-color: #005073;
        }
        .actions {
            display: flex;
            gap: 10px;
        }
    </style>
</head>
<body>
    <?php include('dashboard.php'); ?> <!-- Inclui o menu lateral -->

    <div class="content">
        <h2>Gerenciamento de Usuários</h2>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome de Usuário</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?php echo htmlspecialchars($usuario['id']); ?></td>
                    <td><?php echo htmlspecialchars($usuario['username']); ?></td>
                    <td class="actions">
                        <a href="alterar_senha.php?id=<?php echo htmlspecialchars($usuario['id']); ?>" class="button">Alterar Senha</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="registrar_usuario.php"><button>Cadastrar Novo Usuário</button></a>
    </div>
</body>
</html>
