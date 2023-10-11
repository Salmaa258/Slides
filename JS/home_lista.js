function mostrarOpciones() {
    var opciones = event.target.nextElementSibling;
    if (opciones.style.display === "none" || opciones.style.display === "") {
        opciones.style.display = "block";
    } else {
        opciones.style.display = "none";
    }
}    