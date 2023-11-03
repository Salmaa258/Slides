const nextDiapo = document.querySelector('#diapositivaAnterior');
const primeraDiapositiva = document.querySelector('.d-container');
let diapositivaActual;

document.addEventListener('DOMContentLoaded', () => {
    diapositivaActual = primeraDiapositiva;
});

primeraDiapositiva.style.display = 'flex';

const siguiente = () => {
    if (
        diapositivaActual.nextElementSibling.className.includes('d-container')
    ) {
        try {
            diapositivaActual.style.display = 'none';
            diapositivaActual.nextElementSibling.style.display = 'flex';
            diapositivaActual = diapositivaActual.nextElementSibling;
        } catch (error) {}
    }
};

const anterior = () => {
    if (
        diapositivaActual.previousElementSibling.className.includes('d-container')
    ) {
        try {
            diapositivaActual.style.display = 'none';
            diapositivaActual.previousElementSibling.style.display = 'flex';
            diapositivaActual = diapositivaActual.previousElementSibling;
        } catch (error) {}
    }
};
