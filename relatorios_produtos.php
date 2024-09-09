<?php
session_start();
include('config/db.php');

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

$produtos = [];
$nome_filtro = '';
$categoria_filtro = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_filtro = $_POST['nome'];
    $categoria_filtro = $_POST['categoria'];

    // Consultar produtos com base nos filtros usando prepared statements
    $query = "SELECT codigo, nome, categoria FROM produtos WHERE 1=1";
    if (!empty($nome_filtro)) {
        $query .= " AND nome LIKE ?";
    }
    if (!empty($categoria_filtro)) {
        $query .= " AND categoria LIKE ?";
    }

    $stmt = $conn->prepare($query);

    // Bind parameters
    if (!empty($nome_filtro) && !empty($categoria_filtro)) {
        $param_nome = '%' . $nome_filtro . '%';
        $param_categoria = '%' . $categoria_filtro . '%';
        $stmt->bind_param("ss", $param_nome, $param_categoria);
    } elseif (!empty($nome_filtro)) {
        $param_nome = '%' . $nome_filtro . '%';
        $stmt->bind_param("s", $param_nome);
    } elseif (!empty($categoria_filtro)) {
        $param_categoria = '%' . $categoria_filtro . '%';
        $stmt->bind_param("s", $param_categoria);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $produtos[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos Cadastrados</title>
    <link rel="apple-touch-icon" sizes="180x180" href="img/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/favicon_io/favicon-16x16.png">
    <link rel="manifest" href="img/favicon_io/site.webmanifest">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #005073;
        }
        .container {
            margin-left: 250px; /* Alinha o conteúdo ao lado da sidebar */
            padding: 20px;
            width: calc(80% - 250px); /* Ajusta a largura do container */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 5px; /* Reduzido o padding para diminuir a altura das linhas */
            text-align: left;
            font-size: 10px; /* Ajustado o tamanho da fonte para corresponder à altura reduzida */
        }
        th {
            background-color: #f4f4f4;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        form {
            max-width: 500px;
            margin: auto;
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="text"] {
            width: calc(100% - 22px);
            padding: 8px; /* Ajustado o padding dos campos de entrada */
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px; /* Ajustado o tamanho da fonte dos campos de entrada */
        }
        input[type="submit"] {
            background-color: #079DC2;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px; /* Ajustado o tamanho da fonte do botão de envio */
        }
        input[type="submit"]:hover {
            background-color: #005073;
        }
    </style>
</head>
<body>
    <?php include('dashboard.php'); ?> <!-- Inclui o menu lateral -->

    <div class="container">
        <h2><center>Produtos Cadastrados</center></h2>

        <form method="POST">
            <label>Nome do Produto:</label>
            <input type="text" name="nome" value="<?php echo htmlspecialchars($nome_filtro); ?>">
            
            <label>Categoria:</label>
            <input type="text" name="categoria" value="<?php echo htmlspecialchars($categoria_filtro); ?>">
            
            <input type="submit" value="Filtrar">
        </form>

        <?php if (!empty($produtos)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nome</th>
                        <th>Categoria</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produtos as $produto): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($produto['codigo']); ?></td>
                            <td><?php echo htmlspecialchars($produto['nome']); ?></td>
                            <td><?php echo htmlspecialchars($produto['categoria']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p><center>Não há produtos cadastrados que correspondam aos critérios de filtro.</center></p>
        <?php endif; ?>
    </div>
</body>
</html>
