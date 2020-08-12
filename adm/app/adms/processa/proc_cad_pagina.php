<?php

if (!isset($seguranca)) {
    exit;
}
include_once 'lib/lib_vazio.php';

$SendCadPg = filter_input(
    INPUT_POST,
    'SendCadPg',
    FILTER_SANITIZE_STRING
);

if ($SendCadPg) {
    $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

    //retirar campo da validação vazio
    $dados_obs = $dados['obs'];
    $dados_icone = $dados['icone'];
    unset($dados['obs'], $dados['icone']);

    //validar nenhum campo vazio
    $erro = false;
    include_once 'lib/lib_vazio.php';
    $dados_validos = vazio($dados);

    if (!$dados_validos) {
        $erro = true;
        $_SESSION['msg'] = "<div class='alert alert-danger'>
        Necessário preencher todos os campos para cadastrar a página!</div>";
    }

    if ($erro) {
        $url_destino = pg . '/cadastrar/cad_pagina';
        header("Location: $url_destino");
    } else {
        $result_cad_pg = "INSERT INTO adms_paginas (nome_pagina, endereco, obs, keywords, description,
        author, lib_pub, icone, depend_pg, adms_grps_pg_id, adms_tps_pg_id, adms_robot_id,
        adms_sits_pg_id, created) VALUES (
        '" . $dados_validos['nome_pagina'] . "', '" . $dados_validos['endereco'] . "', '$dados_obs', '" . $dados_validos['keywords'] . "',
        '" . $dados_validos['description'] . "', '" . $dados_validos['author'] . "',
        '" . $dados_validos['lib_pub'] . "', '$dados_icone', '" . $dados_validos['depend_pg'] . "',
        '" . $dados_validos['adms_grps_pg_id'] . "', '" . $dados_validos['adms_tps_pg_id'] . "',
        '" . $dados_validos['adms_robot_id'] . "', '" . $dados_validos['adms_sits_pg_id'] . "',
        NOW())";

        mysqli_query($conn, $result_cad_pg);
        if (mysqli_insert_id($conn)) {
            $_SESSION['msg'] = "<div class='alert alert-success'>
            Página  cadastrada</div>";
            $url_destino = pg . '/listar/list_pagina';
            header("Location: $url_destino");
        } else {
            $_SESSION['msg'] = "<div class='alert alert-danger'>
            Página não cadastrada</div>";
            $url_destino = pg . '/cadastrar/cad_pagina';
            header("Location: $url_destino");
        }
    }
} else {
    $_SESSION['msg'] = "<div class='alert alert-danger'>
    Página não encontrada</div>";
    $url_destino = pg . '/acesso/login';
    header("Location: $url_destino");
}
