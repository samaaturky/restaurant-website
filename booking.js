const today = new Date();
const maxDate = new Date();

maxDate.setMonth(today.getMonth() + 4); // Add 4 months to today

// Set min and max attributes in one line
document
  .getElementById("date")
  .setAttribute("min", today.toISOString().split("T")[0]);
document
  .getElementById("date")
  .setAttribute("max", maxDate.toISOString().split("T")[0]);
