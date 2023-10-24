const nuevaDiapositivaButton = document.querySelector('.dropdown');
const nuevaDiapositivaButtonOptions = document.querySelector('.dropdown-content');

/**
 * Funcion que muestra el desplegable de añadir presentación.
 */
const showDropdown = () => {
  if (nuevaDiapositivaButtonOptions.style.display === 'none') {
    nuevaDiapositivaButtonOptions.style.display = 'block';
  } else {
    nuevaDiapositivaButtonOptions.style.display = 'none';
  }
};

document.addEventListener('click', (e) => {
  if (e.target.id !== 'nueva-diapositiva') {
    nuevaDiapositivaButtonOptions.style.display = 'none';
  }
})

const diapositivas = document.getElementById('diapositivas');
const diapositivaTitulo = document.getElementById('d_titulo_template');
const diapositivaTituloTexto = document.getElementById('d_titulo_texto_template');
let numDiapositivas = diapositivas.children.length - 2;

/**
 * Función que añade una nueva diapositiva de tipo título.
 */
const newDiapositivaTitulo = () => {
  const newDiapositiva = diapositivaTitulo.content.cloneNode(true);
  newDiapositiva.querySelector('input[type="text"]').name = 'd_titulo_' + numDiapositivas;
  diapositivas.append(newDiapositiva);
  numDiapositivas++;
};

/**
 * Función que añade una nueva diapositiva de tipo título.
 */
const newDiapositivaTituloTexto = () => {
  const newDiapositiva = diapositivaTituloTexto.content.cloneNode(true);

  newDiapositiva.querySelector('input[type="text"]').name =
    'd_titulo_' + numDiapositivas;
  newDiapositiva.querySelector('textarea').name =
    'd_contenido_' + numDiapositivas;

  diapositivas.append(newDiapositiva);
  numDiapositivas++;
};