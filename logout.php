<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
            overflow: hidden;
        }
        .logout-container {
            text-align: center;
            font-family: Arial, sans-serif;
        }
        .message {
            font-size: 20px;
            margin-bottom: 20px;
        }
        .fade-out {
            animation: fadeOut 5s forwards;
        }
        @keyframes fadeOut {
            from {
                opacity: 1;
            }
            to {
                opacity: 0;
            }
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <div class="message">Você está sendo desconectado...</div>
    </div>

    <script>
        // Função para redirecionar após a animação
        function redirectToLogin() {
            window.location.href = 'index.php';
        }

        // Adicionar a classe para iniciar a animação
        document.querySelector('.logout-container').classList.add('fade-out');

        // Redirecionar após a animação
        setTimeout(redirectToLogin, 2000); // 2000 ms = 2 segundos, que é a duração da animação
    </script>
</body>
</html>

<?php
session_destroy();
exit();
?>
