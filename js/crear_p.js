// Variables globales
let temaActual = 'oscuro'; 
const diapositivas = document.getElementById('diapositivas');
const diapositivaTitulo = document.getElementById('d_titulo_template');
const diapositivaTituloTexto = document.getElementById('d_titulo_texto_template');
let numDiapositivas = diapositivas.children.length - 2;

// Aplica el tema actual (claro u oscuro) a una diapositiva específica.
const aplicarTema = (diapositiva) => {
  if (diapositiva && diapositiva.style) {  // Aseguramos que diapositiva y diapositiva.style sean válidos
    if (temaActual === 'claro') {
      diapositiva.style.backgroundColor = 'white';
      const inputs = diapositiva.querySelectorAll('input, textarea');
      inputs.forEach(input => {
        input.style.color = 'black';
        input.style.borderColor = 'black';
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
  closeAllDropdowns();

  const dropdownButton = event.target.closest('.dropdown');
  const dropdownContent = dropdownButton.querySelector('.dropdown-content');

  if (dropdownContent.style.display === 'none' || !dropdownContent.style.display) {
    dropdownContent.style.display = 'block';
  } else {
    dropdownContent.style.display = 'none';
  }

  event.stopPropagation();
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

  const allInputs = document.querySelectorAll('input, textarea');
  allInputs.forEach(input => {
    input.style.color = 'black';
    input.style.borderColor = 'black';
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

  const allInputs = document.querySelectorAll('input, textarea');
  allInputs.forEach(input => {
    input.style.color = '';
    input.style.borderColor = '';
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

// Event listeners
document.addEventListener('DOMContentLoaded', init);
document.addEventListener('click', (event) => {
  const isClickInsideDropdown = !!event.target.closest('.dropdown');
  if (!isClickInsideDropdown) {
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