document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector("#data_p");
    
    form.addEventListener("submit", function(event) {
        //event.preventDefault();
        let p_titulo = document.querySelector("input[name='p_titulo']");
        let p_descripcion = document.querySelector("input[name='p_descripcion']");
        
        // Obtenemos los tipos de todas las diapositivas
        let d_tipos = document.querySelectorAll("input[name='d_tipo[]']");

        // Validamos que el título esté completo y sean caracteres:
        if (p_titulo.value.trim() === '' || !/^[a-zA-Z\s]+$/.test(p_titulo.value.trim())) {
            console.log('Datos no válidos');
            alert('Por favor, introduce un título válido.');
            return;  // Salimos de la función si hay un error
        } 

        // Logueamos la información de las diapositivas
        d_tipos.forEach((tipo, index) => {
            console.log(`Diapositiva ${index + 1}: Tipo=${tipo.value}`);
        });

        console.log(`Se han introducido los siguientes datos: título=${p_titulo.value}, descripción=${p_descripcion.value}`);
        console.log(`Se envían los datos...`);
        
        // Enviamos el formulario
        this.submit();
    });
});