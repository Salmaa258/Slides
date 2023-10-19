function mostrarOpciones(event) {
    const opciones = event.target.nextElementSibling;
    if (opciones.style.display === "none" || opciones.style.display === "") {
        opciones.style.display = "block";
        event.target.textContent = "-";
    } else {
        opciones.style.display = "none";
        event.target.textContent = "+";
    }
}    

function mostrarImagen(c) {
    const imagenOverlay = c.querySelector('.imagen-overlay');
    imagenOverlay.style.backgroundImage = 'url("icons/ver.png")';
    imagenOverlay.style.display = 'block';
}

function ocultarImagen(c) {
    const imagenOverlay = c.querySelector('.imagen-overlay');
    imagenOverlay.style.display = 'none';
}