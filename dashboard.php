<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Início</title>
    <link rel="apple-touch-icon" sizes="180x180" href="img/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/favicon_io/favicon-16x16.png">
    <link rel="manifest" href="img/favicon_io/site.webmanifest">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            min-height: 10vh;
        }
        .navbar {
            width: 100%;
            background-color: #005073;
            color: #fff;
            padding: 0.1px 1px;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
        }
        .navbar h1 {
            margin: 0;
            font-size: 1.5em;
        }
        .navbar ul {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
        }
        .navbar li {
            position: relative;
        }
        .navbar a {
            text-decoration: none;
            color: #fff;
            padding: 10px 15px;
            display: block;
            transition: background-color 0.3s ease;
        }
        .navbar a:hover {
            background-color: #079DC2;
        }
        .dropdown {
            position: relative;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #005073;
            box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
            z-index: 1000;
        }
        .dropdown-content a {
            padding: 10px;
            color: #fff;
            display: block;
        }
        .dropdown-content a:hover {
            background-color: #2795A6;
        }
        .dropdown:hover .dropdown-content {
            display: block;
        }
        .content {
            padding: 1px;
            margin-top: 60px;
            flex: 1;
        }
        .footer {
            width: 100%;
            background-color: #005073;
            color: #fff;
            text-align: center;
            padding: 1px 0;
            position: fixed;
            bottom: 0;
            left: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 0.8em;
        }
        .footer .user-info {
            position: absolute;
            left: 20px;
            display: flex;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <a href="dashboard.php"><h1>SCE</h1></a>
        <ul>
            <li><a href="entrada.php">Entrada</a></li>
            <li><a href="saida.php">Saída</a></li> 
            <li><a href="cadastro_produto.php">Cadastro de Produtos</a></li>
            <li class="dropdown">
                <a href="#">Relatórios</a>
                <div class="dropdown-content">
                    <a href="relatorios_entrada_saida.php">Entrada e Saída</a>
                    <a href="relatorios_estoque.php">Estoque</a>
                    <a href="relatorios_produtos.php">Produtos</a>
                </div>
            </li>
            <li><a href="gerenciar_usuarios.php">Usuários</a></li>
            <li><a href="backup.php">Backup</a></li>
            <li><a href="logout.php">Sair</a></li>
        </ul>
    </div>
    <div class="content">
        <!-- O conteúdo da página será exibido aqui -->
    </div>
    <div class="footer">
        <div class="user-info">
            <?php
                // Iniciar a sessão apenas se ainda não estiver ativa
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                // Verificar se o usuário está logado
                if (isset($_SESSION['username'])) {
                    $username = htmlspecialchars($_SESSION['username']);
                } else {
                    $username = 'Usuário';
                }
            ?>
            <span>Usuário: <?php echo $username; ?></span>
        </div>
        <span>Developed by Eugenio Oliveira</span>
    </div>
</body>
</html>
