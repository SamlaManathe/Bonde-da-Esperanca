function abrirModalCriar() {
    document.getElementById("ModalCriar").style.display = "block";
}

function fecharModalCriar() {
    document.getElementById("ModalCriar").style.display = "none";
}

function abrirModalAtualizar(id) {
    var url = "../../backend/core/voluntarios/atualizarVoluntarios.php?id=" + id;
    document.getElementById("iframeAtualizar").src = url;
    document.getElementById("modalAtualizar").style.display = "block";
}

function fecharModalAtualizar() {
    document.getElementById("modalAtualizar").style.display = "none";
    document.getElementById("iframeAtualizar").src = "";
    window.location.reload();
}

function abrirModalDeletar(id) {
    document.getElementById("delete_id").value = id;
    document.getElementById("modalDeletar").style.display = "block";
}

function fecharModalDeletar() {
    document.getElementById("modalDeletar").style.display = "none";
}

window.onclick = function(event) {

    var mCriar = document.getElementById("ModalCriar");
    var mAtualizar = document.getElementById("modalAtualizar");
    var mDeletar = document.getElementById("modalDeletar");

    if (event.target == mCriar)
        mCriar.style.display = "none";

    if (event.target == mAtualizar)
        fecharModalAtualizar();

    if (event.target == mDeletar)
        mDeletar.style.display = "none";
}