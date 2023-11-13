function confirmDelete(event, container) {
    event.preventDefault();
    if (container) {
        const diapositivaId = container.getAttribute('data-id');
        if (diapositivaId) {
            sessionStorage.setItem('diapositivaAEliminar', diapositivaId);
            document.getElementById('confirmarEliminar').showModal();
        }
    }
}


// Esta función se llama cuando se confirma la eliminación de una diapositiva
function eliminarDiapositiva() {
    const diapositivaId = sessionStorage.getItem('diapositivaAEliminar');
    if (diapositivaId) {
        const container = document.querySelector(`.d-container[data-id="${diapositivaId}"], .d-containerImagen[data-id="${diapositivaId}"]`);
        if (container) {
            // Verificar que haya al menos una diapositiva antes de intentar eliminarla
            if (ordenLista.length > 1) {
                // Elimina el elemento DOM de la diapositiva
                container.remove();

                // Obtener el índice de la diapositiva en el orden actual
                const index = ordenLista.indexOf(diapositivaId);

                // Actualizar el orden de la lista de diapositivas después de eliminar
                ordenLista.splice(index, 1); // Eliminar el ID de la diapositiva de la lista de orden

                // Mostrar la diapositiva siguiente si se eliminó la primera diapositiva
                // Mostrar la diapositiva anterior si se eliminó cualquier otra diapositiva
                if (index === 0) {
                    mostrarDiapositiva(ordenLista[0]);
                } else if (index > 0) {
                    mostrarDiapositiva(ordenLista[index - 1]);
                }

                // Cierra la modal de confirmación y muestra la modal de éxito después de la actualización
                cerrarConfirmacion();
                document.getElementById('exito_eliminar').showModal();

                actualizarListaDiapositivas(); // Actualizar la lista en la interfaz
            } else {
                mostrarError(); // Mostrar la modal de error cuando se intenta eliminar la última diapositiva
            }
        }
    }
}


function cerrarConfirmacion() {
    document.getElementById('confirmarEliminar').close();
    sessionStorage.removeItem('diapositivaAEliminar');
}

function cerrarExito() {
    document.getElementById('exito_eliminar').close();
}

function mostrarError() {
    const errorModal = document.getElementById('error_eliminar');
    if (errorModal) {
        errorModal.showModal();
    }
}

function cerrarError() {
    const errorModal = document.getElementById('error_eliminar');
    if (errorModal) {
        errorModal.close();
    }
}

