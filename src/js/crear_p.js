const diapositivas = document.getElementById('diapositivas');
const diapositivaTitulo = document.getElementById('d_titulo_template');
const diapositivaTituloTexto = document.getElementById(
    'd_titulo_texto_template'
);

let numDiapositivas = document.getElementById('diapositivas');
numDiapositivas = numDiapositivas.getAttribute('lastDiapositivaId');

// Cierra todos los desplegables.
const closeAllDropdowns = () => {
    const allDropdownContents = document.querySelectorAll('.dropdown-content');
    allDropdownContents.forEach((content) => {
        content.style.display = 'none';
    });
};

// Cierra todos los desplegables y abre el desplegable específico.
const showDropdown = (event) => {
    const dropdownButton = event.target.closest('.dropdown');
    const dropdownContent = dropdownButton.querySelector('.dropdown-content');

    if (
        dropdownContent.style.display === 'none' ||
        !dropdownContent.style.display
    ) {
        closeAllDropdowns(); // Asegúrate de que todos los otros desplegables estén cerrados
        dropdownContent.style.display = 'block';
    } else {
        dropdownContent.style.display = 'none';
    }

    event.stopPropagation(); // Esto es crucial para prevenir que otros oyentes de eventos cierren el desplegable inmediatamente
};

// Crea y añade una nueva diapositiva de tipo "título" al contenedor principal.
const newDiapositivaTitulo = () => {
    const diapositiva = diapositivaTitulo.content.cloneNode(true);
    const diapositivaContainer = diapositiva.querySelector('.d-container');

    diapositivaContainer.querySelector('input[type="text"]').name =
        'd_titulo_' + numDiapositivas;

    diapositivas.append(diapositivaContainer);
    numDiapositivas++;
};

// Comprobamos si estamos creando una diapositiva nueva o editando una existente.
if (numDiapositivas === '0') {
    newDiapositivaTitulo();
    numDiapositivas = 1;
} else {
    numDiapositivas = parseInt(numDiapositivas) + 1;
}

// Crea y añade una nueva diapositiva que tiene un título y un área de texto.
const newDiapositivaTituloTexto = () => {
    const diapositiva = diapositivaTituloTexto.content.cloneNode(true);
    const diapositivaContainer = diapositiva.querySelector('.d-container');

    diapositivaContainer.querySelector('input[type="text"]').name =
        'd_titulo_' + numDiapositivas;
    diapositivaContainer.querySelector('textarea').name =
        'd_contenido_' + numDiapositivas;

    diapositivas.append(diapositivaContainer);
    numDiapositivas++;
};

document.addEventListener('click', (event) => {
    const isClickInsideDropdown = !!event.target.closest('.dropdown');
    const isClickOnDropdownContent =
        !!event.target.closest('.dropdown-content');

    if (!isClickInsideDropdown || isClickOnDropdownContent) {
        closeAllDropdowns();
    }
});

document.addEventListener('click', (e) => {
    if (e.target.id !== 'nueva-diapositiva') {
        try {
            nuevaDiapositivaButtonOptions.style.display = 'none';
        } catch {}
    }
});
