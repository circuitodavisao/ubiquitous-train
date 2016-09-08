<?php

$stringAcao = 'acao';
$stringSalvar = 'salvar';
$acao = (string) \filter_input(\INPUT_POST, $stringAcao);

if (!\strcmp($acao, $stringSalvar)) {

    include_once 'modelo/DaoGeral.php';
    include_once 'modelo/DaoGeralDW.php';

    include_once 'modelo/DaoUsuario.php';
    include_once 'modelo/DaoFrequencia.php';
    include_once 'modelo/DaoFatoGrupo.php';
    include_once 'modelo/FatoGrupo.php';

    $stringIdUsuario = 'idUsuario';

    $daoUsuario = new DaoUsuario(new DaoGeral());
    $daoFrequencia = new DaoFrequencia(new DaoGeral());
    $daoFatoGrupo = new DaoFatoGrupo(new DaoGeralDW());

    /* Dados de quem esta logado */
    $usuario = $daoUsuario->consultarPorIdCadastro($_SESSION[$stringIdUsuario]);
    /* Buscando relatorio de toda minha arvore abaixo */
    $fatoAux = new FatoGrupo();
    $grupoDados = $daoFrequencia->recuperarDadosPessoa($usuario->idPessoa);
    $fatoAux->idEntidade = $grupoDados->idGrupo;
    $fatoAux->idTipo = $grupoDados->idTipo;
    $fatoAux->idPai = $grupoDados->idPai;
    $fatoAux->mes = date('n');
    $fatoAux->ano = date('Y');
    $numeroIdentificador = $daoFatoGrupo->montarNumeroIdentificador($fatoAux);

    $sqlCreate = 'INSERT INTO questionario (numeroIdentificador, r1, r2, r3, r4, r5, r6, r7, r8, r9)'
            . ' VALUES ("#numeroIdentificador", #r1, #r2, #r3, #r4, #r5, #r6, #r7, #r8, #r9);';
    $sqlCreate = str_replace('#numeroIdentificador', $numeroIdentificador, $sqlCreate);
    for ($i = 1; $i <= 9; $i++) {
        $r[$i] = \filter_input(\INPUT_POST, 'r' . $i);
        $sqlCreate = str_replace('#r' . $i, $r[$i], $sqlCreate);
    }
    $daoGeral = new DaoGeral();
    $daoGeral->abreConexao();
    $resposta = mysql_query($sqlCreate);

    echo $resposta;
}