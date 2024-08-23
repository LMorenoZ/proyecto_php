    <script>
        const descargar = (nombreArchivo, contenido) => {
            console.log(nombreArchivo, contenido)

            // Creamos un elemento de tipo enlace (anchor)
            const enlace = document.createElement('a');

            // Creamos un objeto Blob con el contenido del archivo y el tipo MIME (text/plain)
            const blob = new Blob([contenido], { type: 'text/plain' });

            // Creamos una URL temporal para el objeto Blob
            const url = URL.createObjectURL(blob);

            // Configuramos el enlace con la URL, el nombre del archivo y la acción de descarga
            enlace.href = url;
            enlace.download = nombreArchivo + '.txt'; // Agregamos la extensión .txt por defecto
            enlace.style.display = 'none'; // Ocultamos el enlace

            // Agregamos el enlace al documento y lo simulamos para iniciar la descarga
            document.body.appendChild(enlace);
            enlace.click();

            // Eliminamos el enlace y la URL temporal
            document.body.removeChild(enlace);
            URL.revokeObjectURL(url);
        };
    </script>

    <!-- BootSTrap 5 scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>