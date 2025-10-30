// Aguarda o carregamento completo da página antes de executar o script
window.addEventListener("load", function() {
    // Seleciona todos os elementos de projeto
 const projetos = document.getElementsByClassName("projeto");
 // Campo de busca usado para filtrar projetos
 const filtroInput = document.getElementById("filtro-projetos");
 // Elemento onde o contador de projetos visíveis será exibido
 const contador = document.getElementById("contador");

/**
     * Atualiza o contador exibindo quantos projetos estão visíveis.
     * Conta apenas os elementos que não estão com display: none.
     */

 function atualizarcontador () {
    let visiveis = 0;
    for (let i=0;i<projetos.length;i++) {
        if (projetos[i].style.display !== "none") visiveis++;

    }
    contador.textContent = `Projetos disponiveis: ${visiveis}`;
 }

   /**
     * Adiciona um evento de escuta ao campo de filtro.
     * Sempre que o usuário digita algo, os projetos são filtrados.
     */

    filtroInput.addEventListener("input", function() {

         // Texto digitado pelo usuário, convertido para minúsculas 
        const filtro = this.value.toLowerCase();
        // Percorre todos os projetos
        for (let i=0;i<projetos.length;i++) {
             // Texto digitado pelo usuário, convertido para minúsculas
            const titulo = projetos[i].querySelector("h2").textContent.toLowerCase();

            // Exibe ou oculta o projeto de acordo com o filtro
            projetos[i].style.display = titulo.includes(filtro) ? "flex" : "none";
        }
        // Atualiza o contador após o filtro ser aplicado
        atualizarcontador();
    });
    // Atualiza o contador na primeira carga da página
    atualizarcontador();
});