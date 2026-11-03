document.addEventListener("DOMContentLoaded", () => {
  const fecha = document.getElementById("fecha");
  if (fecha) fecha.textContent = new Date().toLocaleDateString("es-MX", {weekday:"long", year:"numeric", month:"long", day:"numeric"});
  const contenedor = document.getElementById("juegosInicio");
  const juegos = obtenerJuegos().slice(-3).reverse();
  if (juegos.length) contenedor.innerHTML = juegos.map(tarjetaJuego).join("");
  else contenedor.innerHTML = `<div class="col-12"><p class="empty-state">Aún no hay juegos publicados. ¡Puedes agregar el primero!</p></div>`;
});