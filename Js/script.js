// public/js/app.js
const API = "../api/pessoas.php";

const form = document.getElementById("pessoaForm");
const lista = document.getElementById("listaPessoas");

const inputs = {
    id: document.getElementById("pessoaId"),
    nome: document.getElementById("nome"),
    cpf: document.getElementById("cpf"),
    idade: document.getElementById("idade")
};

// Segurança para evitar XSS
function escapeHtml(text) {
    return text.replace(/[&<>"']/g, m => ({
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        '"': "&quot;",
        "'": "&#039;"
    }[m]));
}

// ---------------------------
// LISTAR PESSOAS
// ---------------------------
async function fetchPessoas() {
    const res = await fetch(API);
    const data = await res.json();
    renderLista(data || []);
}

function renderLista(items) {
    lista.innerHTML = "";

    if (!items.length) {
        lista.innerHTML = "<li>Nenhuma pessoa cadastrada.</li>";
        return;
    }

    items.forEach(p => {
        const li = document.createElement("li");

        li.innerHTML = `
            <div class="item-info">
                <strong>${escapeHtml(p.nome)}</strong>
                <small>CPF: ${escapeHtml(p.cpf)} ${p.idade ? "- " + p.idade + " anos" : ""}</small>
            </div>

            <div class="item-actions">
                <button data-id="${p.id}" class="edit-button">Editar</button>
                <button data-id="${p.id}" class="delete-button">Excluir</button>
            </div>
        `;

        lista.appendChild(li);
    });
}

// ---------------------------
// CRIAR / ATUALIZAR PESSOA
// ---------------------------
form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const payload = {
        nome: inputs.nome.value.trim(),
        cpf: inputs.cpf.value.trim(),
        idade: inputs.idade.value ? Number(inputs.idade.value) : null
    };

    try {
        // UPDATE
        if (inputs.id.value) {
            const res = await fetch(API + "?id=" + inputs.id.value, {
                method: "PUT",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(payload)
            });

            await res.json();
        }
        // CREATE
        else {
            const res = await fetch(API, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify(payload)
            });

            await res.json();
        }

        resetForm();
        fetchPessoas();

    } catch (err) {
        console.error("Erro:", err);
    }
});

// Resetar formulário
function resetForm() {
    inputs.id.value = "";
    inputs.nome.value = "";
    inputs.cpf.value = "";
    inputs.idade.value = "";
}

// ---------------------------
// EDITAR / DELETAR
// ---------------------------
lista.addEventListener("click", async (e) => {
    const id = e.target.dataset.id;
    if (!id) return;

    // DELETE
    if (e.target.classList.contains("delete-button")) {
        if (!confirm("Confirma a exclusão desta pessoa?")) return;

        await fetch(API + "?id=" + id, { method: "DELETE" });
        fetchPessoas();
    }

    // EDITAR
    if (e.target.classList.contains("edit-button")) {

        const res = await fetch(API + "?id=" + id);
        const obj = await res.json();

        inputs.id.value = obj.id;
        inputs.nome.value = obj.nome;
        inputs.cpf.value = obj.cpf;
        inputs.idade.value = obj.idade || "";
    }
});

// Inicializa
fetchPessoas();
