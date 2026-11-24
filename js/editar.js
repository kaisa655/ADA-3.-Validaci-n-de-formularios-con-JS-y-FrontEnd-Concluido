
document.addEventListener("DOMContentLoaded", async () => {
    const formulario = document.getElementById("formEditar");
    const mensaje = document.getElementById("mensajeEditar");
    const idJuego = document.getElementById("idJuego");
    const titulo = document.getElementById("titulo");
    const descripcion = document.getElementById("descripcion");
    const categoria = document.getElementById("categoria");
    const urlJuego = document.getElementById("urlJuego");
    const imagen = document.getElementById("imagenJuego");
    const portadaActual = document.getElementById("portadaActual");

    const parametros = new URLSearchParams(window.location.search);
    const id = parametros.get("id");

    function mostrarMensaje(texto, tipo) {
        mensaje.className = `alert alert-${tipo}`;
        mensaje.textContent = texto;
    }

    if (!id || !/^\d+$/.test(id)) {
        mostrarMensaje("No se especificó un videojuego válido.", "danger");
        formulario.classList.add("d-none");
        return;
    }

    // Cargar información del videojuego
    try {
        const respuesta = await fetch(
            `obtener_juego.php?id=${encodeURIComponent(id)}`
        );

        const resultado = await respuesta.json();

        if (!respuesta.ok || !resultado.exito) {
            throw new Error(resultado.mensaje || "No se pudo cargar el videojuego.");
        }

        const juego = resultado.juego;

        idJuego.value = juego.id;
        titulo.value = juego.titulo;
        descripcion.value = juego.descripcion;
        categoria.value = juego.categoria;
        urlJuego.value = juego.url_juego;

        if (juego.portada) {
            portadaActual.src = juego.portada;
        } else {
            portadaActual.classList.add("d-none");
        }

    } catch (error) {
        mostrarMensaje(error.message, "danger");
        formulario.classList.add("d-none");
        return;
    }

    // Mostrar vista previa de la nueva portada
    imagen.addEventListener("change", () => {
        const archivo = imagen.files[0];

        if (!archivo) return;

        const tipos = ["image/jpeg", "image/png", "image/webp"];

        if (!tipos.includes(archivo.type) || archivo.size > 1024 * 1024) {
            imagen.value = "";
            mostrarMensaje("Selecciona una imagen válida de máximo 1 MB.", "danger");
            return;
        }

        portadaActual.src = URL.createObjectURL(archivo);
        portadaActual.classList.remove("d-none");
    });

    // Enviar cambios a PHP
    formulario.addEventListener("submit", async (event) => {
        event.preventDefault();

        const datos = new FormData(formulario);
        const boton = formulario.querySelector('button[type="submit"]');

        boton.disabled = true;
        boton.textContent = "Guardando...";

        try {
            const respuesta = await fetch("editar_juego.php", {
                method: "POST",
                body: datos,
                credentials: "same-origin"
            });

            const resultado = await respuesta.json();

            if (!respuesta.ok || !resultado.exito) {
                throw new Error(resultado.mensaje || "No se pudieron guardar los cambios.");
            }

            mostrarMensaje(resultado.mensaje, "success");

        } catch (error) {
            mostrarMensaje(error.message, "danger");

        } finally {
            boton.disabled = false;
            boton.textContent = "Guardar cambios";
        }
    });
});
