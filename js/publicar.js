document.addEventListener("DOMContentLoaded", () => {
  const formulario = document.getElementById("formPublicar");
  const campoImagen = document.getElementById("imagenJuego");
  const vista = document.getElementById("vistaPrevia");
  const mensaje = document.getElementById("mensajePublicar");
  let imagenBase64 = "";
  campoImagen.addEventListener("change", () => {
    const archivo = campoImagen.files[0];
    imagenBase64 = "";
    vista.classList.add("d-none");
    if (!archivo) return;
    if (!["image/jpeg", "image/png", "image/webp"].includes(archivo.type) || archivo.size > 1024 * 1024) {
      campoImagen.value = "";
      alert("Selecciona una imagen JPG, PNG o WEBP de máximo 1 MB.");
      return;
    }
    const lector = new FileReader();
    lector.onload = e => { imagenBase64 = e.target.result; vista.src = imagenBase64; vista.classList.remove("d-none"); };
    lector.readAsDataURL(archivo);
  });
  formulario.addEventListener("submit", event => {
    event.preventDefault();
    const titulo = document.getElementById("titulo");
    const descripcion = document.getElementById("descripcion");
    const categoria = document.getElementById("categoria");
    const url = document.getElementById("urlJuego");
    [titulo, descripcion, categoria, url].forEach(c => c.classList.remove("is-invalid"));
    let valido = true;
    if (titulo.value.trim().length < 2 || titulo.value.trim().length > 50) { titulo.classList.add("is-invalid"); valido = false; }
    if (descripcion.value.trim().length < 10 || descripcion.value.trim().length > 500) { descripcion.classList.add("is-invalid"); valido = false; }
    if (!categoria.value) { categoria.classList.add("is-invalid"); valido = false; }
    let urlValida = false;
    try { const u = new URL(url.value); urlValida = ["http:", "https:"].includes(u.protocol); } catch (_) {}
    if (!urlValida) { url.classList.add("is-invalid"); valido = false; }
    if (!valido) { mensaje.className = "alert alert-danger"; mensaje.textContent = "Revisa los campos marcados antes de publicar."; return; }
    const juego = {id: Date.now(), titulo: titulo.value.trim(), descripcion: descripcion.value.trim(), categoria: categoria.value, url: url.value.trim(), imagen: imagenBase64, fecha: new Date().toISOString()};
    try {
      const juegos = obtenerJuegos(); juegos.push(juego); guardarJuegos(juegos);
      mensaje.className = "alert alert-success"; mensaje.textContent = "¡Tu juego se agregó al catálogo de este navegador!";
      formulario.reset(); imagenBase64 = ""; vista.classList.add("d-none");
    } catch (error) {
      mensaje.className = "alert alert-danger"; mensaje.textContent = "No se pudo guardar. Prueba con una imagen más pequeña o elimina algunos datos del navegador.";
    }
  });
});