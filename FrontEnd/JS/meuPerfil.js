const token = localStorage.getItem('jwt');
const entrarLink = document.getElementById("entrarLink");
const registrarlink = document.getElementById('registrarLink');
const nomePerfil = document.getElementById('nomePerfil');
const emailPerfil = document.getElementById('emailPerfil');

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
            const { nome, admin, email } = response;
        
            entrarLink.textContent = `Olá, ${nome}`;
            entrarLink.href = admin === 1 ? "meuPerfilAdm.html" : "meuPerfil.html";
            registrarlink.textContent = "Sair";
            registrarlink.addEventListener('click', handleClick);
            nomePerfil.innerHTML = "<strong>Nome:</strong> " + nome;
            emailPerfil.innerHTML = "<strong>Email:</strong> " + email;
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
          window.location.href="Home.html";
        } 
      }

    
      loadPage();
      