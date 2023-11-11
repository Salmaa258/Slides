const diapositivasContainer = document.getElementById('diapositivas');
const diapositivaTitulo = document.getElementById('d_titulo_template');
const diapositivaTituloTexto = document.getElementById('d_titulo_texto_template');
const diapositivaTituloTextoImagen = document.getElementById('d_titulo_texto_imagen_template');

// Este contador incrementará con cada nueva diapositiva
let newDiapositivaId = 1; 

// Función para generar un ID único para nuevas diapositivas
const generateNewDiapositivaId = () => `new-${newDiapositivaId++}`;

// Crea y añade una nueva diapositiva de tipo "título" al contenedor principal.
const newTipoTitulo = () => {
    const diapositiva = diapositivaTitulo.content.cloneNode(true);
    const diapositivaContainer = diapositiva.querySelector('.d-container');
    const tempId = generateNewDiapositivaId();
    diapositivaContainer.setAttribute('data-id', tempId);
    diapositivaContainer.querySelector('input[type="text"]').name = 'd_titulo_' + tempId;

    diapositivasContainer.append(diapositivaContainer);

    ocultarTodasLasDiapositivas();
    mostrarDiapositiva(tempId);
    ordenLista.push(tempId);
    actualizarListaDiapositivas();
    
    mostrarDiapositiva(tempId);
};

let numDiapositivas = document.getElementById('diapositivas');
numDiapositivas = numDiapositivas.getAttribute('lastDiapositivaId');
// Comprobamos si estamos creando una diapositiva nueva o editando una existente.
if (numDiapositivas === '0') {
    newTipoTitulo();
    numDiapositivas = 1;
} else {
    numDiapositivas = parseInt(numDiapositivas) - 1;
}


// Crea y añade una nueva diapositiva que tiene un título y un área de texto.
const newTipoContenido = () => {
    const diapositiva = diapositivaTituloTexto.content.cloneNode(true);
    const diapositivaContainer = diapositiva.querySelector('.d-container');
    const tempId = generateNewDiapositivaId();
    diapositivaContainer.setAttribute('data-id', tempId);
    diapositivaContainer.querySelector('input[type="text"]').name = 'd_titulo_' + tempId;
    diapositivaContainer.querySelector('textarea').name = 'd_contenido_' + tempId;

    diapositivasContainer.append(diapositivaContainer);

    ocultarTodasLasDiapositivas();
    mostrarDiapositiva(tempId);
    ordenLista.push(tempId);
    actualizarListaDiapositivas();

    mostrarDiapositiva(tempId);
};

// Crea y añade una nueva diapositiva que tiene un título, un área de texto y una imagen.
const newTipoImagen = () => {
    const diapositiva = diapositivaTituloTextoImagen.content.cloneNode(true);
    const diapositivaContainer = diapositiva.querySelector('.d-containerImagen');
    const tempId = generateNewDiapositivaId(); // Asume que existe una función para generar IDs únicos
    diapositivaContainer.setAttribute('data-id', tempId);

    diapositivaContainer.querySelector('input[type="text"]').name = 'd_titulo_' + tempId;
    diapositivaContainer.querySelector('textarea').name = 'd_contenido_' + tempId;
    diapositivaContainer.querySelector('input[type="file"]').name = 'd_imagen_' + tempId;

    diapositivasContainer.append(diapositivaContainer);

    ocultarTodasLasDiapositivas(); 
    mostrarDiapositiva(tempId); 
    ordenLista.push(tempId); 
    actualizarListaDiapositivas(); 

    mostrarDiapositiva(tempId);
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
    if (tipoDiapositiva === "titulo") {
      newTipoTitulo();
    } else if (tipoDiapositiva === "tituloTexto") {
      newTipoContenido();
    } else if (tipoDiapositiva === "tituloTextoImagen") {
      newTipoImagen();
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

    if (tipoDiapositiva) {
      // Si hay un tipo de diapositiva recordado, inserta en la base de datos pero sin guardar los cambios
      if (tipoDiapositiva === "titulo") {
        newTipoTitulo();
      } else if (tipoDiapositiva === "tituloTexto") {
        newTipoContenido();
      } else if (tipoDiapositiva === "tituloTextoImagen") {
        newTipoImagen();
      }
    }


    // Reseteamos el tipo de diapositiva
    tipoDiapositiva = "";
  });

  return false; // Evita que el evento del enlace se propague
};

//Llamada a la función que muestra el feedback pasando el tipo de diapositiva "Titulo"
const mostrarConfirmacionNuevaDiapositivaTitulo = (event) => {
    mostrarConfirmacionNuevaDiapositiva(event, 'titulo');
};

//Llamada a la función que muestra el feedback pasando el tipo de diapositiva "Titulo + Texto"
const mostrarConfirmacionNuevaDiapositivaTituloTexto = (event) => {
    mostrarConfirmacionNuevaDiapositiva(event, 'tituloTexto');
};

//Llamada a la función que muestra el feedback pasando el tipo de diapositiva "Titulo + Texto + Imagen"
const mostrarConfirmacionNuevaDiapositivaTituloTextoImagen = (event) => {
  mostrarConfirmacionNuevaDiapositiva(event, "tituloTextoImagen");
};



// Evento para cerrar desplegables al hacer click fuera del mismo.
document.addEventListener('click', (event) => {
    const isClickInsideDropdown = !!event.target.closest('.dropdown');
    const isClickOnDropdownContent =
        !!event.target.closest('.dropdown-content');

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

    const whiteListItems = document.querySelector('.white-list-items');
    if (whiteListItems) {
      whiteListItems.style.backgroundColor = 'white';
      whiteListItems.style.color = 'black';
      const listItems = whiteListItems.querySelectorAll('li');
      listItems.forEach(item => {
        item.style.color = 'black';
      });
    }
};

// Funcion que aplica el tema oscuro.
const setOscuro = () => {
    diapositivasContainer.setAttribute('tema', 'oscuro');
    inputTema.value = 'oscuro';

    const whiteListItems = document.querySelector('.white-list-items');
    if (whiteListItems) {
      whiteListItems.style.backgroundColor = '';
      whiteListItems.style.color = '';
      const listItems = whiteListItems.querySelectorAll('li');
      listItems.forEach(item => {
        item.style.color = '';
      });
    }
};

const generaUrl = () => {
    return Math.floor(1000000000 + Math.random() * 9999999999);
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

const generalForm = document.querySelector('#data_p');
const publicarButton = document.querySelector('#publicar_button button[form="data_p"]');
publicarButton.addEventListener('click', (e) => {
    e.preventDefault();
    const inputUrl = publicarButton.parentElement.querySelector('input');
    if (inputUrl.value === 'null') {
        const url = generaUrl();
        inputUrl.value = url;
    } else {
        inputUrl.value = 'null';
    }
    generalForm.submit();
});

const copyButton = document.querySelector('#copyUrlButton');

copyButton.addEventListener('click', (e) => {
    let url = document.querySelector('input[name="url"]').value;
    url = 'http://localhost:4200/src/views/preview.php?url=' + url;
    navigator.clipboard.writeText(url);
    e.target.src = '../assets/icons/checkCopy.svg';
    setTimeout(() => {
        e.target.src = '../assets/icons/copy.svg';
    }, 1000);
});