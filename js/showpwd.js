const togglePassword = document.querySelector('#togglePassword');
const password = document.querySelector('#password');

togglePassword.addEventListener('click', function (e) {
    // toggle the type attribute
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    // toggle the eye slash icon
    this.classList.toggle('fa-eye-slash');
});

// const pwd = document.getElementById("pwd");
// const chk = document.getElementById("chk");
// chk.onchange = function(e){
//     pwd.type = chk.checked ? "text" : "password";
// };