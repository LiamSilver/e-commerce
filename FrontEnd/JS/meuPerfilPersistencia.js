const token_jwt = localStorage.getItem('jwt');

function carregarDados(url){
    fetch(url)
    .then(response => response.json())
    .then(data => {
      const tabela = document.getElementById("tabela");

      const theadExistente = tabela.querySelector("thead");
      const tbodyExistente = tabela.querySelector("tbody");
      if (theadExistente) {
        tabela.removeChild(theadExistente);
      }
      if (tbodyExistente) {
        tabela.removeChild(tbodyExistente);
      }

      var colunasCabecalho = Object.keys(data[0]);
      if (url === `http://localhost/e-commerce/API/user/getUser.php?token=${token_jwt}`) {
          colunasCabecalho = Object.keys(data[0]).slice(0, -1);
      }
      
      const cabecalho = document.createElement("thead");
      const cabecalhoRow = document.createElement("tr");
      colunasCabecalho.forEach(key => {
        const cabecalhoCell = document.createElement("th");
        cabecalhoCell.textContent = key;
        cabecalhoRow.appendChild(cabecalhoCell);
      });

      cabecalho.appendChild(cabecalhoRow);
      tabela.appendChild(cabecalho);

      const corpo = document.createElement("tbody");
      data.forEach(item => {
        const row = document.createElement("tr");
        var values = Object.values(item);
        if (url === `http://localhost/e-commerce/API/user/getUser.php?token=${token_jwt}`) {
            values = Object.values(item).slice(0, -1);
        }
  
        values.forEach(valor => {
          const cell = document.createElement("td");
          cell.textContent = valor;
          row.appendChild(cell);
        });

        const editar = document.createElement("td");
        const editarButton = document.createElement("button");
        editarButton.textContent = "Editar";
        editarButton.addEventListener("click", () => {
          editarUsuario(item.codUsuario);
        });
        editar.appendChild(editarButton);
        row.appendChild(editar);

        const excluir = document.createElement("td");
        const excluirButton = document.createElement("button");
        excluirButton.textContent = "Excluir";
        excluirButton.addEventListener("click", () => {
          excluirUsuario(item.id);
        });
        excluir.appendChild(excluirButton);
        row.appendChild(excluir);

        corpo.appendChild(row);
      });
      tabela.appendChild(corpo);
    })
    .catch(error => {
      console.error("Ocorreu um erro na requisição:", error);
    });

}
function carregarUsuarios() {
    const usuario = (`http://localhost/e-commerce/API/user/getUser.php?token=${token_jwt}`);
    carregarDados(usuario);
}
 

  function carregarLivros() {
    const livros = (`http://localhost/e-commerce/API/book/getBook.php`);
    carregarDados(livros);
  }

  function carregarGeneros() {
    const genero= (`http://localhost/e-commerce/API/genre/getGenre.php`);
    carregarDados(genero);
  }
  
  function carregarCompras() {
    const compras=(`http://localhost/e-commerce/API/sales/getSale.php?token=${token_jwt}`);
    carregarDados(compras);
  }
  function carregarAutores() {
    const autores = (`http://localhost/e-commerce/API/author/getAuthor.php`);
    carregarDados(autores);
  }
  
    function editarUsuario(id) {  
    }
    
  function excluirUsuario(id) {
  }
  
  carregarUsuarios();