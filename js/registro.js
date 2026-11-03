document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("formRegistro");
  const mensaje = document.getElementById("mensajeRegistro");
  form.addEventListener("submit", e => {
    e.preventDefault();
    const nombre = document.getElementById("nombreUsuario");
    const correo = document.getElementById("correoRegistro");
    const clave = document.getElementById("claveRegistro");
    const confirmar = document.getElementById("confirmarClave");
    const terminos = document.getElementById("aceptarTerminos");
    [nombre, correo, clave, confirmar, terminos].forEach(c => c.classList.remove("is-invalid"));
    let valido = true;
    if (!/^[a-zA-Z0-9_]{3,20}$/.test(nombre.value.trim())) { nombre.classList.add("is-invalid"); valido = false; }
    if (!correo.checkValidity() || !correo.value.trim()) { correo.classList.add("is-invalid"); valido = false; }
    if (clave.value.length < 8) { clave.classList.add("is-invalid"); valido = false; }
    if (confirmar.value !== clave.value || !confirmar.value) { confirmar.classList.add("is-invalid"); valido = false; }
    if (!terminos.checked) { terminos.classList.add("is-invalid"); valido = false; }
    if (!valido) { mensaje.className = "alert alert-danger"; mensaje.textContent = "Corrige los campos señalados."; return; }
    // Solo se simula el registro para demostrar la validación. No se guarda la contraseña.
    mensaje.className = "alert alert-success"; mensaje.textContent = "Validación correcta. En una versión conectada al servidor, aquí se enviaría el registro.";
    form.reset();
  });
});