// Variables globales
let temaActual = 'oscuro'; 
const diapositivas = document.getElementById('diapositivas');
const diapositivaTitulo = document.getElementById('d_titulo_template');
const diapositivaTituloTexto = document.getElementById('d_titulo_texto_template');
let numDiapositivas = diapositivas.children.length - 2;

// Aplica el tema actual (claro u oscuro) a una diapositiva específica.
const aplicarTema = (diapositiva) => {
  if (diapositiva && diapositiva.style) {
    if (temaActual === 'claro') {
      diapositiva.style.backgroundColor = 'white';
      const inputs = diapositiva.querySelectorAll('input, textarea');
      inputs.forEach(input => {
        input.style.color = 'black';
        input.style.borderColor = 'black';
        input.addEventListener('focus', () => {
          if (input.tagName.toLowerCase() === 'input') {
            input.style.backgroundColor = 'transparent';
          } else if (input.tagName.toLowerCase() === 'textarea') {
            input.style.backgroundColor = '#f0f0f0';
          }
        });
        input.addEventListener('blur', () => {
          input.style.backgroundColor = '';
        });
      });
    } else if (temaActual === 'oscuro') {
      diapositiva.style.backgroundColor = '';
      const inputs = diapositiva.querySelectorAll('input, textarea');
      inputs.forEach(input => {
        input.style.color = '';
        input.style.borderColor = '';
      });
    }
  } else {
    console.error('Diapositiva o su estilo no está definido.');
  }
};

// Cierra todos los desplegables (dropdowns) en la página.
const closeAllDropdowns = () => {
  const allDropdownContents = document.querySelectorAll('.dropdown-content');
  allDropdownContents.forEach(content => {
    content.style.display = 'none';
  });
};

// Cierra todos los desplegables y abre el desplegable específico en el que se hizo clic.
// Detiene la propagación del evento.
const showDropdown = (event) => {
  const dropdownButton = event.target.closest('.dropdown');
  const dropdownContent = dropdownButton.querySelector('.dropdown-content');

  if (dropdownContent.style.display === 'none' || !dropdownContent.style.display) {
      closeAllDropdowns();  // Asegúrate de que todos los otros desplegables estén cerrados
      dropdownContent.style.display = 'block';
  } else {
      dropdownContent.style.display = 'none';
  }

  event.stopPropagation();  // Esto es crucial para prevenir que otros oyentes de eventos cierren el desplegable inmediatamente
};


// Crea y añade una nueva diapositiva de tipo "título" al contenedor principal.
// Aplica el tema actual a la nueva diapositiva.
const newDiapositivaTitulo = () => {
  const newDiapositivaFragment = diapositivaTitulo.content.cloneNode(true);
  const newDiapositivaNode = newDiapositivaFragment.querySelector('.d-container');

  newDiapositivaNode.querySelector('input[type="text"]').name = 'd_titulo_' + numDiapositivas;

  diapositivas.append(newDiapositivaNode);
  numDiapositivas++;

  aplicarTema(newDiapositivaNode);
};

// Crea y añade una nueva diapositiva que tiene un título y un área de texto.
// Aplica el tema actual a la nueva diapositiva.
const newDiapositivaTituloTexto = () => {
  const newDiapositivaFragment = diapositivaTituloTexto.content.cloneNode(true);
  const newDiapositivaNode = newDiapositivaFragment.querySelector('.d-container');

  newDiapositivaNode.querySelector('input[type="text"]').name = 'd_titulo_' + numDiapositivas;
  newDiapositivaNode.querySelector('textarea').name = 'd_contenido_' + numDiapositivas;

  diapositivas.append(newDiapositivaNode);
  numDiapositivas++;

  aplicarTema(newDiapositivaNode);
};

// Establece el tema actual a "claro".
// Cambia el fondo de todas las diapositivas a blanco.
// Cambia el color y el borde de todos los inputs y textareas a negro.
const setTemaClaro = () => {
  temaActual = 'claro';

  const allDiapositivas = document.querySelectorAll('.d-container');
  allDiapositivas.forEach(diapositiva => {
    diapositiva.style.backgroundColor = 'white';
  });

  const allInputs = document.querySelectorAll('.d-container input, .d-container textarea');
  allInputs.forEach(input => {
    input.style.color = 'black';
    input.style.borderColor = 'black';

    input.addEventListener('focus', () => {
      if (input.tagName.toLowerCase() === 'input') {
        input.style.backgroundColor = 'transparent';
      } else if (input.tagName.toLowerCase() === 'textarea') {
        input.style.backgroundColor = '#f0f0f0';  // Este es un gris claro, pero puedes ajustarlo según tus necesidades
      }
    });

    input.addEventListener('blur', () => {
      input.style.backgroundColor = '';
    });
  });
};



// Establece el tema actual a "oscuro".
// Restablece el color de fondo de todas las diapositivas al valor por defecto.
// Restablece el color y el borde de todos los inputs y textareas a sus valores por defecto.
const setTemaOscuro = () => {
  temaActual = 'oscuro';

  const allDiapositivas = document.querySelectorAll('.d-container');
  allDiapositivas.forEach(diapositiva => {
    diapositiva.style.backgroundColor = '';
  });

  const allInputs = document.querySelectorAll('.d-container input, .d-container textarea');
  allInputs.forEach(input => {
    input.style.color = '';
    input.style.borderColor = '';
    // Eliminar los event listeners de foco y desenfoque para que no interfieran con el tema oscuro
    const clonedInput = input.cloneNode(true);
    input.replaceWith(clonedInput);
  });
};

// Aplica el tema inicial (claro u oscuro) a todas las diapositivas cuando se carga la página.
const init = () => {
  if (temaActual === 'claro') {
    setTemaClaro();
  } else if (temaActual === 'oscuro') {
    setTemaOscuro();
  }
};

// Función para establecer el borde de los inputs específicos como transparente
const setInputBordersTransparent = () => {
  const inputsTitulo = document.querySelectorAll('input[placeholder="Añade un título..."]');
  const inputsDescripcion = document.querySelectorAll('input[placeholder="Escribe una descripción..."]');

  // Función para establecer estilos en un input
  const setStyles = (input) => {
    input.style.borderColor = 'transparent';

    input.addEventListener('focus', () => {
      input.style.outline = 'none';
      input.style.borderColor = 'transparent';
    });
  };

  // Aplicar estilos a los inputs de título
  inputsTitulo.forEach(setStyles);
  // Aplicar estilos a los inputs de descripción
  inputsDescripcion.forEach(setStyles);
};

// Event listeners
document.addEventListener('DOMContentLoaded', () => {
  init();
  setInputBordersTransparent();
});

document.addEventListener('click', (event) => {
  const isClickInsideDropdown = !!event.target.closest('.dropdown');
  const isClickOnDropdownContent = !!event.target.closest('.dropdown-content');

  if (!isClickInsideDropdown || isClickOnDropdownContent) {
      closeAllDropdowns();
  }
});

document.addEventListener('click', (e) => {
  if (e.target.id !== 'nueva-diapositiva') {
    try {
        nuevaDiapositivaButtonOptions.style.display = 'none';
    }catch{
      
    }
   
  }
});