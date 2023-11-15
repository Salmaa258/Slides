let ordenLista = []; // Esta lista mantendrá el orden actualizado de los elementos

// Función para ocultar todas las diapositivas
const ocultarTodasLasDiapositivas = () => {
  console.log('Ocultando todas las diapositivas...');
  const todasLasDiapositivas = document.querySelectorAll('.d-container, .d-containerImagen');
  todasLasDiapositivas.forEach(diapositiva => {
    diapositiva.style.display = 'none';
  });
  console.log('Todas las diapositivas han sido ocultadas.');
};

// Función para mostrar una diapositiva específica por ID
const mostrarDiapositiva = (id) => {
  console.log(`Mostrando la diapositiva con ID: ${id}`);
  // Selecciona ambas clases con una coma separando los selectores
  const diapositivaParaMostrar = document.querySelector(`.d-container[data-id="${id}"], .d-containerImagen[data-id="${id}"]`);
  if (diapositivaParaMostrar) {
    diapositivaParaMostrar.style.display = 'block'; // Asegúrate de que esta sea tu clase para mostrar elementos
    console.log('Diapositiva mostrada.');
  } else {
    console.log('No se encontró la diapositiva con el ID proporcionado.');
  }
};

// Función para hacer los elementos de la lista arrastrables
const hacerElementosArrastrables = () => {
  const listaDiapositivas = document.querySelector('.white-list-items');
  let elementoArrastrado = null;

  listaDiapositivas.addEventListener('dragover', (e) => {
    e.preventDefault();
    e.dataTransfer.dropEffect = 'move';
  });

  listaDiapositivas.addEventListener('drop', (e) => {
    e.preventDefault();
    if (elementoArrastrado && e.target.tagName === 'LI') {
      const targetIndex = [...listaDiapositivas.children].indexOf(e.target);
      const draggedIndex = [...listaDiapositivas.children].indexOf(elementoArrastrado);
      if (draggedIndex < targetIndex) {
        listaDiapositivas.insertBefore(elementoArrastrado, e.target.nextSibling);
      } else {
        listaDiapositivas.insertBefore(elementoArrastrado, e.target);
      }
      ordenLista = [...listaDiapositivas.children].map(li => li.getAttribute('data-id'));
      console.log('Elementos reordenados:', ordenLista);
    }
  });

  listaDiapositivas.querySelectorAll('li').forEach(li => {
    li.setAttribute('draggable', true);

    li.addEventListener('dragstart', (e) => {
      elementoArrastrado = li;
      e.dataTransfer.setData('text/plain', li.getAttribute('data-id'));
    });

    li.addEventListener('dragend', () => {
      elementoArrastrado = null;
    });
  });
};

// Función para actualizar la lista de diapositivas
const actualizarListaDiapositivas = () => {
  console.log('Actualizando lista de diapositivas...');
  const listaDiapositivas = document.querySelector('.white-list-items');
  listaDiapositivas.innerHTML = '';

  // Actualiza la selección para incluir ambas clases
  const todasLasDiapositivas = document.querySelectorAll('.d-container input[type="text"], .d-containerImagen input[type="text"]');
  todasLasDiapositivas.forEach(input => {
    const id = input.closest('.d-container, .d-containerImagen').getAttribute('data-id');
    const nuevoLi = document.createElement('li');
    nuevoLi.textContent = input.value.trim() || `Título`;
    nuevoLi.setAttribute('data-id', id);
    listaDiapositivas.appendChild(nuevoLi);
  });

  console.log('Lista de diapositivas actualizada.');
  hacerElementosArrastrables(); // Hace que los nuevos elementos li sean arrastrables

  // Asigna eventos de clic a los elementos de la lista
  listaDiapositivas.querySelectorAll('li').forEach(li => {
    li.addEventListener('click', () => {
      const id = li.getAttribute('data-id');
      console.log(`Elemento de lista con ID ${id} clickeado.`);
      ocultarTodasLasDiapositivas();
      mostrarDiapositiva(id);
      actualizarListaDiapositivas();
    });
  });
};

// Función para inicializar la vista de diapositivas
const inicializarDiapositivas = () => {
  console.log('Inicializando diapositivas...');
  ocultarTodasLasDiapositivas();
  actualizarListaDiapositivas();

  // Muestra la primera diapositiva por defecto
  const firstSlideId = document.querySelector('.d-container').getAttribute('data-id');
  mostrarDiapositiva(firstSlideId);
  console.log(`Diapositiva inicial con ID ${firstSlideId} mostrada.`);
};

// Evento para inicializar la vista cuando el DOM esté cargado
document.addEventListener('DOMContentLoaded', () => {
  console.log('DOM completamente cargado. Inicializando las diapositivas.');
  // Aquí recuperas el valor del tema desde el atributo 'tema' del contenedor 'diapositivas'
  const temaActual = diapositivasContainer.getAttribute('tema');
  if (temaActual === 'claro') {
    setClaro(); // Llama a la función que ajusta los estilos para el tema claro
  }
  
  inicializarDiapositivas();
  // Actualiza el orden inicial de la lista
  ordenLista = [...document.querySelectorAll('.white-list-items li')].map(li => li.getAttribute('data-id'));
  console.log('Orden inicial de la lista:', ordenLista);

  document.getElementById('data_p').addEventListener('submit', function() {
    document.getElementById('ordenDiapositivas').value = ordenLista.join(',');
});
});

