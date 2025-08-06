// Espera a que todo el contenido del DOM (la página) se haya cargado completamente.
document.addEventListener('DOMContentLoaded', function() {
    
    // Selecciona todos los botones de reacción que tengan la clase 'emoji-reaction-button'.
    const reactionButtons = document.querySelectorAll('.emoji-reaction-button');

    // Itera sobre cada botón encontrado.
    reactionButtons.forEach(button => {
        // Añade un 'escuchador de eventos' que se activará cuando se haga clic en el botón.
        button.addEventListener('click', function() {
            
            // --- 1. OBTENER DATOS ---
            // 'this' se refiere al botón que fue presionado.
            // Obtenemos el ID del post desde el atributo 'data-post-id'.
            const postId = this.dataset.postId;
            // Obtenemos el emoji desde el atributo 'data-emoji'.
            const emoji = this.dataset.emoji;
            // Encontramos el elemento <span> que muestra el contador dentro del botón.
            const countSpan = this.querySelector('.emoji-reaction-count');

            // --- 2. ACTUALIZACIÓN OPTIMISTA ---
            // Incrementamos el contador en la interfaz de usuario inmediatamente, sin esperar la respuesta del servidor.
            // Esto hace que la interacción se sienta instantánea para el usuario.
            let currentCount = parseInt(countSpan.textContent);
            countSpan.textContent = currentCount + 1;

            // --- 3. PETICIÓN AJAX ---
            // 'fetch' es la forma moderna de hacer peticiones de red en JavaScript.
            // 'er_ajax.ajax_url' es la URL para las peticiones AJAX de WordPress, que pasamos desde PHP.
            fetch(er_ajax.ajax_url, {
                method: 'POST', // Usamos el método POST para enviar datos.
                headers: {
                    // Especificamos el tipo de contenido que estamos enviando.
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                // Construimos el cuerpo de la petición con los datos necesarios.
                // 'action' le dice a WordPress qué función de AJAX debe ejecutar.
                // 'encodeURIComponent' codifica el emoji para que se transmita de forma segura por la red.
                // 'er_ajax.nonce' es un token de seguridad para verificar que la petición es legítima.
                body: `action=er_react&post_id=${postId}&emoji=${encodeURIComponent(emoji)}&nonce=${er_ajax.nonce}`
            })
            .then(response => response.json()) // Convertimos la respuesta del servidor de texto a un objeto JSON.
            .then(data => {
                // --- 4. MANEJAR RESPUESTA DEL SERVIDOR ---
                if (data.success) {
                    // Si el servidor confirma que todo fue bien, actualizamos el contador con el valor real de la base de datos.
                    // Esto asegura que el contador sea preciso, incluso si varios usuarios reaccionan al mismo tiempo.
                    countSpan.textContent = data.data.new_count;
                } else {
                    // Si el servidor devuelve un error, revertimos la actualización optimista.
                    countSpan.textContent = currentCount;
                    // Mostramos un error en la consola del navegador para depuración.
                    console.error('Error al guardar la reacción:', data.data);
                }
            })
            .catch(error => {
                // Si hay un error de red (ej. sin conexión a internet), también revertimos el cambio.
                countSpan.textContent = currentCount;
                console.error('Error de red:', error);
            });
        });
    });
});