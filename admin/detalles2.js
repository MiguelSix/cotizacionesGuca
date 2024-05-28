const urlParams = new URLSearchParams(window.location.search);
const idCotizacion = urlParams.get('id');
const generarPresupuestoBtn = document.getElementById('generarPresupuestoBtn');

generarPresupuestoBtn.addEventListener('click', generarPresupuesto);

function obtenerDetallesCotizacion() {
    fetch(`detalles.php?id=${idCotizacion}`)
        .then(response => response.json())
        .then(data => {
            mostrarDetallesCotizacion(data);
        })
        .catch(error => {
            console.error('Error al obtener los detalles de la cotización:', error);
        });
}

function mostrarDetallesCotizacion(cotizacion) {

    const empresaNombreElement = document.getElementById('empresaNombre');
    const empresaTelefonoElement = document.getElementById('empresaTelefono');
    const empresaCorreoElement = document.getElementById('empresaCorreo');
    const productosContainer = document.getElementById('productosContainer');
    const cotizacionIdElement = document.getElementById('cotizacionId');

    empresaNombreElement.textContent = `Nombre: ${cotizacion.empresa.nombre}`;
    empresaTelefonoElement.textContent = `Teléfono: ${cotizacion.empresa.telefono}`;
    empresaCorreoElement.textContent = `Correo: ${cotizacion.empresa.correo}`;
    cotizacionIdElement.textContent = `ID de la cotización: ${idCotizacion}`;

    productosContainer.innerHTML = cotizacion.productos.map(producto => `
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">${producto.nombre}</h5>
            <p class="card-text">Marca: ${producto.marca}</p>
            <p class="card-text">Cantidad: ${producto.cantidad}</p>
            <p class="card-text">Descripción: ${producto.descripcion}</p>
            <p class="card-text">Color: ${producto.color}</p>
            <p class="card-text">Dimensiones: ${producto.dimensiones}</p>
            <p class="card-text">Material: ${producto.material}</p>
            <div class="form-group">
                <label for="precio-${producto.id}">Precio:</label>
                <input type="number" class="form-control" id="precio-${producto.id}" step="50" required>
            </div>
            <div class="form-group">
                <label for="comentarios-${producto.id}">Comentarios:</label>
                <textarea class="form-control" id="comentarios-${producto.id}" rows="3"></textarea>
            </div>
        </div>
    </div>
    `).join('');
}

function generarPresupuesto() {
    const comentarios = Array.from(document.querySelectorAll('textarea')).map(textarea => textarea.value);
    const precios = Array.from(document.querySelectorAll('input[type="number"]')).map(input => ({
        idProducto: input.id.split('-')[1],
        precio: input.value
    }));

    fetch('generar_presupuesto.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            idCotizacion,
            comentarios,
            precios
        })
    })
    .then(response => {
        if (response.ok) {
            response.blob().then(blob => {
                const url = window.URL.createObjectURL(blob);
                const printWindow = window.open(url, '_blank');
                printWindow.onload = function() {
                    printWindow.print();
                    window.URL.revokeObjectURL(url);
                };
            });
        } else {
            console.error('Error al generar el presupuesto');
        }
    })
    .catch(error => {
        console.error('Error al generar el presupuesto:', error);
    });
}

obtenerDetallesCotizacion();