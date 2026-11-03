// Funciones compartidas por las páginas de PublicArcade.
const CLAVE_JUEGOS = "publicarcade_juegos";
function obtenerJuegos() {
  try { return JSON.parse(localStorage.getItem(CLAVE_JUEGOS)) || []; }
  catch (error) { return []; }
}
function guardarJuegos(juegos) {
  localStorage.setItem(CLAVE_JUEGOS, JSON.stringify(juegos));
}
function escaparHTML(texto) {
  return String(texto).replace(/[&<>"']/g, caracter => ({
    "&":"&amp;", "<":"&lt;", ">":"&gt;", '"':"&quot;", "'":"&#039;"
  })[caracter]);
}
function tarjetaJuego(juego) {
  const imagen = juego.imagen || "https://placehold.co/640x360/211b32/d8c3ff?text=PublicArcade";
  return `<div class="col-md-6 col-lg-4"><article class="game-card">
    <img src="${imagen}" alt="Portada de ${escaparHTML(juego.titulo)}">
    <div class="card-body"><span class="badge-category">${escaparHTML(juego.categoria)}</span>
    <h3 class="mt-3">${escaparHTML(juego.titulo)}</h3>
    <p>${escaparHTML(juego.descripcion)}</p>
    <a class="btn btn-sm btn-primary" href="${escaparHTML(juego.url)}" target="_blank" rel="noopener noreferrer">Jugar / visitar</a>
    </div></article></div>`;
}
