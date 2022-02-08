/*Funkce, která umožnuje stahování obrázku*/
function download(email,image) {
    axios({
          url: "imgs/"+email+"/"+image,
          method: 'GET',
          responseType: 'blob'
    })
          .then((response) => {
                const url = window.URL
                .createObjectURL(new Blob([response.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', image);
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
          })
}