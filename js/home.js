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

const optionsBtn = document.querySelector('.opciones-btn');

optionsBtn.addEventListener('click', (e) => {
    mostrarOpciones(e.target.parentNode);
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
