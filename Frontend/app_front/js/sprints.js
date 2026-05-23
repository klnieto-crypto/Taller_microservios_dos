const API_SPRINTS   = 'http://127.0.0.1:8000/sprints';
const API_HISTORIAS = 'http://127.0.0.1:8000/sprints';

const formSprint  = document.getElementById('formSprint');
const listaSprints = document.getElementById('listaSprints');

/* =========================
   BADGES DE ESTADO
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
   OBTENER SPRINTS + HISTORIAS
========================= */

async function obtenerSprints() {

    try {

        const response = await fetch(API_SPRINTS);
        const data     = await response.json();

        listaSprints.innerHTML = '';

        if (!data || data.length === 0) {
            listaSprints.innerHTML = `
                <div class="item">
                    <h3>No hay sprints registrados</h3>
                </div>`;
            return;
        }

        for (const sprint of data) {

            // Cargar historias de este sprint
            let historias = [];
           try {
                const resH = await fetch(`${API_HISTORIAS}/${sprint.id}/historias`);
                const dataH = await resH.json();
                console.log('Historias recibidas:', dataH); // para verificar
                historias = Array.isArray(dataH) ? dataH : [];
            } catch (e) {
                console.error('Error cargando historias del sprint:', e);
            }

            const total      = historias.length;
            const finalizadas = historias.filter(h => h.estado === 'finalizada').length;
            const pct        = total > 0 ? Math.round((finalizadas / total) * 100) : 0;

            // Filas del historial
            const filasHistorial = total === 0
                ? `<tr><td colspan="4" style="text-align:center;color:#475569;padding:12px">Sin historias aún</td></tr>`
                : historias.map(h => `
                    <tr>
                        <td>${h.titulo}</td>
                        <td>${h.responsable}</td>
                        <td>${badgeEstado(h.estado)}</td>
                        <td style="text-align:center">${h.puntos} pts</td>
                    </tr>`).join('');

            listaSprints.innerHTML += `
                <div class="item">

                    <div class="btn-group">
                        <button class="btn-editar" onclick="abrirEditarSprint(${sprint.id})">
                         Editar
                          </button>
                        <button class="btn-eliminar" onclick="eliminarSprint(${sprint.id})">
                         Eliminar
                        </button>
                    </div>

                    <div class="sprint-fechas">
                        <span>📅 Inicio: <strong>${sprint.fecha_inicio}</strong></span>
                        <span>🏁 Fin: <strong>${sprint.fecha_fin}</strong></span>
                    </div>

                    <div class="sprint-progreso">
                        <div class="progreso-bar">
                            <div class="progreso-fill" style="width:${pct}%"></div>
                        </div>
                        <span class="progreso-label">${finalizadas}/${total} finalizadas · ${pct}%</span>
                    </div>

                    <div class="historial">
                        <p class="historial-titulo">Historial de historias</p>
                        <table class="historial-tabla">
                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <th>Responsable</th>
                                    <th>Estado</th>
                                    <th>Puntos</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${filasHistorial}
                            </tbody>
                        </table>
                    </div>

                </div>`;
        }

    } catch (error) {
        console.error(error);
        listaSprints.innerHTML = `
            <div class="item">
                <h3>Error cargando sprints</h3>
            </div>`;
    }
}

/* =========================
   CREAR SPRINT
========================= */

formSprint.addEventListener('submit', async (e) => {

    e.preventDefault();

    const sprint = {
        nombre:       document.getElementById('nombreSprint').value,
        fecha_inicio: document.getElementById('fechaInicio').value,
        fecha_fin:    document.getElementById('fechaFin').value,
    };

    try {
        await fetch(API_SPRINTS, {
            method:  'POST',
            headers: { 'Content-Type': 'application/json' },
            body:    JSON.stringify(sprint),
        });

        formSprint.reset();
        obtenerSprints();

    } catch (error) {
        console.error(error);
        alert('Error creando sprint');
    }
});

/* =========================
   ELIMINAR SPRINT
========================= */

async function eliminarSprint(id) {

    const confirmar = confirm('¿Deseas eliminar este sprint y todas sus historias?');
    if (!confirmar) return;

    try {
        await fetch(`${API_SPRINTS}/${id}`, { method: 'DELETE' });
        obtenerSprints();
    } catch (error) {
        console.error(error);
        alert('Error eliminando sprint');
    }
}

/* =========================
   INICIAR
========================= */

obtenerSprints();