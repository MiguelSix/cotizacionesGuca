// Obtiene los elementos del formulario
const nombreInput = document.getElementById('nombre');
const correoInput = document.getElementById('correo');
const telefonoInput = document.getElementById('telefono');

//Obtiene el boton de agregar Producto
const btnAgregarProducto = document.getElementById('btnAgregarProducto');

//Crea una Lista de productos
const ProductList = [];

/* ------------------------ Seccion Productos ----------------------------------- */

//Obtiene los elementos del formulario de productos
const nombreproductoInput = document.getElementById('nombreproducto');
const marcaproductoInput = document.getElementById('marcaproducto');
const cantidadproductoInput = document.getElementById('cantidadproducto');
const descripcionproductoInput = document.getElementById('descripcionproducto');

//Obtiene el cuerpo de la tabla de previsualizacion
const tablaprevisualizacion = document.getElementById('tablaprevisualizacion');

// Función para actualizar la tabla de previsualizacio
function actualizarTabla() {
  //crea fila de la ultima componente de la lista de productos
  var fila = document.createElement("tr");
  var i = ProductList.length - 1;
  //agrega nombre a la fila
  var celda = document.createElement("td");
  celda.textContent = ProductList[i].nombre;
  fila.appendChild(celda);
  //agrega marca a la fila
  var celda = document.createElement("td");
  celda.textContent = ProductList[i].marca;
  fila.appendChild(celda);
  //agrega cantidad a la fila
  var celda = document.createElement("td");
  celda.textContent = ProductList[i].cantidad;
  fila.appendChild(celda);
  //agrega descripcion a la fila
  var celda = document.createElement("td");
  celda.textContent = ProductList[i].descripcion;
  fila.appendChild(celda);
  //agrega la fila a la tabla
  tablaprevisualizacion.appendChild(fila);
};

//Agrega productos a la lista al clickear el boton
btnAgregarProducto.addEventListener('click', () => {
  if (nombreproductoInput.value && marcaproductoInput.value && cantidadproductoInput.value && descripcionproductoInput.value) {
    var Producto = {
      nombre: nombreproductoInput.value,
      marca: marcaproductoInput.value,
      cantidad: cantidadproductoInput.value,
      descripcion: descripcionproductoInput.value
    };
    ProductList.push(Producto);
    actualizarTabla();
    nombreproductoInput.value = '';
    marcaproductoInput.value = '';
    cantidadproductoInput.value = '';
    descripcionproductoInput.value = '';
  }
});

btnAgregarProducto.addEventListener('click', actualizarResumen);

/* ---------------------------- Seccion Productos ------------------------------- */

/* ----------------------------- Detalles de los Productos ---------------------- */

// Obtiene el cuerpo de la tabla de productos
const tablaProductos = document.getElementById('tabla-productos');

// Función para renderizar la tabla de productos
function renderizarTablaProductos() {
  tablaProductos.innerHTML = '';

  ProductList.forEach((producto, index) => {
    const row = document.createElement('tr');
    row.innerHTML = `
      <td>${producto.nombre}</td>
      <td>${producto.marca}</td>
      <td>${producto.cantidad}</td>
      <td>${producto.descripcion}</td>
      <td><button class="btn btn-primary" data-toggle="modal" data-target="#modalDetalles" id="agregarDetalles">Agregar detalles</button></td>
    `;
    tablaProductos.appendChild(row);
  });
}

// Llama a la función renderizarTablaProductos cuando se actualice la lista de productos
btnAgregarProducto.addEventListener('click', renderizarTablaProductos);
btnAgregarProducto.addEventListener('click', actualizarResumen);

// Obtiene el nombre del producto al pulsar el botón de agregar detalles

const btnAgregarDetalles = document.getElementById('agregarDetalles');

const btnGuardarDetalles = document.getElementById('guardarDetalles');
// Función para guardar los detalles del producto
function guardarDetallesProducto() {
  const color = colorProducto.value;
  const dimensiones = dimensionesProducto.value;
  const material = materialProducto.value;

  console.log('Color:', color);
  console.log('Dimensiones:', dimensiones);
  console.log('Material:', material);

  const producto = ProductList[ProductList.length - 1];

  producto.color = color;
  producto.dimensiones = dimensiones;
  producto.material = material;

  // Limpia los campos del modal
  colorProducto.value = '';
  dimensionesProducto.value = '';
  materialProducto.value = '';

  // Cierra el modal
  $('#modalDetalles').modal('hide');

  ProductList.forEach(producto => {
    console.log('Producto:', producto);
  });

  actualizarResumen();
}

// Evento para guardar los detalles del producto
btnGuardarDetalles.addEventListener('click', guardarDetallesProducto);
btnGuardarDetalles.addEventListener('click', actualizarResumen);

/* ----------------------------- Detalles de los Productos ---------------------- */

/* ----------------------------- Resumen de los Productos -- -------------------- */

const resumenProductos = document.getElementById('resumen-productos');

function actualizarResumen() {
  resumenProductos.innerHTML = '';
  ProductList.forEach(producto => {
    const row = document.createElement('tr');
    row.innerHTML = `
      <td>${producto.nombre}</td>
      <td>${producto.marca}</td>
      <td>${producto.cantidad}</td>
      <td>${producto.descripcion}</td>
      <td>${producto.color || ''}</td>
      <td>${producto.dimensiones || ''}</td>
      <td>${producto.material || ''}</td>
      <td><button class="btn btn-danger btn-eliminar">Eliminar</button></td>
    `;
    resumenProductos.appendChild(row);
  });
}

// Agregar el evento click a los botones de eliminar
const botonesEliminar = document.querySelectorAll('.btn-eliminar');
botonesEliminar.forEach((boton, index) => {
  boton.addEventListener('click', () => {
    eliminarProducto(index);
  });
});

// Función para eliminar un producto de la lista
function eliminarProducto(index) {
  ProductList.splice(index, 1);
  actualizarResumen();
  renderizarTablaProductos();
}

actualizarResumen();

/* ----------------------------- Resumen de los Productos ----------------------- */


/* ----------------------------- Generar Cotización ----------------------------- */

// Función para generar un ID de cotización
function generarIdCotizacion() {
  const fecha = new Date();
  const dia = fecha.getDate().toString().padStart(2, '0');
  const mes = (fecha.getMonth() + 1).toString().padStart(2, '0');
  const anio = fecha.getFullYear();
  const identificador = Math.floor(Math.random() * 900000) + 100000;
  return `${dia}${mes}${anio}-${identificador}`;
}

// Función para guardar los datos de la cotización en la base de datos
function guardarCotizacion() {
  const empresa = {
    nombre: nombreInput.value,
    correo: correoInput.value,
    telefono: telefonoInput.value
  };

  const productos = [];
  ProductList.forEach(producto => {
    productos.push({
      nombre: producto.nombre,
      marca: producto.marca,
      cantidad: producto.cantidad,
      descripcion: producto.descripcion,
      color: producto.color || '',
      dimensiones: producto.dimensiones || '',
      material: producto.material || ''
    });
  });

  const idCotizacion = generarIdCotizacion();

  const datos = { empresa, productos, idCotizacion };

  // Send data to server using fetch on php 

  fetch('guardar_cotizacion.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify(datos)
  })

  .then(response => response.text())
  .then(data => {
    console.log('Respuesta del servidor:', data);
  })
  .catch(error => {
    console.error('Error:', error);
  });

  console.log('Datos de la cotización:', {
    datos
  });

  alert(`Cotización generada con ID: ${idCotizacion}`);
}

// Evento para generar la cotización
const btnGenerarCotizacion = document.getElementById('generarCotizacion');
btnGenerarCotizacion.addEventListener('click', guardarCotizacion);