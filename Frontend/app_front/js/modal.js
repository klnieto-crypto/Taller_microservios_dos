/* =========================
   SISTEMA MODAL GENÉRICO
========================= */

function abrirModal(idModal) {
    const modal = document.getElementById(idModal);
    if (modal) modal.style.display = 'flex';
}

function cerrarModal(idModal) {
    const modal = document.getElementById(idModal);
    if (modal) modal.style.display = 'none';
}

/* =========================
   CERRAR AL HACER CLICK FUERA
========================= */

window.addEventListener('click', (e) => {
    const modales = document.querySelectorAll('.modal');
    modales.forEach(modal => {
        if (e.target === modal) modal.style.display = 'none';
    });
});

/* =========================
   EFECTO CONSOLE NEON
========================= */

console.log(
    '%c⚡ Glow Neon UI Inicializada ⚡',
    `
    color: #00ffff;
    font-size: 18px;
    font-weight: bold;
    text-shadow: 0 0 10px #00ffff;
    `
);

/* =========================
   EDICIÓN — SPRINT / HISTORIA
========================= */

const API_HIST_M   = 'http://127.0.0.1:8000/historias';
const API_SPRINT_M = 'http://127.0.0.1:8000/sprints';

let _editId   = null;
let _editTipo = null;

// Inyectar HTML del modal de edición
document.getElementById('modalContainer').innerHTML = `
<div id="modalEditar" class="modal">
    <div class="modal-content">

        <span class="close-modal" onclick="cerrarModal('modalEditar')">✕</span>
        <h2 id="modalTitulo"></h2>

        <!-- SPRINT -->
        <div id="modalSprintForm" style="display:none">
            <div class="modal-field">
                <label>Nombre</label>
                <input type="text" id="editNombreSprint" placeholder="Nombre del sprint">
            </div>
            <div class="modal-row">
                <div class="modal-field">
                    <label>Fecha inicio</label>
                    <input type="date" id="editFechaInicio">
                </div>
                <div class="modal-field">
                    <label>Fecha fin</label>
                    <input type="date" id="editFechaFin">
                </div>
            </div>
            <button class="btn-guardar" onclick="guardarEdicionSprint()">Guardar cambios</button>
        </div>

        <!-- HISTORIA -->
        <div id="modalHistoriaForm" style="display:none">
            <div class="modal-field">
                <label>Título</label>
                <input type="text" id="editTitulo" placeholder="Título">
            </div>
            <div class="modal-field">
                <label>Descripción</label>
                <textarea id="editDescripcion" placeholder="Descripción"></textarea>
            </div>
            <div class="modal-row">
                <div class="modal-field">
                    <label>Responsable</label>
                    <input type="text" id="editResponsable">
                </div>
                <div class="modal-field">
                    <label>Puntos</label>
                    <input type="number" id="editPuntos" min="1" max="100">
                </div>
            </div>
            <div class="modal-row">
                <div class="modal-field">
                    <label>Estado</label>
                    <select id="editEstado">
                        <option value="nueva">Nueva</option>
                        <option value="activa">Activa</option>
                        <option value="finalizada">Finalizada</option>
                        <option value="impedimento">Impedimento</option>
                    </select>
                </div>
                <div class="modal-field">
                    <label>Sprint</label>
                    <select id="editSprintId"></select>
                </div>
            </div>
            <div class="modal-row">
                <div class="modal-field">
                    <label>Fecha creación</label>
                    <input type="date" id="editFechaCreacion">
                </div>
                <div class="modal-field">
                    <label>Fecha finalización <span class="opcional">(opcional)</span></label>
                    <input type="date" id="editFechaFinalizacion">
                </div>
            </div>
            <button class="btn-guardar" onclick="guardarEdicionHistoria()">Guardar cambios</button>
        </div>

    </div>
</div>`;

/* ---------- ABRIR EDITAR SPRINT ---------- */
async function abrirEditarSprint(id) {
    try {
        const res  = await fetch(`${API_SPRINT_M}/${id}`);
        const data = await res.json();

        _editId = id;

        document.getElementById('modalTitulo').textContent      = '✏️ Editar Sprint';
        document.getElementById('editNombreSprint').value       = data.nombre;
        document.getElementById('editFechaInicio').value        = data.fecha_inicio;
        document.getElementById('editFechaFin').value           = data.fecha_fin;

        document.getElementById('modalSprintForm').style.display   = 'block';
        document.getElementById('modalHistoriaForm').style.display = 'none';

        abrirModal('modalEditar');

    } catch (e) {
        alert('Error cargando sprint');
    }
}

/* ---------- ABRIR EDITAR HISTORIA ---------- */
async function abrirEditarHistoria(id) {
    try {
        const [resH, resS] = await Promise.all([
            fetch(`${API_HIST_M}/${id}`),
            fetch(API_SPRINT_M)
        ]);

        const historia = await resH.json();
        const sprints  = await resS.json();

        _editId = id;

        // Select de sprints
        const sel = document.getElementById('editSprintId');
        sel.innerHTML = '<option value="">Sin sprint</option>';
        sprints.forEach(s => {
            sel.innerHTML += `<option value="${s.id}" ${s.id == historia.sprint_id ? 'selected' : ''}>${s.nombre}</option>`;
        });

        document.getElementById('modalTitulo').textContent         = '✏️ Editar Historia';
        document.getElementById('editTitulo').value                = historia.titulo;
        document.getElementById('editDescripcion').value           = historia.descripcion;
        document.getElementById('editResponsable').value           = historia.responsable || '';
        document.getElementById('editPuntos').value                = historia.puntos;
        document.getElementById('editEstado').value                = historia.estado;
        document.getElementById('editFechaCreacion').value         = historia.fecha_creacion;
        document.getElementById('editFechaFinalizacion').value     = historia.fecha_finalizacion || '';

        document.getElementById('modalHistoriaForm').style.display = 'block';
        document.getElementById('modalSprintForm').style.display   = 'none';

        abrirModal('modalEditar');

    } catch (e) {
        alert('Error cargando historia');
    }
}

/* ---------- GUARDAR SPRINT ---------- */
async function guardarEdicionSprint() {
    const data = {
        nombre:       document.getElementById('editNombreSprint').value,
        fecha_inicio: document.getElementById('editFechaInicio').value,
        fecha_fin:    document.getElementById('editFechaFin').value,
    };

    try {
        const res = await fetch(`${API_SPRINT_M}/${_editId}`, {
            method:  'PUT',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify(data),
        });

        if (!res.ok) { alert('Error actualizando sprint'); return; }

        cerrarModal('modalEditar');
        obtenerSprints();

    } catch (e) {
        alert('Error actualizando sprint');
    }
}

/* ---------- GUARDAR HISTORIA ---------- */
async function guardarEdicionHistoria() {
    const data = {
        titulo:             document.getElementById('editTitulo').value,
        descripcion:        document.getElementById('editDescripcion').value,
        responsable:        document.getElementById('editResponsable').value,
        puntos:             document.getElementById('editPuntos').value,
        estado:             document.getElementById('editEstado').value,
        sprint_id:          document.getElementById('editSprintId').value || null,
        fecha_creacion:     document.getElementById('editFechaCreacion').value,
        fecha_finalizacion: document.getElementById('editFechaFinalizacion').value || null,
    };

    try {
        const res = await fetch(`${API_HIST_M}/${_editId}`, {
            method:  'PUT',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify(data),
        });

        if (!res.ok) { alert('Error actualizando historia'); return; }

        cerrarModal('modalEditar');
        obtenerHistorias();
        obtenerSprints();

    } catch (e) {
        alert('Error actualizando historia');
    }
}