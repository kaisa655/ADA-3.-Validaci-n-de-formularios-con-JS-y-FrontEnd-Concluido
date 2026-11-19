
document.addEventListener("DOMContentLoaded", () => {
  const contenedor = document.getElementById("catalogoJuegos");
  const busqueda = document.getElementById("buscarJuego");
  const filtro = document.getElementById("filtroCategoria");
  const sinJuegos = document.getElementById("sinJuegos");

  function renderizar() {
    const termino = busqueda.value.trim().toLowerCase();
    const categoria = filtro.value;

    const juegos = obtenerJuegos().filter(j =>
      (j.titulo.toLowerCase().includes(termino) ||
       j.descripcion.toLowerCase().includes(termino)) &&
      (!categoria || j.categoria === categoria)
    ).sort((a, b) =>
      a.titulo.localeCompare(b.titulo, "es")
    );

    contenedor.innerHTML = juegos.map(tarjetaJuego).join("");
    sinJuegos.classList.toggle("d-none", juegos.length > 0);
  }

  busqueda.addEventListener("input", renderizar);
  filtro.addEventListener("change", renderizar);
  renderizar();
});
