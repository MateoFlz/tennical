
$(document).ready(function () {
  cargarHorarios();
});

let profesores = [];

function cargarHorarios() {
  $.ajax({
    url: '/api/profesores',
    type: 'GET',
    headers: {
      'Authorization': 'Bearer ' + localStorage.getItem('token')
    },
    success: function (profesoresData) {
      console.log('Profesores cargados:', profesoresData);
      profesores = profesoresData.data || [];
      cargarDatosHorarios();
    },
    error: function (xhr) {
      console.error('Error al cargar profesores:', xhr);
      cargarDatosHorarios();
    }
  });
}

function cargarDatosHorarios() {
  $.ajax({
    url: '/api/horarios',
    type: 'GET',
    headers: {
      'Authorization': 'Bearer ' + localStorage.getItem('token')
    },
    success: function (data) {
      if ($.fn.DataTable.isDataTable('#horarios-table')) {
        $('#horarios-table').DataTable().clear().rows.add(data.data).draw();
      } else {
        $('#tabla-horarios').html(`
          <table id="horarios-table" class="display" style="width:100%">
            <thead>
              <tr>
                <th>ID</th>
                <th>Profesor</th>
                <th>Día</th>
                <th>Hora Inicio</th>
                <th>Hora Fin</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        `);
        $('#horarios-table').DataTable({
          data: data.data,
          columns: [
            { data: 'id', defaultContent: '-' },
            { 
              data: 'profesor_id', 
              render: function(data, type, row) {
                const profesor = profesores.find(p => p.id == data);
                if (profesor) {
                  return `${profesor.nombre} ${profesor.apellido}`;
                } else {
                  setTimeout(() => {
                    $.ajax({
                      url: '/api/profesores',
                      type: 'GET',
                      headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('token')
                      },
                      success: function (profesoresData) {
                        profesores = profesoresData.data || [];
                        $('#horarios-table').DataTable().ajax.reload();
                      }
                    });
                  }, 100);
                  return `ID: ${data}`;
                }
              },
              defaultContent: '-' 
            },
            { data: 'dia', defaultContent: '-' },
            { data: 'hora_inicio', defaultContent: '-' },
            { data: 'hora_fin', defaultContent: '-' },
            { data: null, render: function(data, type, row) {
                return `<button class='btn btn-sm btn-primary' onclick='editarHorario(${row.id})'>Editar</button> <button class='btn btn-sm btn-danger' onclick='eliminarHorario(${row.id})'>Eliminar</button>`;
              }
            }
          ]
        });
      }
    },
    error: function (xhr, status, error) {
      console.error('Error al cargar horarios:', xhr);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'No se pudieron cargar los horarios',
        confirmButtonColor: '#0d6efd'
      });
    }
  });
}


function mostrarModalHorario(horario) {
  console.log('Datos del horario:', horario);
  
  const horarioData = horario && horario.data ? horario.data : horario;
  const isEdit = !!horarioData;
  
  let profesoresOptions = '<option value="">Seleccione un profesor</option>';
  profesores.forEach(profesor => {
    const selected = horarioData && horarioData.profesor_id == profesor.id ? 'selected' : '';
    profesoresOptions += `<option value="${profesor.id}" ${selected}>${profesor.nombre} ${profesor.apellido}</option>`;
  });
  
  const dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
  let diasOptions = '<option value="">Seleccione un día</option>';
  dias.forEach(dia => {
    const selected = horarioData && horarioData.dia === dia ? 'selected' : '';
    diasOptions += `<option value="${dia}" ${selected}>${dia}</option>`;
  });
  
  const customStyles = `
    <style>
      .custom-select {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #d9d9d9;
        border-radius: 0.375rem;
        font-size: 1rem;
        margin-bottom: 1rem;
      }
      .form-group {
        margin-bottom: 1.5rem;
        text-align: left;
      }
      .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        text-align: left;
      }
      .custom-input {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #d9d9d9;
        border-radius: 0.375rem;
        font-size: 1rem;
      }
    </style>
  `;
  
  Swal.fire({
    title: isEdit ? 'Editar Horario' : 'Nuevo Horario',
    html: `
      ${customStyles}
      <div class="form-group">
        <label for="swal-profesor_id" class="form-label">Profesor</label>
        <select id="swal-profesor_id" class="custom-select">
          ${profesoresOptions}
        </select>
      </div>
      <div class="form-group">
        <label for="swal-dia" class="form-label">Día</label>
        <select id="swal-dia" class="custom-select">
          ${diasOptions}
        </select>
      </div>
      <div class="form-group">
        <label for="swal-hora_inicio" class="form-label">Hora Inicio</label>
        <input id="swal-hora_inicio" class="custom-input" placeholder="HH:MM" value="${horarioData ? horarioData.hora_inicio.substring(0, 5) : ''}">
      </div>
      <div class="form-group">
        <label for="swal-hora_fin" class="form-label">Hora Fin</label>
        <input id="swal-hora_fin" class="custom-input" placeholder="HH:MM" value="${horarioData ? horarioData.hora_fin.substring(0, 5) : ''}">
      </div>
    `,
    focusConfirm: false,
    showCancelButton: true,
    preConfirm: () => {
      const profesor_id = $('#swal-profesor_id').val();
      const dia = $('#swal-dia').val();
      const hora_inicio = $('#swal-hora_inicio').val().trim();
      const hora_fin = $('#swal-hora_fin').val().trim();
      
      if (!profesor_id) {
        Swal.showValidationMessage('Debe seleccionar un profesor');
        return false;
      }
      
      if (!dia) {
        Swal.showValidationMessage('Debe seleccionar un día');
        return false;
      }
      
      if (!hora_inicio || !hora_fin) {
        Swal.showValidationMessage('Las horas de inicio y fin son obligatorias');
        return false;
      }
      
      if (!/^([01]\d|2[0-3]):[0-5]\d$/.test(hora_inicio) || !/^([01]\d|2[0-3]):[0-5]\d$/.test(hora_fin)) {
        Swal.showValidationMessage('Hora inicio y fin deben estar en formato HH:MM (24h)');
        return false;
      }
      
      return { profesor_id: parseInt(profesor_id), dia, hora_inicio, hora_fin };
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
        actualizarHorario(horarioData.id, result.value);
      } else {
        crearHorario(result.value);
      }
    }
  });
}

function crearHorario(data) {
  console.log('Creando horario con datos:', data);
  $.ajax({
    url: '/api/horarios',
    type: 'POST',
    headers: {
      'Authorization': 'Bearer ' + localStorage.getItem('token'),
      'Content-Type': 'application/json'
    },
    data: JSON.stringify(data),
    success: function (response) {
      console.log('Respuesta exitosa:', response);
      Swal.fire({
        icon: 'success',
        title: 'Éxito',
        text: 'Horario creado correctamente',
        confirmButtonColor: '#0d6efd'
      });
      cargarHorarios();
    },
    error: function (xhr, status, error) {
      console.error('Error al crear horario:', xhr.responseText);
      
      let errorMessage = 'No se pudo crear el horario';
      
      try {
        const response = JSON.parse(xhr.responseText);
        if (response.message) {
          errorMessage = response.message;
        } else if (response.error) {
          errorMessage = response.error;
        }
        
        console.log('Mensaje de error detectado:', errorMessage);
      } catch (e) {
        console.error('Error al parsear la respuesta:', e);
      }
      
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: errorMessage,
        confirmButtonColor: '#0d6efd'
      });
    }
  });
}

function editarHorario(id) {
  console.log('Iniciando edición del horario con ID:', id);
  $.ajax({
    url: `/api/horarios/${id}`,
    type: 'GET',
    headers: {
      'Authorization': 'Bearer ' + localStorage.getItem('token')
    },
    success: function (response) {
      console.log('Respuesta completa del servidor:', response);
      let horario;
      if (response && response.data) {
        console.log('Datos dentro de response.data');
        horario = response;
      } else if (response && typeof response === 'object') {
        console.log('Datos directamente en response');
        horario = { data: response };
      } else {
        console.log('Estructura de datos desconocida');
        horario = { data: {} };
      }
      
      mostrarModalHorario(horario);
    },
    error: function (xhr, status, error) {
      Swal.fire('Error', 'No se pudo cargar el horario', 'error');
    }
  });
}

function actualizarHorario(id, data) {
  console.log('Actualizando horario ID:', id, 'con datos:', data);
  $.ajax({
    url: `/api/horarios/${id}`,
    type: 'PUT',
    headers: {
      'Authorization': 'Bearer ' + localStorage.getItem('token'),
      'Content-Type': 'application/json'
    },
    data: JSON.stringify(data),
    success: function (response) {
      console.log('Respuesta exitosa:', response);
      Swal.fire({
        icon: 'success',
        title: 'Éxito',
        text: 'Horario actualizado correctamente',
        confirmButtonColor: '#0d6efd'
      });
      cargarHorarios();
    },
    error: function (xhr, status, error) {
      console.error('Error al actualizar horario:', xhr.responseText);
      
      let errorMessage = 'No se pudo actualizar el horario';
      
      try {
        const response = JSON.parse(xhr.responseText);
        
        if (response.message) {
          errorMessage = response.message;
        } else if (response.error) {
          errorMessage = response.error;
        }
        
        console.log('Mensaje de error detectado:', errorMessage);
      } catch (e) {
        console.error('Error al parsear la respuesta:', e);
      }
      
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: errorMessage,
        confirmButtonColor: '#0d6efd'
      });
    }
  });
}

function eliminarHorario(id) {
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
        url: `/api/horarios/${id}`,
        type: 'DELETE',
        headers: {
          'Authorization': 'Bearer ' + localStorage.getItem('token')
        },
        success: function () {
          Swal.fire('Eliminado', 'El horario ha sido eliminado.', 'success');
          cargarHorarios();
        },
        error: function () {
          Swal.fire('Error', 'No se pudo eliminar el horario', 'error');
        }
      });
    }
  });
}
