function abrirModalCriar() {
    document.getElementById("ModalCriar").style.display = "block";
}

function fecharModalCriar() {
    document.getElementById("ModalCriar").style.display = "none";
}

function abrirModalAtualizarVoluntario(id) {
    document.getElementById("iframeAtualizar").src =
        "../../backend/core/voluntarios/atualizarVoluntarios.php?id=" + id;

    document.getElementById("modalAtualizar").style.display = "block";
}

function abrirModalAtualizarSaida(id) {
    document.getElementById("iframeAtualizar").src =
        "../../backend/core/saidas/atualizarSaidas.php?id=" + id;

    document.getElementById("modalAtualizar").style.display = "block";
}

function fecharModalAtualizar() {
    document.getElementById("modalAtualizar").style.display = "none";
    document.getElementById("iframeAtualizar").src = "";

    setTimeout(function() {
        location.reload();
    }, 100);
}

function abrirModalDeletar(id) {
    document.getElementById("delete_id").value = id;
    document.getElementById("modalDeletar").style.display = "block";
}

function fecharModalDeletar() {
    document.getElementById("modalDeletar").style.display = "none";
}

function abrirModalVoluntarios(saidaId) {
    var modal = document.getElementById("modalVoluntarios");
    var container = document.getElementById("voluntariosLista");
    container.innerHTML = "<p>Carregando voluntários...</p>";
    modal.style.display = "block";

    fetch("../../backend/core/voluntarios/listarVoluntarios.php?saida_id=" + encodeURIComponent(saidaId) + "&format=json")
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            if (!data || !Array.isArray(data.voluntarios)) {
                container.innerHTML = "<p>Não foi possível carregar os voluntários.</p>";
                return;
            }

            if (data.voluntarios.length === 0) {
                container.innerHTML = "<p>Nenhum voluntário cadastrado para esta saída.</p>";
                return;
            }

            var html = "<table><tr><th>Nome</th><th>Telefone</th><th>Inscrito em</th></tr>";
            data.voluntarios.forEach(function(voluntario) {
                html += "<tr>" +
                    "<td>" + voluntario.nome + "</td>" +
                    "<td>" + voluntario.telefone + "</td>" +
                    "<td>" + voluntario.inscrito_em + "</td>" +
                "</tr>";
            });
            html += "</table>";
            container.innerHTML = html;
        })
        .catch(function() {
            container.innerHTML = "<p>Erro ao carregar voluntários.</p>";
        });
}

function fecharModalVoluntarios() {
    document.getElementById("modalVoluntarios").style.display = "none";
}

window.onclick = function(event) {
    var mCriar = document.getElementById("ModalCriar");
    var mAtualizar = document.getElementById("modalAtualizar");
    var mDeletar = document.getElementById("modalDeletar");
    var mVoluntarios = document.getElementById("modalVoluntarios");

    if (event.target == mCriar)
        mCriar.style.display = "none";

    if (event.target == mAtualizar)
        fecharModalAtualizar();

    if (event.target == mDeletar)
        mDeletar.style.display = "none";

    if (event.target == mVoluntarios)
        fecharModalVoluntarios();
}