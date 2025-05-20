$(document).ready(function () {
  cargarProfesores();
});

function cargarProfesores() {
  $.ajax({
    url: '/api/profesores',
    type: 'GET',
    headers: {
      'Authorization': 'Bearer ' + localStorage.getItem('token')
    },
    success: function (data) {
      renderizarTablaProfesores(data);
    },
    error: function () {
      Swal.fire('Error', 'No se pudieron cargar los profesores', 'error');
    }
  });
}

function renderizarTablaProfesores(data) {
  if ($.fn.DataTable.isDataTable('#profesores-table')) {
    $('#profesores-table').DataTable().clear().rows.add(data.data).draw();
  } else {
    $('#tabla-profesores').html(`
      <table id="profesores-table" class="display" style="width:100%">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Apellido</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    `);
    $('#profesores-table').DataTable({
      data: data.data,
      columns: [
        { data: 'id', defaultContent: '-' },
        { data: 'nombre', defaultContent: '-' },
        { data: 'apellido', defaultContent: '-' },
        { data: 'email', defaultContent: '-' },
        { data: 'telefono', defaultContent: '-' },
        { data: null, render: function(data, type, row) {
            return `<button class='btn btn-sm btn-primary' onclick='editarProfesor(${row.id})'>Editar</button> <button class='btn btn-sm btn-danger' onclick='eliminarProfesor(${row.id})'>Eliminar</button>`;
          }
        }
      ]
    });
  }
}

function mostrarModalProfesor(profesor) {
  console.log('Datos del profesor:', profesor); 
  
  const profesorData = profesor && profesor.data ? profesor.data : profesor;
  const isEdit = !!profesorData;
  
  Swal.fire({
    title: isEdit ? 'Editar Profesor' : 'Nuevo Profesor',
    html: `
      <input id="swal-nombre" class="swal2-input" placeholder="Nombre" value="${profesorData ? profesorData.nombre : ''}" data-bs-toggle="tooltip" data-bs-placement="top" title="Nombre del profesor">
      <input id="swal-apellido" class="swal2-input" placeholder="Apellido" value="${profesorData ? profesorData.apellido : ''}" data-bs-toggle="tooltip" data-bs-placement="top" title="Apellido del profesor">
      <input id="swal-email" class="swal2-input" placeholder="Email" value="${profesorData ? profesorData.email : ''}" data-bs-toggle="tooltip" data-bs-placement="top" title="Correo electrónico válido">
      <input id="swal-telefono" class="swal2-input" placeholder="Teléfono" value="${profesorData ? profesorData.telefono : ''}" data-bs-toggle="tooltip" data-bs-placement="top" title="Número de teléfono">
    `,
    focusConfirm: false,
    showCancelButton: true,
    preConfirm: () => {
      const nombre = $('#swal-nombre').val().trim();
      const apellido = $('#swal-apellido').val().trim();
      const email = $('#swal-email').val().trim();
      const telefono = $('#swal-telefono').val().trim();
      if (!nombre || !apellido || !email || !telefono) {
        Swal.showValidationMessage('Todos los campos son obligatorios');
        return false;
      }
      if (!/^[^@\s]+@[^@\s]+\.[^@\s]+$/.test(email)) {
        Swal.showValidationMessage('El email no es válido');
        return false;
      }
      return { nombre, apellido, email, telefono };
    },
    didOpen: () => {
      var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
      });
    }
  }).then((result) => {
    if (result.isConfirmed && result.value) {
      if (isEdit) {
        actualizarProfesor(profesorData.id, result.value);
      } else {
        crearProfesor(result.value);
      }
    }
  });
}

function crearProfesor(data) {
  $.ajax({
    url: '/api/profesores',
    type: 'POST',
    headers: {
      'Authorization': 'Bearer ' + localStorage.getItem('token'),
      'Content-Type': 'application/json'
    },
    data: JSON.stringify(data),
    success: function () {
      Swal.fire('Éxito', 'Profesor creado correctamente', 'success');
      cargarProfesores();
    },
    error: function (xhr) {
      Swal.fire('Error', xhr.responseJSON?.message || 'No se pudo crear el profesor', 'error');
    }
  });
}

function editarProfesor(id) {
  console.log('Iniciando edición del profesor con ID:', id);
  $.ajax({
    url: `/api/profesores/${id}`,
    type: 'GET',
    headers: {
      'Authorization': 'Bearer ' + localStorage.getItem('token')
    },
    success: function (response) {
      let profesor;
      if (response && response.data) {
        profesor = response;
      } else if (response && typeof response === 'object') {
        profesor = { data: response };
      } else {
        profesor = { data: {} };
      }
      
      mostrarModalProfesor(profesor);
    },
    error: function (xhr, status, error) {
      Swal.fire('Error', 'No se pudo cargar el profesor', 'error');
    }
  });
}

function actualizarProfesor(id, data) {
  $.ajax({
    url: `/api/profesores/${id}`,
    type: 'PUT',
    headers: {
      'Authorization': 'Bearer ' + localStorage.getItem('token'),
      'Content-Type': 'application/json'
    },
    data: JSON.stringify(data),
    success: function () {
      Swal.fire('Éxito', 'Profesor actualizado correctamente', 'success');
      cargarProfesores();
    },
    error: function (xhr) {
      Swal.fire('Error', xhr.responseJSON?.message || 'No se pudo actualizar el profesor', 'error');
    }
  });
}

function eliminarProfesor(id) {
  Swal.fire({
    title: '¿Estás seguro?',
    text: 'Esta acción no se puede deshacer',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: `/api/profesores/${id}`,
        type: 'DELETE',
        headers: {
          'Authorization': 'Bearer ' + localStorage.getItem('token')
        },
        success: function () {
          Swal.fire('Eliminado', 'El profesor ha sido eliminado.', 'success');
          cargarProfesores();
        },
        error: function () {
          Swal.fire('Error', 'No se pudo eliminar el profesor', 'error');
        }
      });
    }
  });
}
