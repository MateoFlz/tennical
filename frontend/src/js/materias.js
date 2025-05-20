
$(document).ready(function () {
  cargarMaterias();
});

function cargarMaterias() {
  $.ajax({
    url: '/api/materias',
    type: 'GET',
    headers: {
      'Authorization': 'Bearer ' + localStorage.getItem('token')
    },
    success: function (data) {
      if ($.fn.DataTable.isDataTable('#materias-table')) {
        $('#materias-table').DataTable().clear().rows.add(data.data).draw();
      } else {
        $('#tabla-materias').html(`
          <table id="materias-table" class="display" style="width:100%">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Código</th>
                <th>Descripción</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        `);
        $('#materias-table').DataTable({
          data: data.data,
          columns: [
            { data: 'id', defaultContent: '-' },
            { data: 'nombre', defaultContent: '-' },
            { data: 'codigo', defaultContent: '-' },
            { data: 'descripcion', defaultContent: '-' },
            { data: null, render: function(data, type, row) {
                return `<button class='btn btn-sm btn-primary' onclick='editarMateria(${row.id})'>Editar</button> <button class='btn btn-sm btn-danger' onclick='eliminarMateria(${row.id})'>Eliminar</button>`;
              }
            }
          ]
        });
      }
    },
    error: function () {
      Swal.fire('Error', 'No se pudieron cargar las materias', 'error');
    }
  });
}

function mostrarModalMateria(materia) {
  const materiaData = materia && materia.data ? materia.data : materia;
  const isEdit = !!materiaData;
  
  Swal.fire({
    title: isEdit ? 'Editar Materia' : 'Nueva Materia',
    html: `
      <input id="swal-nombre" class="swal2-input" placeholder="Nombre" value="${materiaData ? materiaData.nombre : ''}" data-bs-toggle="tooltip" data-bs-placement="top" title="Nombre de la materia">
      <input id="swal-codigo" class="swal2-input" placeholder="Código" value="${materiaData ? materiaData.codigo : ''}" data-bs-toggle="tooltip" data-bs-placement="top" title="Código de la materia (ej: MATH-001)">
      <input id="swal-descripcion" class="swal2-input" placeholder="Descripción" value="${materiaData ? materiaData.descripcion : ''}" data-bs-toggle="tooltip" data-bs-placement="top" title="Descripción de la materia">
    `,
    focusConfirm: false,
    showCancelButton: true,
    preConfirm: () => {
      const nombre = $('#swal-nombre').val().trim();
      const codigo = $('#swal-codigo').val().trim();
      const descripcion = $('#swal-descripcion').val().trim();
      
      if (!nombre || !codigo || !descripcion) {
        Swal.showValidationMessage('Todos los campos son obligatorios');
        return false;
      }
      
      return { 
        nombre, 
        codigo, 
        descripcion 
      };
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
        actualizarMateria(materiaData.id, result.value);
      } else {
        crearMateria(result.value);
      }
    }
  });
}

function crearMateria(data) {
  $.ajax({
    url: '/api/materias',
    type: 'POST',
    headers: {
      'Authorization': 'Bearer ' + localStorage.getItem('token'),
      'Content-Type': 'application/json'
    },
    data: JSON.stringify(data),
    success: function () {
      Swal.fire('Éxito', 'Materia creada correctamente', 'success');
      cargarMaterias();
    },
    error: function (xhr) {
      Swal.fire('Error', xhr.responseJSON?.message || 'No se pudo crear la materia', 'error');
    }
  });
}

function editarMateria(id) {
  console.log('Iniciando edición de la materia con ID:', id);
  $.ajax({
    url: `/api/materias/${id}`,
    type: 'GET',
    headers: {
      'Authorization': 'Bearer ' + localStorage.getItem('token')
    },
    success: function (response) {
      console.log('Respuesta completa del servidor:', response);
      let materia;
      if (response && response.data) {
        materia = response;
      } else if (response && typeof response === 'object') {
        materia = { data: response };
      } else {
        materia = { data: {} };
      }
      
      mostrarModalMateria(materia);
    },
    error: function (xhr, status, error) {
      Swal.fire('Error', 'No se pudo cargar la materia', 'error');
    }
  });
}

function actualizarMateria(id, data) {
  $.ajax({
    url: `/api/materias/${id}`,
    type: 'PUT',
    headers: {
      'Authorization': 'Bearer ' + localStorage.getItem('token'),
      'Content-Type': 'application/json'
    },
    data: JSON.stringify(data),
    success: function () {
      Swal.fire('Éxito', 'Materia actualizada correctamente', 'success');
      cargarMaterias();
    },
    error: function (xhr) {
      Swal.fire('Error', xhr.responseJSON?.message || 'No se pudo actualizar la materia', 'error');
    }
  });
}

function eliminarMateria(id) {
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
        url: `/api/materias/${id}`,
        type: 'DELETE',
        headers: {
          'Authorization': 'Bearer ' + localStorage.getItem('token')
        },
        success: function () {
          Swal.fire('Eliminado', 'La materia ha sido eliminada.', 'success');
          cargarMaterias();
        },
        error: function (xhr) {
          let errorMessage = 'No se pudo eliminar la materia';
          
          if (xhr.responseJSON && xhr.responseJSON.message) {
            errorMessage = xhr.responseJSON.message;
          } else if (xhr.responseJSON && xhr.responseJSON.error) {
            errorMessage = xhr.responseJSON.error;
          } else if (xhr.responseText) {
            try {
              const response = JSON.parse(xhr.responseText);
              if (response.message) {
                errorMessage = response.message;
              } else if (response.error) {
                errorMessage = response.error;
              }
            } catch (e) {
              if (xhr.responseText.includes('message')) {
                const match = xhr.responseText.match(/"message":\s*"([^"]+)"/i);
                if (match && match[1]) {
                  errorMessage = match[1];
                }
              }
            }
          }
          
          if (errorMessage.includes('siendo utilizada')) {
            Swal.fire({
              title: 'No se puede eliminar',
              text: errorMessage,
              icon: 'warning',
              confirmButtonText: 'Entendido'
            });
          } else {
            Swal.fire('Error', errorMessage, 'error');
          }
        }
      });
    }
  });
}
