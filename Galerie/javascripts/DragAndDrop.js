/*Script zařizující drag and drop obrázku*/
const image_input = document.querySelector("#img");
image_input.addEventListener("change", function() {
   const reader = new FileReader();
   reader.addEventListener("load", () => {
   const uploaded_image = reader.result;
   document.querySelector("#image_drop_area").style.backgroundImage = `url(${uploaded_image})`;
});

   reader.readAsDataURL(this.files[0]);
});


const image_drop_area = document.querySelector("#img");
var uploaded_image;
image_drop_area.addEventListener('dragover', (event) => {
    event.dataTransfer.dropEffect = 'copy';
 });
 image_drop_area.addEventListener('drop', (event) => {
    const fileList = event.dataTransfer.files;
    readImage(fileList[0]);
 });
 readImage = (file) => {
    const reader = new FileReader();
    reader.addEventListener('load', (event) => {
    uploaded_image = event.target.result;
    document.querySelector("#image_drop_area").style.backgroundImage     = `url(${uploaded_image})`;
    });
    reader.readAsDataURL(file);
 }