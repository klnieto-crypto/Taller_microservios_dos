const API_HIST     = 'http://127.0.0.1:8000/historias';
const API_SPRINTS2 = 'http://127.0.0.1:8000/sprints';

const formHistoria  = document.getElementById('formHistoria');
const listaHistorias = document.getElementById('listaHistorias');

/* =========================
   CARGAR SPRINTS EN SELECT
========================= */

async function cargarSprintsEnSelect() {

    try {

        const res  = await fetch(API_SPRINTS2);
        const data = await res.json();

        const select = document.getElementById('sprintId');
        select.innerHTML = '<option value="">Seleccionar sprint...</option>';

        data.forEach(sprint => {
            select.innerHTML += `<option value="${sprint.id}">${sprint.nombre}</option>`;
        });

    } catch (error) {
        console.error('Error cargando sprints en select:', error);
    }
}

/* =========================
   OBTENER HISTORIAS
========================= */

async function obtenerHistorias() {

    try {

        const response = await fetch(API_HIST);
        const data     = await response.json();

        listaHistorias.innerHTML = '';

        if (!data || data.length === 0) {
            listaHistorias.innerHTML = `
                <div class="item">
                    <h3>No hay historias registradas</h3>
                </div>`;
            return;
        }

        data.forEach(historia => {

            listaHistorias.innerHTML += `
                <div class="item">
                    <div class="sprint-header">
                        <h3>${historia.titulo}</h3>
                        ${badgeEstado(historia.estado)}
                    </div>
                    <p>${historia.descripcion}</p>
                    <p><strong>Responsable:</strong> ${historia.responsable}</p>
                    <p><strong>Puntos:</strong> ${historia.puntos}</p>
                    <p><strong>Sprint ID:</strong> ${historia.sprint_id}</p>
                    <button class="btn-editar" onclick="abrirEditarHistoria(${historia.id})">
                        Editar
                    </button>
                    <button class="btn-eliminar" onclick="eliminarHistoria(${historia.id})">
                        Eliminar
                    </button>
                </div>`;
        });

    } catch (error) {
        console.error(error);
        listaHistorias.innerHTML = `
            <div class="item">
                <h3>Error cargando historias</h3>
            </div>`;
    }
}

/* =========================
   CREAR HISTORIA
========================= */

formHistoria.addEventListener('submit', async (e) => {

    e.preventDefault();

    const historia = {
        titulo:       document.getElementById('titulo').value,
        descripcion:  document.getElementById('descripcion').value,
        responsable:  document.getElementById('responsable').value,
        estado:       document.getElementById('estado').value.toLowerCase(), 
        puntos:       document.getElementById('puntos').value,
        sprint_id:    document.getElementById('sprintId').value || null,  
        fecha_creacion:     new Date().toISOString().split('T')[0],
        fecha_finalizacion: document.getElementById('fechaFinalizacion').value || null,
    };

    try {

        const res  = await fetch(API_HIST, {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify(historia),
        });

        const data = await res.json();

        if (!res.ok) {
            alert(data.mensaje || 'Error creando historia');
            return;
        }

        formHistoria.reset();
        cargarSprintsEnSelect();
        obtenerHistorias();

    } catch (error) {
        console.error(error);
        alert('Error creando historia');
    }
});

/* =========================
   ELIMINAR HISTORIA
========================= */

async function eliminarHistoria(id) {

    const confirmar = confirm('¿Deseas eliminar esta historia?');
    if (!confirmar) return;

    try {

        await fetch(`${API_HIST}/${id}`, { method: 'DELETE' });
        obtenerHistorias();

    } catch (error) {
        console.error(error);
        alert('Error eliminando historia');
    }
}

/* =========================
   BADGE ESTADO
========================= */

function badgeEstado(estado) {
    const clases = {
        nueva:       'badge-nueva',
        activa:      'badge-activa',
        finalizada:  'badge-finalizada',
        impedimento: 'badge-impedimento',
    };
    return `<span class="badge ${clases[estado] || ''}">${estado}</span>`;
}

/* =========================
   INICIAR
========================= */

cargarSprintsEnSelect();
obtenerHistorias();