const nextDiapo = document.querySelector('#diapositivaAnterior');
const primeraDiapositiva = document.querySelector('.d-container');
let diapositivaActual;

const siguiente = () => {
    if (diapositivaActual.nextElementSibling.className.includes('d-container')) {
        try {
            diapositivaActual.style.display = 'none';
            diapositivaActual.nextElementSibling.style.display = 'flex';
            diapositivaActual = diapositivaActual.nextElementSibling;
            document.querySelector('#diapositivaAnterior').style.display = 'flex';
        } catch (error) {}
    }
};

const anterior = () => {
    if (diapositivaActual.previousElementSibling.className.includes('d-container')) {
        try {
            diapositivaActual.style.display = 'none';
            diapositivaActual.previousElementSibling.style.display = 'flex';
            diapositivaActual = diapositivaActual.previousElementSibling;
        } catch (error) {}
    }
};

const activarPantallaCompleta = () => {
    const elem = document.documentElement;
    if (elem.requestFullscreen) {
        elem.requestFullscreen();
    } else if (elem.mozRequestFullScreen) {
        elem.mozRequestFullScreen();
    } else if (elem.webkitRequestFullscreen) {
        elem.webkitRequestFullscreen();
    } else if (elem.msRequestFullscreen) {
        elem.msRequestFullscreen();
    }
};

document.addEventListener('DOMContentLoaded', () => {
    diapositivaActual = primeraDiapositiva;
    primeraDiapositiva.style.display = 'flex';
});

document.addEventListener('fullscreenchange', (event) => {
    if (document.fullscreenElement) {
        document.querySelector('#diapositivaPosterior img').src = '';
    } else {
        document.querySelector('#diapositivaPosterior img').src = '../assets/icons/fullscreen.svg';
    }
});
