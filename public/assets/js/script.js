function previewImage() {
    const gambar = document.querySelector('#gambar');
    const imgPreview = document.querySelector('#imgPreview');

    const fileGambar = new FileReader();
    fileGambar.readAsDataURL(gambar.files[0]);

    fileGambar.onload = function(e) {
        imgPreview.src = e.target.result;
    }
}
