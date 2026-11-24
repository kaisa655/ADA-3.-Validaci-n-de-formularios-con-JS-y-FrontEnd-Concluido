
document.addEventListener("DOMContentLoaded", () => {
    const formulario = document.getElementById("formPublicar");
    const campoImagen = document.getElementById("imagenJuego");
    const vista = document.getElementById("vistaPrevia");
    const mensaje = document.getElementById("mensajePublicar");

    // Vista previa de la imagen
    campoImagen.addEventListener("change", () => {
        const archivo = campoImagen.files[0];

        vista.classList.add("d-none");

        if (!archivo) return;

        const tiposPermitidos = [
            "image/jpeg",
            "image/png",
            "image/webp"
        ];

        if (!tiposPermitidos.includes(archivo.type) ||
            archivo.size > 1024 * 1024) {

            campoImagen.value = "";

            alert("Selecciona una imagen JPG, PNG o WEBP de máximo 1 MB.");
            return;
        }

        vista.src = URL.createObjectURL(archivo);
        vista.classList.remove("d-none");
    });

    // Enviar el formulario al servidor
    formulario.addEventListener("submit", async (event) => {
        event.preventDefault();

        const titulo = document.getElementById("titulo");
        const descripcion = document.getElementById("descripcion");
        const categoria = document.getElementById("categoria");
        const url = document.getElementById("urlJuego");

        [titulo, descripcion, categoria, url].forEach(campo => {
            campo.classList.remove("is-invalid");
        });

        let valido = true;

        if (titulo.value.trim().length < 2 ||
            titulo.value.trim().length > 50) {
            titulo.classList.add("is-invalid");
            valido = false;
        }

        if (descripcion.value.trim().length < 10 ||
            descripcion.value.trim().length > 500) {
            descripcion.classList.add("is-invalid");
            valido = false;
        }

        if (!categoria.value) {
            categoria.classList.add("is-invalid");
            valido = false;
        }

        let urlValida = false;

        try {
            const direccion = new URL(url.value);
            urlValida = ["http:", "https:"].includes(direccion.protocol);
        } catch (_) {}

        if (!urlValida) {
            url.classList.add("is-invalid");
            valido = false;
        }

        if (!valido) {
            mensaje.className = "alert alert-danger";
            mensaje.textContent = "Revisa los campos marcados.";
            return;
        }

        // FormData permite enviar también la imagen
        const datos = new FormData(formulario);

        const boton = formulario.querySelector(
            'button[type="submit"]'
        );

        boton.disabled = true;
        boton.textContent = "Publicando...";

        try {
            const respuesta = await fetch("crear_juego.php", {
                method: "POST",
                body: datos,
                credentials: "same-origin"
            });

            const resultado = await respuesta.json();

            if (!respuesta.ok || !resultado.exito) {
                throw new Error(
                    resultado.mensaje || "No se pudo publicar el juego."
                );
            }

            mensaje.className = "alert alert-success";
            mensaje.textContent = resultado.mensaje;

            formulario.reset();
            vista.removeAttribute("src");
            vista.classList.add("d-none");

        } catch (error) {
            mensaje.className = "alert alert-danger";
            mensaje.textContent = error.message;
        } finally {
            boton.disabled = false;
            boton.textContent = "Publicar videojuego";
        }
    });
});
