<?php
// Ativa exibição de erros para debug
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);                          //http://localhost/projeto/index.html olhar o site
error_reporting(E_ALL);

// Conexão com o banco
$host = "localhost";
$usuario = "root";
$senha = "";
$banco = "voluntariado_db";

$conn = new mysqli($host, $usuario, $senha, $banco);

if ($conn->connect_error) {
    die("<h2 style='color:red; text-align:center;'>ERRO NA CONEXÃO COM BANCO DE DADOS: " . $conn->connect_error . "</h2>");
}

// Pega projeto selecionado via GET
$projetoselecionado = isset($_GET['projeto']) ? $_GET['projeto'] : 'Não especificado';

// Quando o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $projeto = $_POST['projeto'];

    if (empty($nome) || empty($email)) {
        echo "<p class='error'>Por favor, preencha todos os campos.</p>";    
    } else {
        // Insere no banco
        $stmt = $conn->prepare("INSERT INTO cadastro (nome, email, projeto) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nome, $email, $projeto);
        
        if ($stmt->execute()) {
            echo "<div style='text-align:center; padding:40px;'>";
            echo "<div class='success'>";
            echo "<h2>Obrigado pelo cadastro, " . htmlspecialchars($nome) . "!</h2>";
            echo "<p>Seus dados foram salvos!</p>";
            echo '<br><a href="index.html">Voltar</a>';
            echo "</div>";

        } else {
            echo "<p>Erro ao salvar: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }
    $conn->close();
    exit;
}

$conn->close();
?>

<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/login.css">
    <title>Cadastro Voluntário</title>
</head>
<body>
<main>
    <h1>Cadastro de Voluntário</h1>
    <p>Você vai se inscrever no projeto: <strong><?php echo htmlspecialchars($projetoselecionado); ?></strong></p>

    <form method="POST" action="cadastro.php">
        <input type="hidden" name="projeto" value="<?php echo htmlspecialchars($projetoselecionado); ?>">

        <label for="nome">Nome:</label><br>
        <input type="text" id="nome" name="nome" required><br>

        <label for="email">Email:</label><br>
        <input type="text" id="email" name="email" required><br><br>

        <input type="submit" value="Enviar">
    </form>

    <br>
    <a href="index.html">Voltar</a>
</main>
</body>
</html>
