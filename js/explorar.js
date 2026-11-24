document.addEventListener("DOMContentLoaded", async () => {
  const contenedor = document.getElementById("catalogoJuegos");
  const busqueda = document.getElementById("buscarJuego");
  const filtro = document.getElementById("filtroCategoria");
  const sinJuegos = document.getElementById("sinJuegos");

  let juegos = [];

  async function cargarJuegos() {
    try {
      const respuesta = await fetch("listar_juegos.php");
      const resultado = await respuesta.json();

      if (!respuesta.ok || !resultado.exito) {
        throw new Error("No se pudieron cargar los videojuegos.");
      }

      juegos = resultado.juegos;
      renderizar();

    } catch (error) {
      contenedor.innerHTML = "";
      sinJuegos.textContent = error.message;
      sinJuegos.classList.remove("d-none");
    }
  }

  function renderizar() {
    const termino = busqueda.value.trim().toLowerCase();
    const categoria = filtro.value;

    const filtrados = juegos.filter(j =>
      (j.titulo.toLowerCase().includes(termino) ||
       j.descripcion.toLowerCase().includes(termino)) &&
      (!categoria || j.categoria === categoria)
    ).sort((a, b) =>
      a.titulo.localeCompare(b.titulo, "es")
    );

    contenedor.innerHTML = filtrados.map(tarjetaJuego).join("");

    sinJuegos.textContent = "No se encontraron videojuegos.";
    sinJuegos.classList.toggle("d-none", filtrados.length > 0);
  }

  busqueda.addEventListener("input", renderizar);
  filtro.addEventListener("change", renderizar);

  await cargarJuegos();
});
