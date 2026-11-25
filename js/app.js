// Comportamientos pequeños de interfaz; todas las páginas y rutas son PHP.
document.addEventListener('submit', (event) => {
  const form = event.target;
  if (form.matches('[data-confirm]') && !confirm(form.dataset.confirm)) {
    event.preventDefault();
  }
});
