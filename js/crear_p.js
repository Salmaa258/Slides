// Obtenemos los elementos DOM para las diapositivas y las plantillas
const diapositivas = document.getElementById('diapositivas');
const diapositivaTitulo = document.getElementById('d_titulo_template');
const diapositivaTituloTexto = document.getElementById('d_titulo_texto_template');

// Calculamos el número de diapositivas existentes (menos 2 para ajustar el conteo)
let numDiapositivas = diapositivas.children.length - 2;

// Función para añadir una nueva diapositiva con solo título
const newDiapositivaTitulo = () => {
  // Clonamos la plantilla de diapositiva de título
  const newDiapositiva = diapositivaTitulo.content.cloneNode(true);
  
  // Configuramos el nombre del input basado en el número actual de diapositivas
  newDiapositiva.querySelector('input[type="text"]').name = 'd_titulo_' + numDiapositivas;
  
  // Añadimos la nueva diapositiva al contenedor principal
  diapositivas.append(newDiapositiva);
  
  // Incrementamos el contador de diapositivas
  numDiapositivas++;
};

// Función para añadir una nueva diapositiva con título y texto
const newDiapositivaTituloTexto = () => {
  // Clonamos la plantilla de diapositiva de título y texto
  const newDiapositiva = diapositivaTituloTexto.content.cloneNode(true);
  
  // Configuramos el nombre del input y textarea basado en el número actual de diapositivas
  newDiapositiva.querySelector('input[type="text"]').name = 'd_titulo_' + numDiapositivas;
  newDiapositiva.querySelector('textarea').name = 'd_contenido_' + numDiapositivas;
  
  // Añadimos la nueva diapositiva al contenedor principal
  diapositivas.append(newDiapositiva);
  
  // Incrementamos el contador de diapositivas
  numDiapositivas++;
};
