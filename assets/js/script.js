// Pratinjau gambar sebelum diunggah pada form tambah/ubah barang
document.addEventListener('DOMContentLoaded', function () {
    var input = document.querySelector('input[type="file"][data-preview]');
    if (!input) return;

    var preview = document.getElementById(input.dataset.preview);
    if (!preview) return;

    input.addEventListener('change', function () {
        var file = input.files && input.files[0];
        if (!file) {
            preview.hidden = true;
            preview.removeAttribute('src');
            return;
        }
        preview.src = URL.createObjectURL(file);
        preview.hidden = false;
    });
});
