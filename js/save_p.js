// Constantes
const SUCCESS_MESSAGE = 'Datos guardados exitosamente';
const ERROR_MESSAGE = 'Hubo un error al guardar los datos: ';

// Función para obtener datos del formulario
const getFormData = () => {
    return new FormData(document.querySelector('#data_p'));
}

// Función para enviar datos al servidor de manera síncrona
const sendDataToServer = (url, data) => {
    let xhr = new XMLHttpRequest();
    xhr.open('POST', url, false);
    xhr.send(data);
    return xhr;
}

// Función para manejar la respuesta del servidor
const handleServerResponse = (xhr) => {
    console.log(xhr.responseText)
    if (xhr.status === 200) {
        let response = JSON.parse(xhr.responseText);
        if (response.success) {
            alert(SUCCESS_MESSAGE);
        } else {
            alert(ERROR_MESSAGE + response.error);
        }
    } else {
        console.error('Error en la solicitud:', xhr.status);
    }
}

// Función para enviar datos al servidor y guardar en la base de datos
const onSaveData = () => {
    const formData = getFormData();
    const xhrResponse = sendDataToServer('../php/db/save/save_p.php', formData);
    handleServerResponse(xhrResponse);
}
