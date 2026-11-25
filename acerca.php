<?php
require_once __DIR__ . '/includes/funciones.php';
require_once __DIR__ . '/includes/header.php';
?>

<main>

    <!-- Presentación -->
    <section class="container py-5 text-center">
        <p class="eyebrow">SOBRE NOSOTROS</p>

        <h1>
            Acerca de <span>PublicArcade</span>
        </h1>

        <p class="text-secondary mt-3">
            Una plataforma creada para compartir, descubrir
            y disfrutar videojuegos.
        </p>
    </section>

    <!-- Información del proyecto -->
    <section class="container pb-5">
        <div class="info-card p-4">
            <h2>¿Qué es PublicArcade?</h2>

            <p>
                PublicArcade es una plataforma donde los usuarios
                pueden descubrir, explorar y publicar videojuegos.
                Nuestro objetivo es crear una comunidad en la que
                los amantes de los videojuegos puedan compartir
                sus creaciones y conocer nuevos juegos.
            </p>
        </div>
    </section>

    <!-- Creadores -->
    <section class="container py-5">

        <div class="text-center mb-5">
            <p class="eyebrow">NUESTRO EQUIPO</p>

            <h2>
                Conoce a los <span>creadores</span>
            </h2>

            <p class="text-secondary">
                Las personas detrás de PublicArcade.
            </p>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-9 col-lg-8">

                <div class="game-card p-3 text-center">

                    <img
                        src="img/creadores.png"
                        alt="Creadores de PublicArcade"
                        class="img-fluid rounded mb-4"
                        style="width:100%; max-height:550px; object-fit:cover;"
                    >

                    <div class="row g-3">

                        <div class="col-md-6">
                            <h3>Máximo Algandro</h3>
                            <p class="text-secondary">
                                Creador de PublicArcade
                            </p>
                        </div>

                        <div class="col-md-6">
                            <h3>Rodrigo Pacheco</h3>
                            <p class="text-secondary">
                                Creador de PublicArcade
                            </p>
                        </div>

                    </div>

                    <div class="mt-3">
                        <p>
                            Desarrolladores de una plataforma
                            pensada para la comunidad gamer.
                        </p>
                    </div>

                </div>

            </div>
        </div>

    </section>

</main>

<?php
require_once __DIR__ . '/includes/footer.php';
?>