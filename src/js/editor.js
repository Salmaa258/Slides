const diapositivasContainer = document.getElementById('diapositivas');
const diapositivaTitulo = document.getElementById('d_titulo_template');
const diapositivaTituloTexto = document.getElementById('d_titulo_texto_template');
let tipoDiapositiva = '';

// Crea y añade una nueva diapositiva de tipo "título" al contenedor principal.
const newTipoTitulo = () => {
    const diapositiva = diapositivaTitulo.content.cloneNode(true);
    const diapositivaContainer = diapositiva.querySelector('.d-container');

    diapositivaContainer.querySelector('input[type="text"]').name = 'd_titulo_' + numDiapositivas;

    diapositivasContainer.append(diapositivaContainer);
    numDiapositivas++;
};

let numDiapositivas = document.getElementById('diapositivas');
numDiapositivas = numDiapositivas.getAttribute('lastDiapositivaId');
// Comprobamos si estamos creando una diapositiva nueva o editando una existente.
if (numDiapositivas === '0') {
    newTipoTitulo();
    numDiapositivas = 1;
} else {
    numDiapositivas = parseInt(numDiapositivas) + 1;
}

// Crea y añade una nueva diapositiva que tiene un título y un área de texto.
const newTipoContenido = () => {
    const diapositiva = diapositivaTituloTexto.content.cloneNode(true);
    const diapositivaContainer = diapositiva.querySelector('.d-container');

    diapositivaContainer.querySelector('input[type="text"]').name = 'd_titulo_' + numDiapositivas;
    diapositivaContainer.querySelector('textarea').name = 'd_contenido_' + numDiapositivas;

    diapositivasContainer.append(diapositivaContainer);
    numDiapositivas++;
};

//Funcion para mostrar la confirmación del feedback
const mostrarConfirmacionNuevaDiapositiva = (event, tipo) => {
    event.preventDefault();
    const dialog = document.getElementById('confirmarGuardar');
    const overlay = document.getElementById('overlay');

    // Recordamos el tipo de diapositiva que se va a agregar
    tipoDiapositiva = tipo;

    // Muestra el diálogo
    dialog.style.display = 'block';
    overlay.style.display = 'block';

    // Agrega un event listener al botón "Aceptar" en el diálogo
    const btnAceptar = document.getElementById('btn-aceptar');
    btnAceptar.addEventListener('click', () => {
        // Oculta el diálogo
        dialog.style.display = 'none';
        overlay.style.display = 'none';

        // Agregamos la Diapositiva seleccionada según el tipo recordado
        if (tipoDiapositiva === 'titulo') {
            newTipoTitulo();
        } else if (tipoDiapositiva === 'tituloTexto') {
            newTipoContenido();
        }

        // Reseteamos el tipo de diapositiva
        tipoDiapositiva = '';
    });

    // Agrega un event listener al botón "Cancelar" en el diálogo
    const btnCancelar = document.getElementById('btn-cancelar');
    btnCancelar.addEventListener('click', () => {
        // Oculta el diálogo sin llamar a la función
        dialog.style.display = 'none';
        overlay.style.display = 'none';

        if (tipoDiapositiva) {
            // Si hay un tipo de diapositiva recordado, inserta en la base de datos pero sin guardar los cambios
            if (tipoDiapositiva === 'titulo') {
                newTipoTitulo();
            } else if (tipoDiapositiva === 'tituloTexto') {
                newTipoContenido();
            }
        }

        // Reseteamos el tipo de diapositiva
        tipoDiapositiva = '';
    });

    return false; // Evita que el evento del enlace se propague
};

// //Botón guardar la presentación
// const btnGuardar = document.getElementById("btn-guardar");
// btnGuardar.addEventListener("click", (event) => {
//   event.preventDefault();
//   const overlay = document.getElementById("overlay");
//   const exitoDialog = document.getElementById("exito_guardar");

//   // Mostrar el diálogo de éxito
//   exitoDialog.style.display = "block";
//   overlay.style.display = "block";

//   // Agrega un event listener al botón "Aceptar" en el diálogo de éxito
//   const btnAceptarExito = document.getElementById("btn-aceptar-exito");
//   btnAceptarExito.addEventListener("click", (form) => {

//     // Cuando el usuario hace clic en "Aceptar", envía el formulario
//     form.submit();

//     // Oculta el diálogo de éxito
//     exitoDialog.style.display = "none";
//     overlay.style.display = "none";
//   });
// });

//Llamada a la función que muestra el feedback pasando el tipo de diapositiva "Titulo"
const mostrarConfirmacionNuevaDiapositivaTitulo = (event) => {
    mostrarConfirmacionNuevaDiapositiva(event, 'titulo');
};

//Llamada a la función que muestra el feedback pasando el tipo de diapositiva "Titulo + Texto"
const mostrarConfirmacionNuevaDiapositivaTituloTexto = (event) => {
    mostrarConfirmacionNuevaDiapositiva(event, 'tituloTexto');
};

// Evento para cerrar desplegables al hacer click fuera del mismo.
document.addEventListener('click', (event) => {
    const isClickInsideDropdown = !!event.target.closest('.dropdown');
    const isClickOnDropdownContent = !!event.target.closest('.dropdown-content');

    if (!isClickInsideDropdown || isClickOnDropdownContent) {
        closeAllDropdowns();
    }
});

// Cierra todos los desplegables.
const closeAllDropdowns = () => {
    const allDropdownContents = document.querySelectorAll('.dropdown-content');
    allDropdownContents.forEach((content) => {
        content.style.display = 'none';
    });
};

// Abre el desplegable cerrando los demás.
const showDropdown = (event) => {
    const dropdownButton = event.target.closest('.dropdown');
    const dropdownContent = dropdownButton.querySelector('.dropdown-content');

    closeAllDropdowns();
    dropdownContent.style.display = 'block';

    event.stopPropagation();
};

// Funcion que aplica el tema claro.
inputTema = document.querySelector('input[name="tema"]');
const setClaro = () => {
    diapositivasContainer.setAttribute('tema', 'claro');
    inputTema.value = 'claro';
};

// Funcion que aplica el tema oscuro.
const setOscuro = () => {
    diapositivasContainer.setAttribute('tema', 'oscuro');
    inputTema.value = 'oscuro';
};

const previewForm = document.querySelector('#preview_form');
previewForm.addEventListener('submit', (e) => {
    e.preventDefault();
    const inputDiapositivaId = previewForm.querySelector('input[name="diapositiva_id"]');
    let diapositivaActual = document.querySelector('.d-container[style*="display: flex"] input');
    diapositivaActual = diapositivaActual.name.split('_');
    diapositivaActual = diapositivaActual[diapositivaActual.length - 1];
    inputDiapositivaId.value = diapositivaActual;
    e.target.submit();
});
