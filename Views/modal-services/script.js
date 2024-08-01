document.addEventListener('DOMContentLoaded', function () {
    const openModalBtn = document.getElementById('openModalBtn');
    const modal = new bootstrap.Modal(document.getElementById('modal'));

    openModalBtn.addEventListener('click', function () {
        modal.show();
    });

    const visitaSelect = document.getElementById('visita');
    const direccionDiv = document.getElementById('direccionDiv');

    visitaSelect.addEventListener('change', function () {
        if (this.value === 'vis_tec') {
            direccionDiv.style.display = 'block';
        } else {
            direccionDiv.style.display = 'none';
        }
    });

    // Configuración de Flatpickr para el campo de fecha
    const fechaInput = document.getElementById('fecha');

    fetch('Views/modal-services/get_unavailable_dates.php')
        .then(response => response.json())
        .then(data => {
            flatpickr(fechaInput, {
                dateFormat: "Y-m-d",
                disable: data.unavailableDates,
                minDate: "today" // Deshabilitar fechas pasadas
            });
        })
        .catch(error => console.error('Error al obtener las fechas no disponibles:', error));

    // Mostrar la alerta de éxito si está presente
    const successAlert = document.getElementById('success-alert');
    if (successAlert) {
        successAlert.style.display = 'block';
        setTimeout(() => {
            successAlert.style.display = 'none';
        }, 5000);
    }
});
