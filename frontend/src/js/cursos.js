
$(document).ready(function () {
  cargarCursos();
});
let materias = [];

function cargarCursos() {
  $.ajax({
    url: '/api/materias',
    type: 'GET',
    headers: {
      'Authorization': 'Bearer ' + localStorage.getItem('token')
    },
    success: function (materiasData) {
      console.log('Materias cargadas:', materiasData);
      materias = materiasData.data || [];
      cargarDatosCursos();
    },
    error: function (xhr) {
      console.error('Error al cargar materias:', xhr);
      cargarDatosCursos();
    }
  });
}

function cargarDatosCursos() {
  $.ajax({
    url: '/api/cursos',
    type: 'GET',
    headers: {
      'Authorization': 'Bearer ' + localStorage.getItem('token')
    },
    success: function (data) {
      if ($.fn.DataTable.isDataTable('#cursos-table')) {
        $('#cursos-table').DataTable().clear().rows.add(data.data).draw();
      } else {
        $('#tabla-cursos').html(`
          <table id="cursos-table" class="display" style="width:100%">
            <thead>
              <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Código</th>
                <th>Materia</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        `);
        $('#cursos-table').DataTable({
          data: data.data,
          columns: [
            { data: 'id', defaultContent: '-' },
            { data: 'nombre', defaultContent: '-' },
            { data: 'codigo', defaultContent: '-' },
            { 
              data: 'materia_id', 
              render: function(data, type, row) {
                const materia = materias.find(m => m.id == data);
                if (materia) {
                  return materia.nombre;
                } else {
                  setTimeout(() => {
                    $.ajax({
                      url: '/api/materias',
                      type: 'GET',
                      headers: {
                        'Authorization': 'Bearer ' + localStorage.getItem('token')
                      },
                      success: function (materiasData) {
                        materias = materiasData.data || [];
                        $('#cursos-table').DataTable().ajax.reload();
                      }
                    });
                  }, 100);
                  return `ID: ${data}`;
                }
              },
              defaultContent: '-' 
            },
            { data: null, render: function(data, type, row) {
                return `<button class='btn btn-sm btn-primary' onclick='editarCurso(${row.id})'>Editar</button> <button class='btn btn-sm btn-danger' onclick='eliminarCurso(${row.id})'>Eliminar</button>`;
              }
            }
          ]
        });
      }
    },
    error: function () {
      Swal.fire('Error', 'No se pudieron cargar los cursos', 'error');
    }
  });
}

function mostrarModalCurso(curso) {
  const cursoData = curso && curso.data ? curso.data : curso;
  const isEdit = !!cursoData;
  
  let materiasOptions = '<option value="">Seleccione una materia</option>';
  materias.forEach(materia => {
    const selected = cursoData && cursoData.materia_id == materia.id ? 'selected' : '';
    materiasOptions += `<option value="${materia.id}" ${selected}>${materia.nombre}</option>`;
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
    title: isEdit ? 'Editar Curso' : 'Nuevo Curso',
    html: `
      ${customStyles}
      <div class="form-group">
        <label for="swal-nombre" class="form-label">Nombre</label>
        <input id="swal-nombre" class="custom-input" placeholder="Nombre del curso" value="${cursoData ? cursoData.nombre : ''}">
      </div>
      <div class="form-group">
        <label for="swal-codigo" class="form-label">Código</label>
        <input id="swal-codigo" class="custom-input" placeholder="Código del curso" value="${cursoData ? cursoData.codigo : ''}">
      </div>
      <div class="form-group">
        <label for="swal-materia_id" class="form-label">Materia</label>
        <select id="swal-materia_id" class="custom-select">
          ${materiasOptions}
        </select>
      </div>
    `,
    focusConfirm: false,
    showCancelButton: true,
    preConfirm: () => {
      const nombre = $('#swal-nombre').val().trim();
      const codigo = $('#swal-codigo').val().trim();
      const materia_id = $('#swal-materia_id').val();
      
      if (!nombre) {
        Swal.showValidationMessage('El nombre del curso es obligatorio');
        return false;
      }
      
      if (!codigo) {
        Swal.showValidationMessage('El código del curso es obligatorio');
        return false;
      }
      
      if (!materia_id) {
        Swal.showValidationMessage('Debe seleccionar una materia');
        return false;
      }
      
      return { nombre, codigo, materia_id: parseInt(materia_id) };
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
        actualizarCurso(cursoData.id, result.value);
      } else {
        crearCurso(result.value);
      }
    }
  });
}

function crearCurso(data) {
  $.ajax({
    url: '/api/cursos',
    type: 'POST',
    headers: {
      'Authorization': 'Bearer ' + localStorage.getItem('token'),
      'Content-Type': 'application/json'
    },
    data: JSON.stringify(data),
    success: function () {
      Swal.fire('Éxito', 'Curso creado correctamente', 'success');
      cargarCursos();
    },
    error: function (xhr) {
      Swal.fire('Error', xhr.responseJSON?.message || 'No se pudo crear el curso', 'error');
    }
  });
}

function editarCurso(id) {
  $.ajax({
    url: `/api/cursos/${id}`,
    type: 'GET',
    headers: {
      'Authorization': 'Bearer ' + localStorage.getItem('token')
    },
    success: function (curso) {
      mostrarModalCurso(curso);
    },
    error: function () {
      Swal.fire('Error', 'No se pudo cargar el curso', 'error');
    }
  });
}

function actualizarCurso(id, data) {
  $.ajax({
    url: `/api/cursos/${id}`,
    type: 'PUT',
    headers: {
      'Authorization': 'Bearer ' + localStorage.getItem('token'),
      'Content-Type': 'application/json'
    },
    data: JSON.stringify(data),
    success: function () {
      Swal.fire('Éxito', 'Curso actualizado correctamente', 'success');
      cargarCursos();
    },
    error: function (xhr) {
      Swal.fire('Error', xhr.responseJSON?.message || 'No se pudo actualizar el curso', 'error');
    }
  });
}

function eliminarCurso(id) {
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
        url: `/api/cursos/${id}`,
        type: 'DELETE',
        headers: {
          'Authorization': 'Bearer ' + localStorage.getItem('token')
        },
        success: function () {
          Swal.fire('Eliminado', 'El curso ha sido eliminado.', 'success');
          cargarCursos();
        },
        error: function () {
          Swal.fire('Error', 'No se pudo eliminar el curso', 'error');
        }
      });
    }
  });
}
