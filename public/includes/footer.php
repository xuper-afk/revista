</main>

<footer class="bg-dark text-white mt-5">
    <div class="container py-4">
        <div class="row">

            <div class="col-md-5 mb-3">
                <h5>Revista Digital</h5>
                <p class="small mb-0">
                    Revista digital dedicada a la publicación de reportajes,
                    noticias, análisis y contenido de interés para la sociedad.
                </p>
            </div>

            <div class="col-md-3 mb-3">
                <h5>Secciones</h5>
                <ul class="list-unstyled small">
                    <li><a href="<?= urlPublic('reportajes.php') ?>" class="text-white-50 text-decoration-none">Reportajes</a></li>
                    <li><a href="<?= urlPublic('noticias.php') ?>" class="text-white-50 text-decoration-none">Noticias</a></li>
                    <li><a href="<?= urlPublic('boletines.php') ?>" class="text-white-50 text-decoration-none">Boletines NTEP</a></li>
                    <li><a href="<?= urlPublic('podcasts.php') ?>" class="text-white-50 text-decoration-none">Podcasts</a></li>
                    <li><a href="<?= urlPublic('videos.php') ?>" class="text-white-50 text-decoration-none">Videos</a></li>
                </ul>
            </div>

            <div class="col-md-4 mb-3">
                <h5>Contacto</h5>
                <p class="small mb-1">📧 023100272G@uandina.edu.pe</p>
                <p class="small mb-1">📍 Cusco - Perú</p>
                <p class="small mb-0">Síguenos en nuestras redes sociales.</p>
            </div>

        </div>

        <hr class="border-secondary">

        <div class="text-center">
            <small>
                &copy; <?= date('Y') ?> Revista Digital. Todos los derechos reservados.
            </small>
            <br>
            <a href="/revista/admin/login.php" class="text-white-50 text-decoration-none" style="font-size:.75rem">
                Acceso editores
            </a>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>