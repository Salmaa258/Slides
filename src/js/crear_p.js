const diapositivasContainer = document.getElementById('diapositivas');
const diapositivaTitulo = document.getElementById('d_titulo_template');
const diapositivaTituloTexto = document.getElementById(
    'd_titulo_texto_template'
);

// Crea y añade una nueva diapositiva de tipo "título" al contenedor principal.
const newTipoTitulo = () => {
    const diapositiva = diapositivaTitulo.content.cloneNode(true);
    const diapositivaContainer = diapositiva.querySelector('.d-container');

    diapositivaContainer.querySelector('input[type="text"]').name =
        'd_titulo_' + numDiapositivas;

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

    diapositivaContainer.querySelector('input[type="text"]').name =
        'd_titulo_' + numDiapositivas;
    diapositivaContainer.querySelector('textarea').name =
        'd_contenido_' + numDiapositivas;

    diapositivasContainer.append(diapositivaContainer);
    numDiapositivas++;
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
};

// Funcion que aplica el tema oscuro.
const setOscuro = () => {
    diapositivasContainer.setAttribute('tema', 'oscuro');
    inputTema.value = 'oscuro';
};
