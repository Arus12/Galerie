/* Script zařizující na hlavní stránce tlačítko vytvoření složky*/
function openForm() {
    document.getElementById("myForm").style.display = "block";
    document.getElementById("open-button").style.display = "none";
  }
  
  function closeForm() {
    document.getElementById("myForm").style.display = "none";
    document.getElementById("open-button").style.display = "flex";
}