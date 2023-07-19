function previewImage(event) {
    var reader = new FileReader();
    var imagePreview = document.getElementById('imagePreview');
    reader.onload = function() {
        imagePreview.src = reader.result;
    }
    
    if (event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    } else {
        imagePreview.src = '';
    }
}

