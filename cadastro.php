<?php
// ==========================================================
//  ARQUIVO: cadastro.php
//  FUNÇÃO: receber o projeto selecionado, exibir o formulário
//          de cadastro de voluntário e salvar os dados no BD.
//  FLUXO GERAL:
//
//  1. Usuário clica em "Tenho interesse" na index.html
//     → abre: cadastro.php?projeto=NOME_DO_PROJETO
//
//  2. cadastro.php (método GET)
//     → mostra o formulário, exibindo o nome do projeto
//
//  3. Usuário preenche nome e email e envia (método POST)
//     → cadastro.php recebe os dados
//     → valida
//     → insere na tabela `cadastro`
//     → mostra uma tela de sucesso
// ==========================================================



// ----------------------------------------------------------
//  CONFIGURAÇÃO DE EXIBIÇÃO DE ERROS (APENAS PARA DESENVOLVIMENTO)
//  Isso ajuda a ver mensagens de erro do PHP na tela.
// ----------------------------------------------------------
ini_set('display_errors', 1);        // Mostrar erros de execução
ini_set('display_startup_errors', 1);// Mostrar erros de inicialização
error_reporting(E_ALL);              // Mostrar todos os tipos de erros



// ----------------------------------------------------------
// http://localhost/projeto/index.html local de teste navegador 
//  CONEXÃO COM O BANCO DE DADOS
//  Ajuste apenas se o seu banco tiver outro nome ou senha.
// ----------------------------------------------------------
$host    = "localhost";           // Servidor (padrão do XAMPP)
$usuario = "root";                // Usuário padrão do MySQL no XAMPP
$senha   = "";                    // Senha (em geral vazia no XAMPP)
$banco   = "voluntariado_db";     // Nome do banco de dados

// Cria o objeto de conexão usando mysqli
$conn = new mysqli($host, $usuario, $senha, $banco);

// Verifica se deu erro na conexão
if ($conn->connect_error) {
    // Se der erro, para tudo e mostra uma mensagem
    die("<h2 style='color:red; text-align:center;'>Erro ao conectar: " . $conn->connect_error . "</h2>");
}



// ----------------------------------------------------------
//  CAPTURA DO PROJETO SELECIONADO (VINDO POR GET)
//  Exemplo de URL: cadastro.php?projeto=Limpeza+de+Praias
//  Se não vier nada, colocamos "Não especificado".
// ----------------------------------------------------------
$projetoselecionado = isset($_GET['projeto']) ? $_GET['projeto'] : 'Não especificado';



// ----------------------------------------------------------
//  VARIÁVEIS PARA CONTROLE DE MENSAGENS
//  Serão usadas para mostrar mensagens na tela (HTML).
// ----------------------------------------------------------
$mensagemSucesso = "";   // Preenchida se o cadastro der certo
$mensagemErro    = "";   // Preenchida se der algum erro ou faltarem campos



// ----------------------------------------------------------
//  TRATAMENTO DO ENVIO DO FORMULÁRIO (MÉTODO POST)
//  Se o usuário clicou em "Cadastrar", o formulário é enviado
//  via POST e esse bloco é executado.
// ----------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Pega os valores enviados pelo formulário.
    // Usamos o operador ?? para evitar "undefined index" caso algum campo não exista.
    $nome    = $_POST['nome']    ?? '';
    $email   = $_POST['email']   ?? '';
    $projeto = $_POST['projeto'] ?? '';

    // -------------------------------
    //  Validação simples
    // -------------------------------
    if (empty($nome) || empty($email)) {

        // Se algum campo obrigatório estiver vazio,
        // guardamos uma mensagem de erro para mostrar no HTML.
        $mensagemErro = "Por favor, preencha todos os campos.";

    } else {

        // --------------------------------------------------
        //  INSERT NO BANCO DE DADOS
        //  Tabela esperada: cadastro
        //  Campos: nome, email, projeto
        // --------------------------------------------------

        // Prepara o comando SQL com placeholders (?,?,?)
        $stmt = $conn->prepare(
            "INSERT INTO cadastro (nome, email, projeto) VALUES (?, ?, ?)"
        );

        // Faz a ligação entre os placeholders e as variáveis
        // "sss" indica que os 3 parâmetros são strings
        $stmt->bind_param("sss", $nome, $email, $projeto);

        // Tenta executar o INSERT
        if ($stmt->execute()) {

            // Se deu certo, preenchemos a mensagem de sucesso,
            // que será exibida no HTML.
            $mensagemSucesso = "Obrigado pelo cadastro, " . htmlspecialchars($nome) . "!";

        } else {

            // Se deu erro no banco (por exemplo problema na tabela),
            // guardamos a mensagem de erro.
            $mensagemErro = "Erro ao salvar: " . $stmt->error;
        }

        // Fecha o statement (boa prática)
        $stmt->close();
    }
}



// ----------------------------------------------------------
//  FECHA A CONEXÃO COM O BANCO
//  A partir daqui só vamos exibir HTML.
// ----------------------------------------------------------
$conn->close();

?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro Voluntário</title>

    <!-- CSS responsável por toda a aparência do formulário -->
    <link rel="stylesheet" href="./css/login.css">
</head>
<body>

<?php if ($mensagemSucesso): ?>

    <!-- =====================================================
         TELA DE SUCESSO
         Mostrada quando o INSERT foi concluído sem erros.
         Layout simples, ainda usando a mesma .container.
         ===================================================== -->
    <div class="container">

        <!-- Podemos reutilizar a área de sign-up para mostrar o sucesso -->
        <div class="form-container sign-up">
            <form>
                <h1><?php echo $mensagemSucesso; ?></h1>

                <p>Seus dados foram registrados para o projeto:</p>
                <strong><?php echo htmlspecialchars($projetoselecionado); ?></strong>

                <p style="margin-top: 20px;">Cadastro realizado com sucesso! 🎉</p>

                <button type="button" onclick="window.location.href='index.html'">
                    Voltar para a página inicial
                </button>
            </form>
        </div>

    </div>

<?php else: ?>

    <!-- =====================================================
         TELA DE CADASTRO
         Layout baseado no CSS que você enviou, seguindo o
         modelo da imagem: lado roxo "Welcome Back!"
         e lado direito "Create account".
         ===================================================== -->

    <!-- 
        id="container" é usado pelo seu JS (toggle.js)
        class="active" deixa o lado de "Create account" visível
        logo de cara, de acordo com o seu CSS.
    -->
    <div id="container" class="container active">

        <!-- ============================================
             FORM DE CADASTRO (lado direito - branco)
             classes: .form-container.sign-up
             ============================================ -->
        <div class="form-container sign-up">
            <form method="POST" action="cadastro.php?projeto=<?php echo urlencode($projetoselecionado); ?>">

                <h1>Create account</h1>

                <!-- Ícones de redes sociais (apenas visuais) -->
                <div class="social-icons">
                    <a href="#">G</a>
                    <a href="#">F</a>
                    <a href="#">P</a>
                    <a href="#">in</a>
                </div>

                <span>or use your email for registration</span>

                <p style="margin-top: 10px;">Project:</p>
                <strong><?php echo htmlspecialchars($projetoselecionado); ?></strong>

                <!-- Campo oculto: envia o nome do projeto no POST -->
                <input type="hidden" name="projeto"
                       value="<?php echo htmlspecialchars($projetoselecionado); ?>">

                <!-- Campo de nome -->
                <input type="text" name="nome" placeholder="Name" required>

                <!-- Campo de email -->
                <input type="email" name="email" placeholder="Email" required>

                <!-- Se houve erro de validação, mostra aqui -->
                <?php if ($mensagemErro): ?>
                    <div class="error-box"><?php echo $mensagemErro; ?></div>
                <?php endif; ?>

                <!-- Botão principal (SIGN UP) -->
                <button type="submit">SIGN UP</button>
            </form>
        </div>

        <!-- ============================================
             FORM DE LOGIN (lado esquerdo "escondido")
             Você ainda não usa login de verdade, mas
             deixei o form montado para o layout funcionar
             com o CSS e com o JS de toggle.
             ============================================ -->
        <div class="form-container sign-in">
            <form action="#" method="POST">
                <h1>Sign in</h1>
                <input type="email" name="email_login" placeholder="Email">
                <input type="password" name="senha_login" placeholder="Password">
                <button type="button" onclick="window.location.href='index.html'">
                    SIGN IN
                </button>
            </form>
        </div>

        <!-- ============================================
             PAINEL ROXO (lado esquerdo na imagem)
             Aqui fica o "Welcome Back!" com o botão SIGN IN,
             igual ao que você mostrou.
             ============================================ -->
        <div class="toggle-container">
            <div class="toggle">

                <!-- Painel da esquerda (mostrado quando .active está setado) -->
                <div class="toggle-panel toggle-left">
                    <h1>Welcome Back!</h1>
                    <p>Enter your personal details to use all of site features</p>
                    <!-- Botão que tira a classe .active e mostra o lado de login -->
                    <button class="hidden" id="login">SIGN IN</button>
                </div>

                <!-- Painel da direita (para quando estiver no modo login) -->
                <div class="toggle-panel toggle-right">
                    <h1>Hello, Friend!</h1>
                    <p>Enter your personal details and start your journey with us</p>
                    <!-- Botão que adiciona a classe .active e mostra o cadastro -->
                    <button class="hidden" id="register">SIGN UP</button>
                </div>

            </div>
        </div>

    </div> <!-- fim .container -->

    <!-- JS que controla o efeito de alternar .active -->
    <!-- Se sua pasta for "Js" maiúsculo, troque para ./Js/toggle.js -->
    <script src="./js/toggle.js"></script>

<?php endif; ?>

</body>
</html>
