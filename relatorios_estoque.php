<?php
session_start();
include('config/db.php');

// Verificar se o usuário está logado
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

$estoque = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Consultar produtos
    $query_estoque = "
        SELECT p.codigo, p.nome, p.quantidade AS quantidade_estoque, 
               COALESCE(SUM(e.quantidade), 0) AS quantidade_entradas, 
               COALESCE(SUM(s.quantidade), 0) AS quantidade_saidas
        FROM produtos p
        LEFT JOIN entradas e ON p.codigo = e.codigo
        LEFT JOIN saidas s ON p.codigo = s.codigo
        GROUP BY p.codigo, p.nome, p.quantidade
    ";

    $result_estoque = mysqli_query($conn, $query_estoque);

    if ($result_estoque) {
        while ($row = mysqli_fetch_assoc($result_estoque)) {
            $estoque[] = $row;
        }
    } else {
        $error = "Erro na consulta de estoque: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Estoque</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #005073;
        }
        .container {
            margin-left: 220px;
            padding: 20px;
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
            padding: 5px;
            text-align: left;
            font-size: 10px;
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
        .print-container {
            margin-top: 20px;
        }
        .print-button {
            background-color: #079DC2;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-bottom: 20px;
        }
        .print-button:hover {
            background-color: #005073;
        }
        .error-message {
            color: red;
            font-weight: bold;
            text-align: center;
        }
        @media print {
            body * {
                visibility: hidden;
            }
            .print-container, .print-container * {
                visibility: visible;
            }
            .print-container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .print-button {
                display: none;
            }
        }
    </style>
</head>
<body>
    <?php include('dashboard.php'); ?> <!-- Inclui o menu lateral -->

    <div class="container">
        <h2>Relatório de Estoque</h2>

        <form method="POST">
            <input type="submit" value="Gerar Relatório">
        </form>

        <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>

        <?php if (!empty($estoque)): ?>
            <div class="print-container">
                <button class="print-button" onclick="window.print();">Imprimir Relatório</button>

                <table>
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Nome</th>
                            <th>Quantidade em Estoque</th>
                            <th>Entradas</th>
                            <th>Saídas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($estoque as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['codigo']); ?></td>
                                <td><?php echo htmlspecialchars($item['nome']); ?></td>
                                <td><?php echo htmlspecialchars($item['quantidade_estoque']); ?></td>
                                <td><?php echo htmlspecialchars($item['quantidade_entradas']); ?></td>
                                <td><?php echo htmlspecialchars($item['quantidade_saidas']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>Não há dados disponíveis para o relatório.</p>
        <?php endif; ?>
    </div>
</body>
</html>
