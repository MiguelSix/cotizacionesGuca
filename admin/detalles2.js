const urlParams = new URLSearchParams(window.location.search);
const idCotizacion = urlParams.get('id');

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

    empresaNombreElement.textContent = `Nombre: ${cotizacion.empresa.nombre}`;
    empresaTelefonoElement.textContent = `Teléfono: ${cotizacion.empresa.telefono}`;
    empresaCorreoElement.textContent = `Correo: ${cotizacion.empresa.correo}`;

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

/* function generarPresupuesto() {
    const comentarios = document.getElementById('comentarios').value;
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
    .then(response => response.text())
    .then(data => {
        console.log(data);
    })
    .catch(error => {
        console.error('Error al generar el presupuesto:', error);
    });
} */

obtenerDetallesCotizacion();