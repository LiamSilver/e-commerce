const token_jwt = localStorage.getItem('jwt');

function carregarCompras() {
    fetch(`http://localhost/e-commerce/API/sales/getSaleByUser.php?token=${token_jwt}`)
      .then(response => response.json())
      .then(data => {
        console.log(data);
        const tabela = document.getElementById("tabela");
  
        const theadExistente = tabela.querySelector("thead");
        const tbodyExistente = tabela.querySelector("tbody");
        if (theadExistente) {
          tabela.removeChild(theadExistente);
        }
        if (tbodyExistente) {
          tabela.removeChild(tbodyExistente);
        }
        const colunasCabecalho = Object.keys(data[0]);
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
            const values = Object.values(item);
            values.forEach(valor => {
                const cell = document.createElement("td");
                cell.textContent = valor;
                row.appendChild(cell);
            });
            
            corpo.appendChild(row);
        });
        tabela.appendChild(corpo);
    })
      .catch(error => {
        console.error("Ocorreu um erro na requisição:", error);
      });
  }
    carregarCompras();
  