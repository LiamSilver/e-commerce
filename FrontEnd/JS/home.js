
const contadorInput = document.getElementById('contador');
const token = localStorage.getItem('jwt');
const entrarLink = document.getElementById("entrarLink");
const registrarlink = document.getElementById('registrarLink');

//Menu Lateral Generos
function menuLateral(){

  fetch("http://localhost/e-commerce/API/genre/getGenre.php")
    .then(response => response.json())
    .then(genres => {
      const genresList = document.getElementById("genero");
      genres.forEach(genre => {
        const li = document.createElement("li");
        const a = document.createElement("a");
        a.href = "#";
        a.textContent = genre.descGenero;
        li.appendChild(a);
        genresList.appendChild(li);
        
        li.addEventListener('click', function(event) {
          const desc = event.target.textContent;
          fetchBookListByGenre(desc);
        });
      });
    });

    fetch("http://localhost/e-commerce/API/author/getAuthor.php")
    .then(response => response.json())
    .then(authors => {
      const authorList = document.getElementById("autores");
      authors.forEach(author => {
        const li = document.createElement("li");
        const a = document.createElement("a");
        a.href = "#";
        a.textContent = author.nomeAutor;
        li.appendChild(a);
        authorList.appendChild(li);
    
        li.addEventListener('click', function(event) {
          const name = event.target.textContent;
          fetchBookListByAuthor(name);
        });
    
      });
    });
}

//Busca por genero
  function fetchBookListByGenre(desc) {
    fetch(`http://localhost/e-commerce/API/book/getBookByGenre.php?genre=${desc}`)
      .then(response => response.json())
      .then(data => {
        const bookList = data.map(book => {
          const id = Math.floor(Math.random() * 1000000) + 1;
          const img = `https://covers.openlibrary.org/b/id/${id}-L.jpg`;
          return {
            image: img,
            name: book.descLivro,
            price: book.preco
          };
        });
        displayBookList(bookList);
      })
      .catch(error => console.error(error));
  }

    //Busca por Autor
    function fetchBookListByAuthor(name) {
      fetch(`http://localhost/e-commerce/API/book/getBookByAuthor.php?author=${name}`)
        .then(response => response.json())
        .then(data => {
          const bookList = data.map(book => {
            const id = Math.floor(Math.random() * 1000000) + 1;
            const img = `https://covers.openlibrary.org/b/id/${id}-L.jpg`;
            return {
              image: img,
              name: book.descLivro,
              price: book.preco
            };
          });
          displayBookList(bookList);
        })
        .catch(error => console.error(error));
    }
  
// Auto Completa Barra de Busca
const searchBar = document.getElementById('barraBuscaInput');
const autocompleteMenu = document.getElementById('autoMenu');

searchBar.addEventListener('input', function() {
  const query = this.value;
  if (query.length < 3) { 
    autocompleteMenu.innerHTML = '';
    return;
  }

fetch(`http://localhost/e-commerce/API/book/getBookbyLetter.php?name=${query}`) 
  .then(response => response.json())
    .then(data => {

      if (!data.length) {
        autocompleteMenu.innerHTML = '';
        return;
      }

      const results = data.map(result => `<li>${result}</li>`).join('');
      autocompleteMenu.innerHTML = results;
    })
    .catch(error => console.error(error));
});

searchBar.addEventListener('focusout', function() {
  setTimeout(() => {
    autocompleteMenu.innerHTML = '';
  }, 500);
});

autocompleteMenu.addEventListener('click', function(event) {
  if (event.target.tagName === 'LI') {
    searchBar.value = event.target.innerText;
    autocompleteMenu.innerHTML = '';
  }
});

//Lista de livros

function fetchBookList() {
  fetch('http://localhost/e-commerce/API/book/getBook.php')
    .then(response => response.json())
    .then(data => {
      const bookList = data.map(book => {
        const id = Math.floor(Math.random() * 1000000) + 1;
        const img = `https://covers.openlibrary.org/b/id/${id}-L.jpg`;
        return {
          image: img,
          name: book.descLivro,
          price: book.preco
        };
      });
      displayBookList(bookList);
    })
    .catch(error => console.error(error));
}

function displayBookList(bookList) {
  const bookListElement = document.querySelector('.books');
  bookListElement.innerHTML = '';

  bookList.forEach(book => {
    const bookItem = document.createElement('li');
    bookItem.classList.add('bookItem');

    const bookLink = document.createElement('a');
    bookLink.href = "Produtos.html?livro="+encodeURIComponent(book.name);

    const bookImage = document.createElement('img');
    bookImage.classList.add('bookImage');
    bookImage.src = book.image;
    bookImage.alt = book.name;

    const bookTitle = document.createElement('h3');
    bookTitle.classList.add('bookTitle');
    bookTitle.textContent = book.name;

    const bookPrice = document.createElement('p');
    bookPrice.classList.add('bookPrice');
    bookPrice.textContent = `R$ ${book.price.toFixed(2)}`;

    bookLink.appendChild(bookImage);
    bookItem.appendChild(bookLink); 
    bookItem.appendChild(bookTitle);
    bookItem.appendChild(bookPrice);
    bookListElement.appendChild(bookItem);
  });
}

//Buscar livro pelo botão

function buscarLivro(){
  const param = document.getElementById('barraBuscaInput').value;

  if(param.textContent !== ''){
    window.location.href = "Produtos.html?livro="+encodeURIComponent(param);
  }
}

//Buscar pela barra de busca

function verificarTecla(event) {
  if (event.keyCode === 13) {
    buscarLivro();
  }
}

const url = window.location.href;
const urlComp = "Home.html"

if(url.includes(urlComp)){
  fetchBookList();
  menuLateral();
}

function carregaContador() {
  var localStorageLivro = localStorage.getItem('livros');
  var lsLivros = [{}];

  if (localStorageLivro) {
    lsLivros = JSON.parse(localStorageLivro);
  }

  localStorage.setItem('livros', JSON.stringify(lsLivros));

  if (lsLivros.length === 0) {
    contadorInput.value = 0;
  } else {
    contadorInput.value = lsLivros.length-1;
  }
}

// Login
function loadPage(){
  if (token) {
    try {
      const xhr = new XMLHttpRequest();
      const url = 'http://localhost/e-commerce/API/auth/decodeToken.php?token=' + encodeURIComponent(token);
  
      xhr.open('GET', url);
  
      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
          const response = JSON.parse(xhr.responseText);
          const { nome, admin } = response;
      
          entrarLink.textContent = `Olá, ${nome}`;
          entrarLink.href = admin === 1 ? "meuPerfilAdm.html" : "meuPerfil.html";
          registrarlink.textContent = "Sair";
          registrarlink.addEventListener('click', handleClick);
        }
      };
        
      xhr.send();
    } catch (error) {
      console.error("Erro ao fazer requisição:", error);
    }
  }
  }

  function handleClick(event) {
    event.preventDefault();
  
    if (registrarLink.textContent === 'Sair') {
      localStorage.removeItem('jwt');
      localStorage.removeItem('livros');
      window.location.reload();
    } 
  }

  loadPage();
  carregaContador();



