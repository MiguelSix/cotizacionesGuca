/* Navegacion de botones */
const btnPrev = document.getElementById('btnPrev');
const btnNext = document.getElementById('btnNext');
const content = document.querySelector('.content');

// Secuencia de secciones
btnNext.addEventListener('click', () => {
  currentSection = Math.min(currentSection + 1, 3); // Cambia '3' al número de secciones menos 1
  content.style.transform = `translateX(-${currentSection * 100}vw)`;
  updateActiveDot(currentSection);
});

btnPrev.addEventListener('click', () => {
  currentSection = Math.max(currentSection - 1, 0);
  content.style.transform = `translateX(-${currentSection * 100}vw)`;
  updateActiveDot(currentSection);
});

/* Codigo de los dots de navegación */
let currentSection = 0;

const dots = document.querySelectorAll('.dot');

function updateActiveDot(index) {
  dots.forEach((dot, i) => {
    if (i === index) {
      dot.classList.add('active-dot');
    } else {
      dot.classList.remove('active-dot');
    }
  });
}