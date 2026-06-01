<?php

function limparTexto($valor) {
    return trim((string) $valor);
}

function escaparHtml($valor) {
    return htmlspecialchars((string) $valor, ENT_QUOTES, "UTF-8");
}

function statusSaidaValido($status) {
    return in_array($status, ["aberto", "encerrado"], true);
}

function validarId($valor) {
    $id = filter_var($valor, FILTER_VALIDATE_INT);
    return $id !== false && $id > 0 ? $id : null;
}

function validarData($valor) {
    $data = DateTime::createFromFormat("Y-m-d", $valor);
    return $data && $data->format("Y-m-d") === $valor;
}

function validarDataHora($valor) {
    $dataHora = DateTime::createFromFormat("Y-m-d\TH:i", $valor);
    if ($dataHora && $dataHora->format("Y-m-d\TH:i") === $valor) {
        return $dataHora->format("Y-m-d H:i:s");
    }

    $dataHora = DateTime::createFromFormat("Y-m-d H:i:s", $valor);
    if ($dataHora && $dataHora->format("Y-m-d H:i:s") === $valor) {
        return $valor;
    }

    return null;
}

function prazoAntesDaSaida($dataSaida, $prazoInscricao) {
    $limiteSaida = DateTime::createFromFormat("Y-m-d H:i:s", $dataSaida . " 23:59:59");
    $prazo = DateTime::createFromFormat("Y-m-d H:i:s", $prazoInscricao);

    return $limiteSaida && $prazo && $prazo <= $limiteSaida;
}

function normalizarTelefone($telefone) {
    return preg_replace("/\D+/", "", limparTexto($telefone));
}

function telefoneValido($telefone) {
    $totalDigitos = strlen($telefone);
    return $totalDigitos >= 8 && $totalDigitos <= 15;
}

function desejaJson() {
    $aceitaJson = isset($_SERVER["HTTP_ACCEPT"]) && strpos($_SERVER["HTTP_ACCEPT"], "application/json") !== false;
    $formatoJson = isset($_GET["format"]) && $_GET["format"] === "json";

    return $aceitaJson || $formatoJson;
}

function executadoDiretamente($arquivo) {
    return isset($_SERVER["SCRIPT_FILENAME"]) && realpath($_SERVER["SCRIPT_FILENAME"]) === realpath($arquivo);
}

function responderJson($dados, $statusHttp = 200) {
    http_response_code($statusHttp);
    header("Content-Type: application/json; charset=utf-8");
    echo json_encode($dados, JSON_UNESCAPED_UNICODE);
    exit();
}

function responderErro($mensagem, $voltar = null, $statusHttp = 400) {
    if (desejaJson()) {
        responderJson(["sucesso" => false, "mensagem" => $mensagem], $statusHttp);
    }

    http_response_code($statusHttp);
    echo "<p>" . escaparHtml($mensagem) . "</p>";

    if ($voltar) {
        echo "<p><a href='" . escaparHtml($voltar) . "'>Voltar</a></p>";
    }

    exit();
}

function responderSucesso($mensagem, $dados = [], $redirecionarPara = null) {
    if (desejaJson()) {
        responderJson(array_merge(["sucesso" => true, "mensagem" => $mensagem], $dados));
    }

    if ($redirecionarPara) {
        header("Location: " . $redirecionarPara);
        exit();
    }

    echo "<p>" . escaparHtml($mensagem) . "</p>";
    exit();
}

function exigirPost($redirecionarPara = null) {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        return;
    }

    if ($redirecionarPara) {
        header("Location: " . $redirecionarPara);
        exit();
    }

    responderErro("Método não permitido.", null, 405);
}

function buscarSaidaPorId($conexao, $id) {
    $stmt = $conexao->prepare("SELECT * FROM saidas WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

function buscarProximaSaidaAberta($conexao) {
    $agora = date("Y-m-d H:i:s");
    $stmt = $conexao->prepare("
        SELECT *
        FROM saidas
        WHERE status = 'aberto'
          AND prazo_limite_inscricao >= ?
        ORDER BY data_saida ASC, prazo_limite_inscricao ASC
        LIMIT 1
    ");
    $stmt->bind_param("s", $agora);
    $stmt->execute();

    return $stmt->get_result()->fetch_assoc();
}

function saidaAceitaInscricao($saida) {
    if (!$saida || $saida["status"] !== "aberto") {
        return false;
    }

    return strtotime($saida["prazo_limite_inscricao"]) >= time();
}

