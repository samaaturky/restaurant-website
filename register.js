// Get DOM elements
const showPasswordCheckbox = document.getElementById("show_password");
const showConfirmPasswordCheckbox = document.getElementById(
  "show_confirm_password"
);
const passwordField = document.getElementById("password");
const confirmPasswordField = document.getElementById("confirm_password");

// Toggle visibility for Password Field
showPasswordCheckbox.addEventListener("change", function () {
  passwordField.type = this.checked ? "text" : "password";
});

// Toggle visibility for Confirm Password Field
showConfirmPasswordCheckbox.addEventListener("change", function () {
  confirmPasswordField.type = this.checked ? "text" : "password";
});
