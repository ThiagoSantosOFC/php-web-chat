let password = document.getElementById("password");
let confirmpass = document.getElementById("confirmpass");
let submit = document.getElementById("submit");
let form = document.getElementById("form");


function validatePassword() {
  if (password.value != confirmpass.value) {
    confirmpass.setCustomValidity("Passwords não coincidem!");
  } else {
    confirmpass.setCustomValidity("");
  }
}

password.onchange = validatePassword;
confirmpass.onkeyup = validatePassword;

submit.onclick = function () {
  validatePassword();
  if (confirmpass.checkValidity()) {
    console.log("Passwords coincidem");
  

  } else {
    
    console.log("Passwords não coincidem");
  }
};
