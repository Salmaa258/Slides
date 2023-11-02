//Función para mostrar opciones de editar, eliminar y clonar presentación
function mostrarOpciones(cajaElement) {
    const opcionesButton = cajaElement.querySelector('.opciones-btn');
    const opciones = cajaElement.querySelector('.opciones');
    if (opciones.style.display !== 'block') {
        opciones.style.display = 'block';
        opcionesButton.textContent = '-';
    } else {
        opciones.style.display = 'none';
        opcionesButton.textContent = '+';
    }
}

const cajaContainer = document.getElementById('global');

cajaContainer.addEventListener('click', (e) => {
    if (e.target.className.includes('opciones-btn')) {
        mostrarOpciones(e.target.parentNode);
    }
});

document.addEventListener('click', (e) => {
    let optionsDisplayTrue;
    try {
        optionsDisplayTrue = document.querySelector(
            '.opciones[style*="display: block"]'
        ).parentElement;
    } catch (error) {
        optionsDisplayTrue = null;
    }

    if (
        !e.target.className.includes('clickable') &&
        optionsDisplayTrue !== null
    ) {
        mostrarOpciones(optionsDisplayTrue);
    }
});

// Función para mostrar el diálogo al hacer clic en el botón de eliminar
function mostrarConfirmacionEliminar(event, form) {
    event.preventDefault();
    const dialog = document.getElementById("confirmarEliminar");
    const overlay = document.getElementById("overlay");

    // Muestra el diálogo
    dialog.style.display = "block";
    overlay.style.display = "block";

    // Agrega un event listener al botón "Aceptar" en el diálogo
    const btnAceptar = document.getElementById("btn-aceptar");
    btnAceptar.addEventListener("click", function () {

        // Cuando el usuario hace clic en "Aceptar", envía el formulario
        form.submit();
        
        // Oculta el diálogo
        dialog.style.display = "none";
        overlay.style.display = "none";
        
    });

    // Agrega un event listener al botón "Cancelar" en el diálogo
    const btnCancelar = document.getElementById("btn-cancelar");
    btnCancelar.addEventListener("click", function () {
        // Oculta el diálogo sin enviar el formulario
        dialog.style.display = "none";
        overlay.style.display = "none";
    });

    return false; // Evita que el formulario se envíe automáticamente
}
