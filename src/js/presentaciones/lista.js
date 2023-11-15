let ordenLista = [];

// Función para ocultar todas las diapositivas
const ocultarTodasLasDiapositivas = () => {
  console.log('Ocultando todas las diapositivas');
  const todasLasDiapositivas = document.querySelectorAll('.d-container, .d-containerImagen');
  todasLasDiapositivas.forEach(diapositiva => {
    diapositiva.style.display = 'none';
  });
};

// Función para mostrar una diapositiva específica por ID
const mostrarDiapositiva = (id) => {
  console.log(`Mostrando diapositiva con ID: ${id}`);
  const todasLasDiapositivas = document.querySelectorAll('.d-container, .d-containerImagen');
  todasLasDiapositivas.forEach(diapositiva => {
    if (diapositiva.getAttribute('data-id') === id.toString()) {
      diapositiva.style.display = 'block';
      console.log(`Diapositiva ${id} ahora está visible`);
    }
  });
};

// Añadir la funcionalidad de arrastrar a los elementos de la lista
const hacerElementosArrastrables = () => {
  const listaDiapositivas = document.querySelector('.white-list-items');
  let elementoArrastrado = null;

  listaDiapositivas.querySelectorAll('li').forEach(li => {
    li.draggable = true;
    li.addEventListener('dragstart', (e) => {
      elementoArrastrado = li;
      e.dataTransfer.effectAllowed = 'move';
      e.dataTransfer.setData('text/plain', ''); // Necesario para algunos navegadores
    });

    li.addEventListener('dragover', (e) => {
      e.preventDefault();
      e.dataTransfer.dropEffect = 'move';
    });

    li.addEventListener('drop', (e) => {
      e.preventDefault();
      if (e.target.tagName === 'LI' && elementoArrastrado !== e.target) {
        const parent = listaDiapositivas;
        const targetIndex = [...parent.children].indexOf(e.target);
        const draggedIndex = [...parent.children].indexOf(elementoArrastrado);
        if (draggedIndex < targetIndex) {
          parent.insertBefore(elementoArrastrado, e.target.nextSibling);
        } else {
          parent.insertBefore(elementoArrastrado, e.target);
        }
      }
      ordenLista = Array.from(listaDiapositivas.children).map(li => li.getAttribute('data-id'));
      console.log(`Elemento soltado. Orden actual de la lista: ${ordenLista}`);
      
      // Después de agregar todos los elementos li a la lista, verifica si hay más de 11 elementos
      if (ordenLista.length > 11) {
        const listaContainer = document.querySelector('.white-list-items-container');
        listaContainer.style.maxHeight = '400px';
        listaContainer.style.overflowY = 'auto';
      }
    });

    li.addEventListener('dragend', () => {
      elementoArrastrado = null;
      console.log(`Arrastre finalizado. Orden actual de la lista: ${ordenLista}`);
    });

    // Resto del código para manejar el click
    li.addEventListener('click', () => {
      const id = li.getAttribute('data-id');
      console.log(`Elemento de lista con ID ${id} clickeado`);
      ocultarTodasLasDiapositivas();
      mostrarDiapositiva(id);
      actualizarListaDiapositivas();
    });
  });
};


// Función para actualizar la lista de diapositivas usando el ID
const actualizarListaDiapositivas = () => {
  console.log('Actualizando lista de diapositivas');
  const listaDiapositivas = document.querySelector('.white-list-items');
  listaDiapositivas.innerHTML = '';

  ordenLista.forEach(id => {
    // Trata de seleccionar el input de texto tanto en .d-container como en .d-containerImagen
    const input = document.querySelector(`.d-container[data-id="${id}"] input[type="text"], .d-containerImagen[data-id="${id}"] input[type="text"]`);
    if (input) {
      const nuevoLi = document.createElement('li');
      nuevoLi.textContent = input.value.trim() || `Titulo`;
      nuevoLi.setAttribute('data-id', id);
      listaDiapositivas.appendChild(nuevoLi);
      console.log(`Añadido a la lista: ${nuevoLi.textContent}`);
    }
  });

  hacerElementosArrastrables();
  console.log(`Orden de la lista después de la actualización: ${ordenLista}`);
};

// Inicializar el orden de la lista basado en el DOM inicial
const inicializarOrdenLista = () => {
  const todasLasDiapositivas = document.querySelectorAll('.d-container, .d-containerImagen');
  ordenLista = Array.from(todasLasDiapositivas).map(diapositiva => 
    diapositiva.getAttribute('data-id')
  );
  console.log(`Orden inicial de la lista: ${ordenLista}`);
};

// Actualizar el valor del campo oculto antes de enviar el formulario
document.getElementById('data_p').addEventListener('submit', function(event) {
  document.getElementById('ordenDiapositivas').value = ordenLista.join(',');
  console.log(`Orden de diapositivas enviado: ${ordenLista.join(',')}`);
});

// Inicializar la lista de diapositivas cuando el documento esté listo
document.addEventListener('DOMContentLoaded', () => {
  inicializarOrdenLista();
  actualizarListaDiapositivas();
});
