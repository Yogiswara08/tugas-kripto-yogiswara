function setExample(a, b) {
    document.getElementById('angka1').value = a;
    document.getElementById('angka2').value = b;
}

function resetForm() {
    document.getElementById('angka1').value = '';
    document.getElementById('angka2').value = '';
    window.location.href = window.location.pathname;
}

document.querySelector('form')?.addEventListener('submit', function(e) {
    const angka1 = document.getElementById('angka1').value;
    const angka2 = document.getElementById('angka2').value;

    if (angka1 <= 0 || angka2 <= 0) {
        e.preventDefault();
        alert('Mohon masukkan bilangan bulat positif!');
    }
});
