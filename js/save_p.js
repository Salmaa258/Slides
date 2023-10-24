document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("#data_p");
    
    form.addEventListener("submit", function(event) {
        //event.preventDefault();
        let p_titulo = document.querySelector("input[name='p_titulo']");
        let p_descripcion = document.querySelector("input[name='p_descripcion']");
        
        // Obtenemos los tipos de todas las diapositivas
        let d_tipos = document.querySelectorAll("input[name='d_tipo[]']");

        // Validamos que el título esté completo y sean caracteres:
        if (p_titulo.value.trim() === '') {
            alert('Por favor, introduce un título válido.');
            return;  // Salimos de la función si hay un error
        } 

        // Enviamos el formulario
        this.submit();
    });
});
