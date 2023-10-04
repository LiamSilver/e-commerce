const img = document.getElementById('imagem');
const titulo = document.getElementById('titulo');
const autor = document.getElementById('autor');
const genero = document.getElementById('genero');
const dataLancamento = document.getElementById('dataLancamento');
const preco = document.getElementById('preco');
const quantidade = document.getElementById('quantidade');
let codigoLivro = 0;
let precoProduto = 0;

const botao = document.getElementById('adicionarAoCarrinho');
botao.addEventListener('click', adicionarAoCarrinho);

function carregarLivro(){
    const URL = window.location.search;
    const urlParam = new URLSearchParams(URL);
    const param = urlParam.get('livro');

    if(param!==''){
        const apiUrl = "http://localhost/e-commerce/API/book/getBookByName.php?livro="+encodeURIComponent(param);

        fetch(apiUrl).then(response => response.json()).then(data => {
            const id = Math.floor(Math.random() * 1000000) + 1;
            img.src = `https://covers.openlibrary.org/b/id/${id}-L.jpg`;
            codigoLivro = data[0].codLivro;
            titulo.textContent = data[0].descLivro;
            autor.textContent = "Autor: " + data[0].nomeAutor;
            genero.textContent = "Gênero: " + data[0].descGenero;
            dataLancamento.textContent = "Data de Lançamento: " + data[0].dt_lancamento;
            preco.textContent = "Preço: R$ " + data[0].preco;
            precoProduto = data[0].preco;
        }).catch(e => console.error(e));
    }
}
function adicionarAoCarrinho() {
    var dadosLivros = {
      codLivro: codigoLivro,
      src: img.src,
      titulo: titulo.textContent,
      preco: precoProduto,
      quantidade: quantidade.value
    };
  
var livrosString = localStorage.getItem('livros');
var livros = [{}];
livros=JSON.parse(livrosString);
livros.push(dadosLivros);
localStorage.setItem('livros', JSON.stringify(livros));
  
contadorInput.value = livros.length-1;

}
  carregarLivro();
  carregaContador();


