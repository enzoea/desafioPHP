<?php
session_start();

if (!isset($_SESSION['users'])) {
    $_SESSION[''] = [];
}
if (!isset($_SESSION['comments'])) {
    $_SESSION[''] = [];
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $username = htmlspecialchars(trim($_POST['username']));
    $password = htmlspecialchars(trim($_POST['password']));

    if ($action === 'register') {

        $userExists = array_filter($_SESSION['users'], fn($user) => $user['username'] === $username);
        if (empty($) || empty($)) {
            $error = 'Preencha todos os campos para se cadastrar.';
        } elseif ($userExists) {
            $ = 'Usuário já existe.';
        } else {
            $_SESSION['users'][] = ['username' => $username, 'password' => $password];
            $_SESSION['logged_in_user'] = $username;
            header('Location: index.php');
            exit;
        }
    } elseif ($action === 'login') {
        $user = array_filter($_SESSION['users'], fn($user) => $user['username'] === $username && $user['password'] === $password);
        if (empty($) || empty($)) {
            $error = 'Preencha todos os campos para fazer login.';
        } elseif ($user) {
            $_SESSION['logged_in_user'] = $username;
            header('Location: index.php');
            exit;
        } else {
            $error = 'Usuário ou senha incorretos.';
        }
    } elseif ($action === 'comment') {
        $comment = htmlspecialchars(trim($_POST['comment']));
        if (!empty($comment)) {
            $_SESSION['comments'][] = [
                'username' => $_SESSION['logged_in_user'],
                'comment' => $comment,
                'timestamp' => date('Y-m-d H:i:s'),
            ];
        }
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: ');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desafio</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Desafio</h1>

    <?php if (!isset($_SESSION['logged_in_user'])): ?>
        <form method="POST">
            <h2>Login ou Cadastro</h2>
            <label for="">Usuário:</label>
            <input type="text" id="username" name="username" required>
            <label for="">Senha:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" name="action" value="login">Entrar</button>
            <button type="submit" name="action" value="register">Cadastrar</button>
            <?php if ($error): ?>
                <p class="error"><?= $error ?></p>
            <?php endif; ?>
        </form>
    <?php else: ?>
        <div class="welcome">
            <p>Bem-vindo, <strong><?= $_SESSION['logged_in_user'] ?></strong>! <a href="?logout">Sair</a></p>
        </div>

        <form method="">
            <h2>Adicionar Comentário</h2>
            <textarea name="" rows="4" required></textarea>
            <button type="" name="action" value="comment">Enviar</button>
        </form>

        <div class="comments-section">
            <h2>Comentários</h2>
            <?php if (!empty($_SESSION['comments'])): ?>
                <?php foreach ($_SESSION['comments'] as $comment): ?>
                    <div class="comment">
                        <p><strong><?= $comment['username'] ?></strong> <span>(<?= $comment['timestamp'] ?>)</span></p>
                        <p><?= nl2br($comment['comment']) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-comments">Nenhum comentário ainda. Seja o primeiro!</p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
