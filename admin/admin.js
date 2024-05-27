// Función para obtener las cotizaciones desde el servidor
function obtenerCotizaciones() {
    fetch('obtener_cotizaciones.php')
        .then(response => response.json())
        .then(data => {
            console.log('Cotizaciones:', data);
            llenarTablaCotizaciones(data);
        })
        .catch(error => {
            console.error('Error al obtener las cotizaciones:', error);
        });
}

// Función para llenar la tabla con los datos de las cotizaciones
function llenarTablaCotizaciones(cotizaciones) {
    const tableContainer = document.getElementById('requestsTable');
    const table = document.createElement('table');
    table.classList.add('table', 'table-striped');

    const thead = document.createElement('thead');
    const theadRow = document.createElement('tr');
    const headers = ['ID', 'Empresa', 'Productos', 'Status', 'Acciones', 'Borrar'];
    headers.forEach(header => {
        const th = document.createElement('th');
        th.textContent = header;
        theadRow.appendChild(th);
    });
    thead.appendChild(theadRow);
    table.appendChild(thead);

    const tbody = document.createElement('tbody');
    cotizaciones.forEach(cotizacion => {
        const row = document.createElement('tr');
        const idCell = document.createElement('td');
        idCell.textContent = cotizacion.id_cotizacion;
        row.appendChild(idCell);

        const empresaCell = document.createElement('td');
        empresaCell.textContent = JSON.parse(cotizacion.empresa).nombre;
        row.appendChild(empresaCell);

        const productosCell = document.createElement('td');
        const productos = JSON.parse(cotizacion.productos);
        productos.forEach(producto => {
            const productoElement = document.createElement('div');
            productoElement.textContent = producto.nombre;
            productosCell.appendChild(productoElement);
        });
        row.appendChild(productosCell);

        const statusCell = document.createElement('td');
        statusCell.textContent = cotizacion.status;
        row.appendChild(statusCell);

        const accionesCell = document.createElement('td');
        const detallesButton = document.createElement('a');
        detallesButton.href = `detalles.php?id=${cotizacion.id_cotizacion}`;
        detallesButton.textContent = 'Ver detalles';
        detallesButton.classList.add('btn', 'btn-primary');
        accionesCell.appendChild(detallesButton);
        row.appendChild(accionesCell);
        
        //Boton para borrar cotizacion
        const borrarCell = document.createElement('td');
        const borrarButton = document.createElement('a');
        borrarButton.href = `borrar_cotizacion.php?id=${cotizacion.id_cotizacion}`;
        borrarButton.textContent = 'Borrar cotizacion';
        borrarButton.classList.add('btn', 'btn-danger');
        borrarCell.appendChild(borrarButton);
        row.appendChild(borrarCell);


        tbody.appendChild(row);
    });
    table.appendChild(tbody);
    tableContainer.appendChild(table);
}

// Llamar a la función para obtener las cotizaciones al cargar la página
obtenerCotizaciones();