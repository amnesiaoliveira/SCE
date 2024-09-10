<?php
session_start();
include('config/db.php');

if (!isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}

$success = '';
$error = '';

// Processar formulário de saída de produtos
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['registrar_saida'])) {
    $codigo = mysqli_real_escape_string($conn, $_POST['codigo']);
    $produto = mysqli_real_escape_string($conn, $_POST['produto']);
    $quantidade = mysqli_real_escape_string($conn, $_POST['quantidade']);
    $data_lancamento = mysqli_real_escape_string($conn, $_POST['data_lancamento']);
    $setor_destino = mysqli_real_escape_string($conn, $_POST['setor_destino']);

    // Verificar se o produto existe e obter a quantidade atual
    $query_verificar = "SELECT quantidade FROM produtos WHERE codigo = '$codigo' AND nome = '$produto'";
    $result_verificar = mysqli_query($conn, $query_verificar);
    
    if (mysqli_num_rows($result_verificar) > 0) {
        $row = mysqli_fetch_assoc($result_verificar);
        $quantidade_atual = $row['quantidade'];

        if ($quantidade_atual >= $quantidade) {
            // Atualizar a quantidade do produto
            $quantidade_nova = $quantidade_atual - $quantidade;
            $query_atualizar = "UPDATE produtos SET quantidade = $quantidade_nova WHERE codigo = '$codigo' AND nome = '$produto'";
            if (mysqli_query($conn, $query_atualizar)) {
                // Registrar a saída
                $query_saida = "INSERT INTO saidas (codigo, produto, quantidade, data_lancamento, setor_destino) 
                                VALUES ('$codigo', '$produto', $quantidade, '$data_lancamento', '$setor_destino')";
                if (mysqli_query($conn, $query_saida)) {
                    $success = "Saída registrada com sucesso!";
                } else {
                    $error = "Erro ao registrar saída: " . mysqli_error($conn);
                }
            } else {
                $error = "Erro ao atualizar quantidade: " . mysqli_error($conn);
            }
        } else {
            $error = "Quantidade em estoque insuficiente!";
        }
    } else {
        $error = "Produto não encontrado!";
    }
}

// Processar adição de novo setor
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['adicionar_setor'])) {
    $novo_setor = mysqli_real_escape_string($conn, $_POST['novo_setor']);
    if (!empty($novo_setor)) {
        // Verificar se o setor já existe
        $query_verificar_setor = "SELECT * FROM setores_destino WHERE nome = '$novo_setor'";
        $result_verificar_setor = mysqli_query($conn, $query_verificar_setor);

        if (mysqli_num_rows($result_verificar_setor) == 0) {
            $query_adicionar_setor = "INSERT INTO setores_destino (nome) VALUES ('$novo_setor')";
            if (mysqli_query($conn, $query_adicionar_setor)) {
                $success = "Novo setor adicionado com sucesso!";
            } else {
                $error = "Erro ao adicionar novo setor: " . mysqli_error($conn);
            }
        } else {
            $error = "O setor já está cadastrado!";
        }
    } else {
        $error = "O nome do setor não pode ser vazio!";
    }
}

// Processar remoção de setor
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remover_setor'])) {
    $setor_remover = mysqli_real_escape_string($conn, $_POST['setor_remover']);
    if (!empty($setor_remover)) {
        // Remover setor
        $query_remover_setor = "DELETE FROM setores_destino WHERE nome = '$setor_remover'";
        if (mysqli_query($conn, $query_remover_setor)) {
            $success = "Setor removido com sucesso!";
        } else {
            $error = "Erro ao remover setor: " . mysqli_error($conn);
        }
    } else {
        $error = "Selecione um setor para remover!";
    }
}

// Buscar setores existentes
$query_setores = "SELECT nome FROM setores_destino";
$result_setores = mysqli_query($conn, $query_setores);
$setores = [];
while ($row = mysqli_fetch_assoc($result_setores)) {
    $setores[] = $row['nome'];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saída de Produtos</title>
    <script src="autocomplete.js"></script>
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
        }
        .container {
            margin-left: 176px;
            padding: 20px;
            width: calc(100% - 176px); /* Ajusta a largura do container */
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: flex-start;
            min-height: 100vh;
        }
        form {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            max-width: 700px;
            width: 100%;
        }
        .form-group {
            flex: 1 1 48%; /* Campos lado a lado */
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="number"],
        input[type="date"],
        select {
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
            margin-top: 10px;
            box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
        }
        input[type="submit"]:hover {
            background-color: #005073;
        }
        .success-message {
            color: green;
            font-weight: bold;
            margin-top: 10px;
        }
        .error-message {
            color: red;
            font-weight: bold;
            margin-top: 10px;
        }
        .new-setor-form,
        .remove-setor-form {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php include('dashboard.php'); ?> <!-- Inclui o menu lateral -->

    <div class="container">
        <h2>Saída de Produtos</h2>
        <form method="POST">
            <div class="form-group">
                <label for="codigo">Código ou Nome do Produto:</label>
                <input type="text" id="codigo" name="codigo" onkeyup="buscarSugestoes()" autocomplete="off" required>
                <div id="sugestoes" class="suggestions-box"></div>
            </div>
            <div class="form-group">
                <label for="produto">Nome do Produto:</label>
                <input type="text" id="produto" name="produto" readonly required>
            </div>
            <div class="form-group">
                <label for="quantidade">Quantidade:</label>
                <input type="number" id="quantidade" name="quantidade" required>
            </div>
            <div class="form-group">
                <label for="data_lancamento">Data de Lançamento:</label>
                <input type="date" id="data_lancamento" name="data_lancamento" required>
            </div>
            <div class="form-group">
                <label for="setor_destino">Setor de Destino:</label>
                <select id="setor_destino" name="setor_destino" required>
                    <option value="">Selecione um setor</option>
                    <?php foreach ($setores as $setor): ?>
                        <option value="<?php echo $setor; ?>"><?php echo $setor; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <input type="submit" name="registrar_saida" value="Registrar Saída">
        </form>

        <!-- Formulário para adicionar novo setor -->
        <div class="new-setor-form">
            <h3>Adicionar Novo Setor de Destino</h3>
            <form method="POST">
                <div class="form-group">
                    <label for="novo_setor">Novo Setor:</label>
                    <input type="text" id="novo_setor" name="novo_setor" required>
                </div>
                <input type="submit" name="adicionar_setor" value="Adicionar Setor">
            </form>
        </div>

        <!-- Formulário para remover setor -->
        <div class="remove-setor-form">
            <h3>Remover Setor de Destino</h3>
            <form method="POST">
                <div class="form-group">
                    <label for="setor_remover">Setor para Remover:</label>
                    <select id="setor_remover" name="setor_remover" required>
                        <option value="">Selecione um setor</option>
                        <?php foreach ($setores as $setor): ?>
                            <option value="<?php echo $setor; ?>"><?php echo $setor; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <input type="submit" name="remover_setor" value="Remover Setor">
            </form>
        </div>

        <?php if (!empty($success)) echo "<p class='success-message'>$success</p>"; ?>
        <?php if (!empty($error)) echo "<p class='error-message'>$error</p>"; ?>
    </div>

    <script>
        function buscarProduto() {
            var codigo = document.getElementById('codigo').value;
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'buscar_produto.php?codigo=' + codigo, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        var resposta = xhr.responseText.trim();
                        if (resposta !== 'Produto não encontrado!') {
                            document.getElementById('produto').value = resposta;
                        } else {
                            document.getElementById('produto').value = '';
                        }
                    } else {
                        document.getElementById('produto').value = 'Produto não encontrado!';
                    }
                }
            };
            xhr.send();
        }
    </script>
</body>
</html>
