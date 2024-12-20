const dragArrow = document.getElementById('dragArrow');
const loginForm = document.getElementById('loginForm');
const registerForm = document.getElementById('registerForm');

dragArrow.addEventListener('dragstart', (e) => {
    e.dataTransfer.setData('text/plain', null);
});

dragArrow.addEventListener('dragend', () => {
    loginForm.classList.add('show');
});

dragArrow.addEventListener('click', () => {
    loginForm.classList.toggle('show');
});

document.getElementById('showRegister').addEventListener('click', (event) => {
    event.preventDefault();
    loginForm.classList.remove('show');
    registerForm.classList.add('show');
});

document.getElementById('showLogin').addEventListener('click', (event) => {
    event.preventDefault();
    registerForm.classList.remove('show');
    loginForm.classList.add('show');
});

document.addEventListener('click', (event) => {
    const isClickInsideArrow = dragArrow.contains(event.target);
    const isClickInsideLoginForm = loginForm.contains(event.target);
    const isClickInsideRegisterForm = registerForm.contains(event.target);

    if (!isClickInsideArrow && !isClickInsideLoginForm && !isClickInsideRegisterForm) {
        loginForm.classList.remove('show');
        registerForm.classList.remove('show');
    }
});

if ("<?php echo $message; ?>" !== "") {
    const messageElement = document.querySelector('.message');
    setTimeout(() => {
        messageElement.style.display = 'none';
    }, 3000);
}

document.addEventListener("DOMContentLoaded", function() {
    feather.replace();
});