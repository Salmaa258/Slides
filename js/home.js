//Función para mostrar opciones de editar, eliminar y clonar presentación
function mostrarOpciones(event) {
  const opciones = event.target.nextElementSibling;
  if (opciones.style.display !== 'block') {
    opciones.style.display = 'block';
    event.target.textContent = '-';
  } else {
    opciones.style.display = 'none';
    event.target.textContent = '+';
  }
}

//Función para mostrar la imagen para visualizar presentaciones cuando el ratón pase por encima de éstas
function mostrarImagen(c) {
  const imagenOverlay = c.querySelector('.imagen-overlay');
  imagenOverlay.style.backgroundImage = 'url("icons/ver.png")';
  imagenOverlay.style.display = 'block';
}
function ocultarImagen(c) {
  const imagenOverlay = c.querySelector('.imagen-overlay');
  imagenOverlay.style.display = 'none';
}
