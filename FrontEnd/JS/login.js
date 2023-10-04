const mostrarSenhaCheckbox = document.getElementById('mostrarSenha');
const emailInput = document.getElementById('email');
const senhaInput = document.getElementById('senha');

mostrarSenhaCheckbox.addEventListener('change', function() {
  if (mostrarSenhaCheckbox.checked) {
    senhaInput.setAttribute('type', 'text');
  } else {
    senhaInput.setAttribute('type', 'password');
  }
});

const form = document.querySelector('form');
const responseMessage = document.getElementById('responseMessage');

form.addEventListener('submit', function(event) {
    event.preventDefault();
  
    const formData = new FormData(form);
    const xhr = new XMLHttpRequest();
  
    xhr.open('POST', form.getAttribute('action'));
    xhr.setRequestHeader('Content-Type', 'application/json');

    const jsonPayload = JSON.stringify(Object.fromEntries(formData));
    xhr.send(jsonPayload);
  
    xhr.onreadystatechange = function() {
      if (xhr.readyState === 4) {
        const response = JSON.parse(xhr.responseText);

        if (xhr.status === 200) {
          // Sucesso
          responseMessage.textContent = response.message;
          localStorage.setItem('jwt', response.token);

          responseMessage.classList.remove('error');

          window.location.href="Home.html";
        } else {
            // Erro
          responseMessage.textContent = response.message;
          responseMessage.classList.add('error');
        }
      }
    };
  
  });
  
