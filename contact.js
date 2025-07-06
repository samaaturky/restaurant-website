document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("contact-form");
  form.addEventListener("submit", function (e) {
    e.preventDefault(); // Prevent form submission initially

    // Get form values
    const firstName = document.getElementById("first-name").value.trim();
    const lastName = document.getElementById("last-name").value.trim();
    const email = document.getElementById("email").value.trim();
    const message = document.getElementById("message").value.trim();

    let errors = []; // Initialize errors array

    if (!firstName || !lastName || !email || !message) {
      alert("Please fill in all fields.");
      return;
    }

    if (!validateEmail(email)) {
      alert("Please enter a valid email address.");
      return;
    }

    // If there are errors, display them and stop form submission
    if (errors.length > 0) {
      alert(errors.join("\n")); // Show all error messages in an alert
      return;
    }

    function validateEmail(email) {
      const gmailPattern = /^[^\s@]+@gmail\.com$/;
      return gmailPattern.test(email);
    }

    // If all validations pass, submit the form or take further action
    alert("Form submitted successfully!");
    form.reset(); // Optionally reset the form after submission
  });
});

