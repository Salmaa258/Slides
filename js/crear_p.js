const diapositivas = document.getElementById('diapositivas');
const diapositivaTitulo = document.getElementById('d_titulo_template');
const diapositivaTituloTexto = document.getElementById('d_titulo_texto_template');
let numDiapositivas = diapositivas.children.length - 2;

const newDiapositivaTitulo = () => {
  const newDiapositiva = diapositivaTitulo.content.cloneNode(true);
  newDiapositiva.querySelector('input[type="text"]').name = 'd_titulo_' + numDiapositivas;
  diapositivas.append(newDiapositiva);
  numDiapositivas++;
};

const newDiapositivaTituloTexto = () => {
  const newDiapositiva = diapositivaTituloTexto.content.cloneNode(true);
  newDiapositiva.querySelector('input[type="text"]').name = 'd_titulo_' + numDiapositivas;
  newDiapositiva.querySelector('textarea').name = 'd_contenido_' + numDiapositivas; // Esta es la línea que debes agregar
  diapositivas.append(newDiapositiva);
  numDiapositivas++;
};
