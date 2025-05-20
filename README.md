# Sistema de Gestión de Horarios Académicos

Este proyecto implementa una API RESTful para la gestión de horarios académicos, siguiendo los principios de Clean Architecture y SOLID. El sistema permite administrar profesores, materias, cursos y horarios, con un enfoque especial en la prevención de conflictos de horarios.

## Arquitectura

El proyecto está estructurado siguiendo los principios de Clean Architecture, separando claramente las capas:

### 1. Capa de Dominio (`src/Domain/`)

- **Entidades**: Objetos de negocio puros sin dependencias externas
  - `Profesor.php`
  - `Curso.php`
  - `Materia.php`
  - `Horario.php`

- **Repositorios (Interfaces)**: Contratos para acceso a datos
  - `ProfesorRepositoryInterface.php`
  - `CursoRepositoryInterface.php`
  - `MateriaRepositoryInterface.php`
  - `HorarioRepositoryInterface.php`

- **Servicios**: Lógica de negocio
  - `ProfesorService.php`
  - `CursoService.php`
  - `MateriaService.php`
  - `HorarioService.php`
  - `ConflictoService.php` - Servicio especializado para la detección y gestión de conflictos de horarios

### 2. Capa de Aplicación (`src/Application/`)

- **Controladores**: Punto de entrada para las peticiones HTTP
  - `ProfesorController.php`
  - `CursoController.php`
  - `MateriaController.php`
  - `HorarioController.php`
  - `AuthController.php`
  - `DisponibilidadController.php`
  - `ProfesorCursoController.php`

### 3. Capa de Infraestructura (`src/Infrastructure/`)

- **Persistencia**: Implementación de repositorios
  - `Eloquent/ProfesorRepository.php`
  - `Eloquent/CursoRepository.php`
  - `Eloquent/MateriaRepository.php`
  - `Eloquent/HorarioRepository.php`

- **API**: Configuración de rutas y middleware
  - `API/Routes/slim_routes.php`
  - `API/Middleware/AuthMiddleware.php`
  - `API/Middleware/ValidationMiddleware.php`

- **Swagger**: Documentación de la API
  ### Documentación Swagger

Para generar o actualizar el archivo `swagger.json`:

**Opción recomendada (script):**
```bash
docker-compose exec app sh ./swagger-generate.sh
```

**Si el script falla, puedes ejecutar el comando manualmente:**
```bash
docker-compose exec app ./vendor/bin/openapi --bootstrap vendor/autoload.php --output public/swagger.json src/Infrastructure/Swagger
```
  - `Swagger/AuthEndpoints.php`
  - `Swagger/CursoEndpoints.php`
  - `Swagger/MateriaEndpoints.php`
  - `Swagger/ProfesorEndpoints.php`
  - `Swagger/HorarioEndpoints.php`
  - `Swagger/DisponibilidadEndpoints.php`
  - `Swagger/AsignacionEndpoints.php`
  - `Swagger/ConflictoEndpoints.php`

## Inyección de Dependencias

El proyecto utiliza PHP-DI para la inyección de dependencias. La configuración se encuentra en `config/dependencies.php`:

```php
// Repositorios
MateriaRepository::class => DI\autowire(),
CursoRepository::class => DI\autowire(),
ProfesorRepository::class => DI\autowire(),
HorarioRepository::class => DI\autowire(),

// Servicios
MateriaService::class => DI\autowire()->constructorParameter('repo', DI\get(MateriaRepository::class)),
CursoService::class => DI\autowire()->constructorParameter('repo', DI\get(CursoRepository::class)),

// Servicio de conflictos
ConflictoService::class => DI\autowire()
    ->constructorParameter('horarioRepository', DI\get(HorarioRepository::class))
    ->constructorParameter('profesorRepository', DI\get(ProfesorRepository::class))
    ->constructorParameter('cursoRepository', DI\get(CursoRepository::class)),

// Servicio de profesores con todas sus dependencias
ProfesorService::class => DI\autowire()
    ->constructorParameter('profesorRepository', DI\get(ProfesorRepository::class))
    ->constructorParameter('cursoRepository', DI\get(CursoRepository::class))
    ->constructorParameter('conflictoService', DI\get(ConflictoService::class)),
```

## Endpoints de la API

Todos los endpoints están protegidos con autenticación JWT, excepto el endpoint de login.

### Autenticación

- **POST /api/login**: Autenticación y obtención de JWT
  - Request: `{ "username": "admin", "password": "password123" }`
  - Response: `{ "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...", "expires_at": "2023-12-31T23:59:59Z" }`

### Profesores

- **GET /api/profesores**: Listar todos los profesores
- **POST /api/profesores**: Crear un nuevo profesor
- **GET /api/profesores/{id}**: Obtener un profesor por ID
- **PUT /api/profesores/{id}**: Actualizar un profesor
- **DELETE /api/profesores/{id}**: Eliminar un profesor

### Cursos

- **GET /api/cursos**: Listar todos los cursos
- **POST /api/cursos**: Crear un nuevo curso
- **GET /api/cursos/{id}**: Obtener un curso por ID
- **PUT /api/cursos/{id}**: Actualizar un curso
- **DELETE /api/cursos/{id}**: Eliminar un curso

### Materias

- **GET /api/materias**: Listar todas las materias
- **POST /api/materias**: Crear una nueva materia
- **GET /api/materias/{id}**: Obtener una materia por ID
- **PUT /api/materias/{id}**: Actualizar una materia
- **DELETE /api/materias/{id}**: Eliminar una materia

### Horarios

- **GET /api/horarios**: Listar todos los horarios
- **POST /api/horarios**: Crear un nuevo horario
- **GET /api/horarios/{id}**: Obtener un horario por ID
- **PUT /api/horarios/{id}**: Actualizar un horario
- **DELETE /api/horarios/{id}**: Eliminar un horario

### Disponibilidad de Profesores

- **GET /api/profesores/{id}/disponibilidad**: Obtener la disponibilidad de un profesor
- **POST /api/profesores/{id}/disponibilidad**: Definir disponibilidad de un profesor
- **PUT /api/profesores/{id}/disponibilidad/{disponibilidad_id}**: Actualizar disponibilidad
- **DELETE /api/profesores/{id}/disponibilidad/{disponibilidad_id}**: Eliminar disponibilidad

### Asignación de Cursos

- **GET /api/profesores/{id}/cursos**: Obtener cursos asignados a un profesor
- **POST /api/profesores/{id}/cursos**: Asignar un curso a un profesor
- **DELETE /api/profesores/{id}/cursos/{curso_id}**: Eliminar asignación de curso
- **POST /api/verificar-conflicto**: Verificar si existe un conflicto de horario

### Gestión de Conflictos

- **GET /api/conflictos**: Obtener todos los conflictos de horarios existentes
- **GET /api/profesores/{id}/conflictos**: Obtener conflictos de un profesor
- **GET /api/cursos/{id}/conflictos**: Obtener conflictos de un curso
- **POST /api/resolver-conflicto/{conflicto_id}**: Resolver un conflicto específico

## Configuración y Ejecución

### Requisitos

- Docker y Docker Compose
- Git

### Instalación y Ejecución

Para ejecutar el proyecto completo, simplemente utiliza el siguiente comando en la raíz del proyecto:

```bash
docker-compose up
```

Este comando:
- Construirá las imágenes necesarias
- Iniciará los contenedores (PHP, MySQL, Nginx)
- Instalará las dependencias
- Ejecutará las migraciones de la base de datos
- Cargará los datos iniciales

Una vez que los contenedores estén en ejecución, podrás acceder a:

- **API Backend**: http://localhost:8080
- **Interfaz Frontend**: http://localhost:8081
- **Pagina Swagger**: http://localhost:8080/docs
- **JSON Swagger**: http://localhost:8080/swagger.json

## Características Principales

### 1. Prevención de Conflictos de Horarios

El sistema implementa una lógica robusta para prevenir conflictos de horarios:

- Cuando un profesor intenta asignar un curso, el sistema verifica si hay conflictos con otros cursos ya asignados.
- Si se detecta un conflicto, se devuelve un mensaje de error detallado con información sobre el conflicto.
- El sistema también proporciona endpoints para consultar y resolver conflictos existentes.

#### Ejemplos de respuestas de error:

**1. Al crear un horario (`POST /api/horarios`):**

```json
{
    "success": false,
    "message": "Conflicto de horario para el profesor en ese rango de horas."
}
```

**2. Cuando el profesor no está disponible:**

```json
{
    "success": false,
    "message": "El profesor no está disponible en ese horario."
}
```

**3. Al verificar conflictos explícitamente (`POST /api/verificar-conflicto`):**

```json
{
    "conflicto": true,
    "mensaje": "Existe un conflicto de horario",
    "detalles": {
        "profesor_id": 1,
        "curso_id": 1,
        "dia": "Lunes",
        "hora_inicio": "08:00:00",
        "hora_fin": "10:00:00",
        "conflicto_con": "Álgebra"
    }
}
```

### 2. Autenticación JWT

Todos los endpoints están protegidos con autenticación JWT, excepto el endpoint de login. Para acceder a los endpoints protegidos, debes incluir el token JWT en el encabezado de la petición:

```
Authorization: Bearer <token>
```

### 3. Validación de Datos

El sistema implementa validación de datos en los controladores utilizando el middleware `ValidationMiddleware` con la biblioteca Respect\Validation.

## Contribución

Para contribuir al proyecto:

1. Haz un fork del repositorio
2. Crea una rama para tu funcionalidad
3. Realiza tus cambios y haz commit
4. Haz push a la rama
5. Crea un Pull Request

## Acceso al Sistema

### API Backend

- **Username**: admin
- **Password**: admin123

Utiliza estas credenciales para obtener un token JWT en `/api/login`.

### Interfaz Frontend

Accede a la interfaz de usuario a través de la siguiente URL:

```
http://localhost:8081
```

Utiliza las mismas credenciales (admin/admin123) para iniciar sesión en la interfaz web. Desde allí podrás gestionar profesores, materias, cursos y horarios a través de una interfaz amigable.
