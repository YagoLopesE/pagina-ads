const container = document.getElementById('container');
const registerBtn = document.getElementById('register');
const loginBtn = document.getElementById('login');

// Ativa o modo "cadastro"
registerBtn.addEventListener('click', ()=>  {
    container.classList.add("active");
});

// Ativa o modo "login"
loginBtn.addEventListener('click', () => {
    container.classList.remove("active");
});

const successBox = document.querySelector('.success-box');
if (successBox) {
  setTimeout(() => {
    container.classList.remove('active'); // volta ao modo default
  }, 3000); // depois de 3s
}
