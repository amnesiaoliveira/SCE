<?php
session_start();
include('config/db.php');

// Verificar se o usuário está logado
if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

$entradas = [];
$saidas = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data_inicio = mysqli_real_escape_string($conn, $_POST['data_inicio']);
    $data_fim = mysqli_real_escape_string($conn, $_POST['data_fim']);

    // Verificar se as datas foram enviadas e são válidas
    if ($data_inicio && $data_fim) {
        if ($data_inicio > $data_fim) {
            $error = "A data de início não pode ser depois da data de fim.";
        } else {
            // Consultar entradas
            $query_entradas = $conn->prepare("SELECT * FROM entradas WHERE data_lancamento BETWEEN ? AND ?");
            $query_entradas->bind_param("ss", $data_inicio, $data_fim);
            $query_entradas->execute();
            $result_entradas = $query_entradas->get_result();

            if ($result_entradas) {
                while ($row = $result_entradas->fetch_assoc()) {
                    $entradas[] = $row;
                }
            } else {
                $error = "Erro na consulta de entradas: " . $conn->error;
            }

            // Consultar saídas
            $query_saidas = $conn->prepare("SELECT * FROM saidas WHERE data_lancamento BETWEEN ? AND ?");
            $query_saidas->bind_param("ss", $data_inicio, $data_fim);
            $query_saidas->execute();
            $result_saidas = $query_saidas->get_result();

            if ($result_saidas) {
                while ($row = $result_saidas->fetch_assoc()) {
                    $saidas[] = $row;
                }
            } else {
                $error = "Erro na consulta de saídas: " . $conn->error;
            }

            $query_entradas->close();
            $query_saidas->close();
        }
    } else {
        $error = "Datas não foram enviadas corretamente.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Movimentação</title>
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
            max-width: 600px;
            margin: auto;
        }
        .form-group {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .form-group label {
            margin-right: 10px;
            width: 100%;
        }
        .form-group input[type="date"] {
            width: calc(50% - 10px);
            padding: 10px;
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
            box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
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
            box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
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
        <h2>Relatórios de Entrada e Saída</h2>

        <form method="POST">
            <div class="form-group">
                <label for="data_inicio">Data de Início:</label>
                <input type="date" id="data_inicio" name="data_inicio" required>
                
                <label for="data_fim">Data de Fim:</label>
                <input type="date" id="data_fim" name="data_fim" required>
            </div>
            <input type="submit" value="Gerar Relatório">
        </form>

        <?php if (isset($error)) echo "<p class='error-message'>$error</p>"; ?>

        <?php if (!empty($entradas) || !empty($saidas)): ?>
            <div class="print-container">
                <button class="print-button" onclick="window.print();">Imprimir Relatório</button>

                <h3>Entradas</h3>
                <?php if (!empty($entradas)): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Produto</th>
                                <th>Quantidade</th>
                                <th>Data de Lançamento</th>
                                <th>Número do Lote</th>
                                <th>Data de Fabricação</th>
                                <th>Validade</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($entradas as $entrada): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($entrada['codigo']); ?></td>
                                    <td><?php echo htmlspecialchars($entrada['produto']); ?></td>
                                    <td><?php echo htmlspecialchars($entrada['quantidade']); ?></td>
                                    <td><?php echo htmlspecialchars($entrada['data_lancamento']); ?></td>
                                    <td><?php echo htmlspecialchars($entrada['numero_lote']); ?></td>
                                    <td><?php echo htmlspecialchars($entrada['data_fabricacao']); ?></td>
                                    <td><?php echo htmlspecialchars($entrada['validade']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Não há entradas registradas para o período selecionado.</p>
                <?php endif; ?>

                <h3>Saídas</h3>
                <?php if (!empty($saidas)): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Produto</th>
                                <th>Quantidade</th>
                                <th>Data de Lançamento</th>
                                <th>Setor de Destino</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($saidas as $saida): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($saida['codigo']); ?></td>
                                    <td><?php echo htmlspecialchars($saida['produto']); ?></td>
                                    <td><?php echo htmlspecialchars($saida['quantidade']); ?></td>
                                    <td><?php echo htmlspecialchars($saida['data_lancamento']); ?></td>
                                    <td><?php echo htmlspecialchars($saida['setor_destino']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p>Não há saídas registradas para o período selecionado.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
