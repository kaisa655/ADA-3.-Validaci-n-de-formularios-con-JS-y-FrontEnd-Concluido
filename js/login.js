document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("formLogin");
  const mensaje = document.getElementById("mensajeLogin");
  form.addEventListener("submit", e => {
    e.preventDefault();
    const correo = document.getElementById("correoLogin");
    const clave = document.getElementById("claveLogin");
    [correo, clave].forEach(c => c.classList.remove("is-invalid"));
    let valido = true;
    if (!correo.checkValidity() || !correo.value.trim()) { correo.classList.add("is-invalid"); valido = false; }
    if (!clave.value.trim()) { clave.classList.add("is-invalid"); valido = false; }
    if (!valido) { mensaje.className = "alert alert-danger"; mensaje.textContent = "Introduce un correo válido y tu contraseña."; return; }
    mensaje.className = "alert alert-info"; mensaje.textContent = "Formulario validado. La autenticación real requiere un servidor y una base de datos.";
  });
});