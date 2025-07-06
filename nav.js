const sidebartoggle = document.getElementById("sidebar-toggle");
const sidebar = document.getElementById("sidebar");
const overlay = document.getElementById("overlay");

// Toggle sidebar and overlay on hamburger menu click
sidebartoggle.addEventListener("click", () => {
  sidebar.classList.toggle("active");
  overlay.classList.toggle("active");
});

// Close sidebar when clicking outside (on the overlay)
overlay.addEventListener("click", () => {
  sidebar.classList.remove("active");
  overlay.classList.remove("active");
});

// The Scroll js
const scrollToTopButton = document.getElementById("scroll-to-top");

// When the user clicks on the icon, scroll to the top of the page
scrollToTopButton.addEventListener("click", function (e) {
  e.preventDefault(); // Prevent the default link behavior
  window.scrollTo({
    top: 0,
    behavior: "smooth", // Smooth scroll to the top
  });
});
