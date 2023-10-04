const contador = document.getElementById('contador1');
const livros = JSON.parse(localStorage.getItem('livros')) || [];
contador.value = livros.length;

function isObjectEmpty(obj) {
    return !Object.entries(obj).length;
  }
  
function criarTabelaProdutos() {
    const carrinhoProdutos = document.getElementById('carrinhoProdutos');
    carrinhoProdutos.innerHTML = '';
    let somaPrecosTotais=0;

  
  
    if (livros.length === 0) {
      const mensagem = document.createElement('p');
      mensagem.textContent = 'Não há produtos no carrinho.';
      carrinhoProdutos.appendChild(mensagem);
      return;
    }
  
    if(isObjectEmpty(livros[0])){
        livros.shift();
        localStorage.setItem('livros', JSON.stringify(livros));
        window.location.reload();
    }
    


    const tabela = document.createElement('table');
    tabela.id = 'table';
  
    // Criar a cabeçalho da tabela
    const cabecalho = document.createElement('tr');
    const img = document.createElement('th');
    img.textContent = 'Imagem';
    img.id = 'th';
    const titulo = document.createElement('th');
    titulo.textContent = 'Título';
    titulo.id = 'th';
    const qtd = document.createElement('th');
    qtd.textContent = 'Quantidade';
    qtd.id = 'th';
    const preco = document.createElement('th');
    preco.textContent = 'Preço R$';
    preco.id = 'th';
    const precoTotal = document.createElement('th');
    precoTotal.textContent = 'Preço Total R$';
    precoTotal.id = 'th';
    const botao = document.createElement('th');
    botao.textContent = 'Ação';
    botao.id = 'th';
    cabecalho.appendChild(img);
    cabecalho.appendChild(titulo);
    cabecalho.appendChild(qtd);
    cabecalho.appendChild(preco);
    cabecalho.appendChild(precoTotal);
    cabecalho.appendChild(botao);
    tabela.appendChild(cabecalho);
  
    // Adicionar os dados de cada livro na tabela
    livros.forEach(function (livro, index) {
      const linha = document.createElement('tr');
  
      // Coluna da imagem
      const colunaImagem = document.createElement('td');
      colunaImagem.classList.add('produtoImg');
      const imagem = document.createElement('img');
      imagem.src = livro.src;
      imagem.alt = 'Capa do livro';
      imagem.style.width = '100px';
      imagem.style.height = '100px';
      colunaImagem.appendChild(imagem);
      linha.appendChild(colunaImagem);
  
      // Coluna do título
      const colunaTitulo = document.createElement('td');
      colunaTitulo.classList.add('produtoTitulo');
      colunaTitulo.textContent = livro.titulo;
      linha.appendChild(colunaTitulo);
  
      // Coluna da quantidade
      const colunaQuantidade = document.createElement('td');
      colunaQuantidade.classList.add('produtoQtd');
      colunaQuantidade.textContent = livro.quantidade;
      linha.appendChild(colunaQuantidade);
  
      // Coluna do preço
      const colunaPreco = document.createElement('td');
      colunaPreco.classList.add('produtoPreco');
      colunaPreco.textContent = livro.preco ? livro.preco.toFixed(2) : '';
      linha.appendChild(colunaPreco);
  
     // Coluna do preço total
     const colunaPrecoTotal = document.createElement('td');
     colunaPrecoTotal.classList.add('produtoPrecoTotal');
     colunaPrecoTotal.textContent = (livro.preco * livro.quantidade).toFixed(2);
     linha.appendChild(colunaPrecoTotal);
 
     // Coluna do botão
     const colunaAcao = document.createElement('td');
     const botaoRemover = document.createElement('button');
     botaoRemover.classList.add('produtoRemover');
     botaoRemover.textContent = 'Remover';
     botaoRemover.addEventListener('click', function () {
        const codLivro = livro.codLivro;
      
        const index = livros.findIndex(livro => livro.codLivro === codLivro);
      
        if (index !== -1) {
          livros.splice(index, 1);
          if(livros.length === 0){
            livros.push({});
            localStorage.removeItem('livros');
            window.location.reload();
          }
          else{
              localStorage.setItem('livros', JSON.stringify(livros));
              tabela.deleteRow(index);
              window.location.reload();
          }
        }
      });
           colunaAcao.appendChild(botaoRemover);
     linha.appendChild(colunaAcao);
 
     tabela.appendChild(linha);

     somaPrecosTotais += livro.preco * livro.quantidade;

   });
 
   carrinhoProdutos.appendChild(tabela);

   const valorCompra = document.createElement('p');
   valorCompra.textContent = `Valor Total do Pedido: R$ ${somaPrecosTotais.toFixed(2)}`;
   valorCompra.style.textAlign = 'center';
   carrinhoProdutos.appendChild(valorCompra);
 }
 
 function finalizarPedido() {
    const token = localStorage.getItem('jwt');
  
    if (token) {
        const xhr = new XMLHttpRequest();
        const sale = `http://localhost/e-commerce/API/sales/createSale.php?token=${encodeURIComponent(token)}`;
        xhr.open('POST', 'http://localhost/e-commerce/API/auth/decodeToken.php?token=' + encodeURIComponent(token));
        xhr.setRequestHeader('Content-Type', 'application/json');

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const dadosTemporarios = JSON.parse(xhr.responseText);

                const codUsuario = dadosTemporarios.id;

                const livros = JSON.parse(localStorage.getItem('livros'));
 
                const itens = livros.map(livro => ({
                    codLivro: livro.codLivro,
                    quantidade: livro.quantidade,
                    valorUnitario: livro.preco
                }));

            let somaPrecosTotais = 0;
            for (const livro of livros) {
            somaPrecosTotais += livro.quantidade * livro.preco;
            }
 
            const pedido = {
                codUsuario: codUsuario,
                valor: somaPrecosTotais,
                dt_compra: new Date().toISOString().split('T')[0], 
                itens: itens
            };
 
            fetch(sale, {
                method: 'POST',
                headers: {
                  'Content-Type': 'application/json'
                },
                body: JSON.stringify(pedido)
              })
                .then(response => {
                  if (response.ok) {
                    localStorage.removeItem('livros');
                    return response.json();
                  } else {
                    return response.json().then(data => {
                      throw new Error(data.message);
                    });
                  }
                })
                .then(data => {
                  alert('Compra realizada com sucesso!');
                  location.reload();
                })
                .catch(error => {
                  alert(`Erro na requisição: ${error.message}`);
                });
                                          
              
      }
    };

    xhr.send(JSON.stringify({ token: token }));
    
    } else {
      alert('Faça login antes de finalizar o pedido');
    }
  }
 criarTabelaProdutos(); 