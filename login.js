const showPasswordCheckbox = document.getElementById("show_password");

const passwordField = document.getElementById("password");

// Toggle visibility for Password Field
showPasswordCheckbox.addEventListener("change", function () {
  passwordField.type = this.checked ? "text" : "password";
});
