/* Zjistí a uloží si všechny ID obrázků vygenerované v PHP */
var modal = document.getElementById("myModal");
x = -1;
for(let i = 0; i > x; i++){
    if(document.getElementById('myImg' + i) != null){
        console.log("myImg" + i);
        console.log(i);
        var img = document.getElementById("myImg" + i);
        var modalImg = document.getElementById("img01");
        var captionText = document.getElementById("caption");
        img.onclick = function(){
          modal.style.display = "block";
          modalImg.src = this.src;
          captionText.innerHTML = this.alt;
}
    }else{
      x = i + 10;
    }
}


/* Uloží si span, který uzavírá modal */
var span = document.getElementsByClassName("close")[0];

/* Když uživatel klikne na span, modal se zavře */
span.onclick = function() { 
  modal.style.display = "none";
}