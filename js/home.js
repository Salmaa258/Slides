document.addEventListener("DOMContentLoaded", function() {
  // Realiza una solicitud AJAX para verificar si hay registros en la tabla "Presentacions"
  const xhr = new XMLHttpRequest();
  xhr.open("GET", '../php/db/read/verificar_recursos.php', true);
  xhr.onreadystatechange = function() {
      if (xhr.readyState === 4 && xhr.status === 200) {
          const response = JSON.parse(xhr.responseText);

          // Obtiene una referencia al botón "btn-crearPresentacion" por su ID
          const btnCrearPresentacion = document.getElementById("btn-crearPresentacion");

          // Si hay registros en la tabla "Presentacions", oculta el botón
          if (response.hasRecords) {
              btnCrearPresentacion.style.display = "none";
          }
      }
  };
  xhr.send();
});


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


