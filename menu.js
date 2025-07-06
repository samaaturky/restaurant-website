// Select all cards and menu sections
const cards = document.querySelectorAll(".menu-card");
const sections = document.querySelectorAll(".menu-items");

// Add click event to each card
cards.forEach((card) => {
  card.addEventListener("click", () => {
    const category = card.getAttribute("data-category");

    // Hide all sections
    sections.forEach((section) => section.classList.remove("active"));

    // Show the matching section
    const activeSection = document.querySelector(
      `.menu-items[data-category="${category}"]`
    );
    activeSection.classList.add("active");
  });
});
