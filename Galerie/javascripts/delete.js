/* Funkce která odešle deleteItem.php info o souboru, který chce uživatel smazat. 
   Ještě před smazáním se uživatele zeptá, zdali to chce opravu udělat */
function deleteFile(item) {
  let yesorno = confirm("Opravdu chcete tuto složku smazat?");
  console.log(yesorno);
  if (yesorno) {
    var formdata = "item=" + item + "&folder/image=" + "FOLDER";
    $.ajax({
      type: "POST",
      url: "php/admin/deleteItem.php",
      data: formdata,
      cache: false,
      success: function (html) {
        console.log(html);
        location.reload();
      },
    });
  } else {
    console.log("nic");
  }
  return false;
}


/* Funkce která odešle deleteItem.php info o obrázku, který chce uživatel smazat. 
   Ještě před smazáním se uživatele zeptá, zdali to chce opravu udělat */
function deleteImage(item, folder) {
  let yesorno = confirm("Opravdu chcete tento obrázek smazat?");
  console.log(yesorno);
  if (yesorno) {
    var formdata =
      "item=" + item + "&folder/image=" + "IMAGE" + "&folderid=" + folder;
    $.ajax({
      type: "POST",
      url: "php/admin/deleteItem.php",
      data: formdata,
      cache: false,
      success: function (html) {
        console.log(html);
        location.reload();
      },
    });
  } else {
    console.log("nic");
  }
  return false;
}
