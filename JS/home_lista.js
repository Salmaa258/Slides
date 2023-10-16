function mostrarOpciones() {
    var opciones = event.target.nextElementSibling;
    if (opciones.style.display === "none" || opciones.style.display === "") {
        opciones.style.display = "block";
    } else {
        opciones.style.display = "none";
    }
}    

function mostrarImagen(c) {
    const imagenOverlay = c.querySelector('.imagen-overlay');
    imagenOverlay.style.backgroundImage = 'url("../icons/ver.png")';
    imagenOverlay.style.display = 'block';
}

function ocultarImagen(c) {
    const imagenOverlay = c.querySelector('.imagen-overlay');
    imagenOverlay.style.display = 'none';
}