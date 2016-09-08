<?php
session_start();
include_once 'modelo/DaoGeral.php';
include_once 'modelo/DaoGeralDW.php';

include_once 'modelo/DaoUsuario.php';
include_once 'modelo/DaoFrequencia.php';
include_once 'modelo/DaoFatoGrupo.php';
include_once 'modelo/FatoGrupo.php';

include_once 'modelo/DaoCoordenadorIgreja.php';
include_once 'modelo/DaoRegiao.php';

$stringIdUsuario = 'idUsuario';
$idPefilLogado = $_SESSION['idPerfil'];

$daoUsuario = new DaoUsuario(new DaoGeral());
$daoFrequencia = new DaoFrequencia(new DaoGeral());
$daoFatoGrupo = new DaoFatoGrupo(new DaoGeralDW());

/* Dados de quem esta logado */
$usuario = $daoUsuario->consultarPorIdCadastro($_SESSION[$stringIdUsuario]);
/* Buscando relatorio de toda minha arvore abaixo */
$fatoAux = new FatoGrupo();
if ($idPefilLogado == 22) {
    $daoRegiao = new DaoRegiao(new DaoGeral());
    $regiao = $daoRegiao->consultarRegiaoPorIdResponsavel($usuario->idPessoa);
    $numeroIdentificador = str_pad($regiao->id, 3, 0, STR_PAD_LEFT);
} else {
    if ($idPefilLogado == 21) {
        $daoCoordenador = new DaoCoordenadorIgreja(new DaoGeral());
        $coordenacao = $daoCoordenador->consultarPorIdResposanveis($usuario->idPessoa, 0);
        $fatoAux->idEntidade = $coordenacao->id;
        $fatoAux->idTipo = 5;
        $fatoAux->idPai = $coordenacao->idRegiao;
    } else {
        $grupoDados = $daoFrequencia->recuperarDadosPessoa($usuario->idPessoa);
        $fatoAux->idEntidade = $grupoDados->idGrupo;
        $fatoAux->idTipo = $grupoDados->idTipo;
        $fatoAux->idPai = $grupoDados->idPai;
    }
    $fatoAux->mes = date('n');
    $fatoAux->ano = date('Y');
    $numeroIdentificador = $daoFatoGrupo->montarNumeroIdentificador($fatoAux);
}


$daoGeral = new DaoGeral();
$daoGeral->abreConexao();
$sqlConsultarPeloSeuNumeroIdentificador = 'SELECT * FROM questionario WHERE numeroIdentificador LIKE "#numeroIdentificador%"; ';
$sqlConsultarPeloSeuNumeroIdentificador = str_replace('#numeroIdentificador', $numeroIdentificador, $sqlConsultarPeloSeuNumeroIdentificador);

$result = mysql_query($sqlConsultarPeloSeuNumeroIdentificador);
$totalDePessoasQueEnviaramORelatorio = mysql_num_rows($result);

$respostas;
$performance;
$quantidade;
if ($totalDePessoasQueEnviaramORelatorio) {
    while ($r = mysql_fetch_array($result)) {
        for ($i = 1; $i <= 9; $i++) {
            $respostas[$i] += $r['r' . $i];

            if ($i == 2 || $i == 5 || $i == 8) {
                $performance[2] += $respostas[$i];
            }
            if ($i == 4 || $i == 7 || $i == 9) {
                $performance[1] += $respostas[$i];
            }
            if ($i == 1 || $i == 3 || $i == 6) {
                $performance[3] += $respostas[$i];
            }
        }
        $totalDePontos = 0;
        for ($k = 1; $k <= 3; $k++) {
            $totalDePontos += $performance[$k];
        }
        $qualEMaior = 0;
        $valorMaior = 0;
        for ($l = 1; $l <= 3; $l++) {
            $performance[$l] = $performance[$l] / $totalDePontos * 100;

//            if ($performance[$l] > $valorMaior) {
//                $valorMaior = $performance[$l];
//                $qualEMaior = $l;
//            }
        }

//        $quantidade[$qualEMaior] ++;
    }

//    $performanceTotal;
//    for ($l = 1; $l <= 3; $l++) {
//        $performanceTotal[$l] = number_format($quantidade[$l] / $totalDePessoasQueEnviaramORelatorio * 100);
//    }
}
?>
<html lang="pt-BR">
    <head>
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <title>CIRCUITO DA VIS&Atilde;O</title>
        <meta name="keywords" content="CIRCUITO DA VIS&Atilde;O" />
        <meta name="description" content="Falicitador do seu ministério">
        <meta name="author" content="Leonardo Pereira Magalhães">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Font CSS (Via CDN) -->
        <link rel='stylesheet' type='text/css' href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700'></link>
        <!-- Theme CSS -->
        <link rel="stylesheet" type="text/css" href="/visao/absoluteAdmin/assets/skin/default_skin/css/theme.css"></link>
        <!-- Admin Forms CSS -->
        <link rel="stylesheet" type="text/css" href="/visao/absoluteAdmin/assets/admin-tools/admin-forms/css/admin-forms.css"></link>

        <link href = "/visao/css/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
      <![endif]-->
    </head>
    <body class="admin-layout-page">
        <!-- Start: Main -->
        <div id="main">
            <!-- Begin: Content -->
            <section id="content" class="table-layout">

                <!-- Begin: .tray-center -->
                <div class="tray tray-center">
                    <div class="tab-content mw600 center-block center-children">
                        <div class="admin-form">
                            <div class="panel panel-dark heading-border mw800">
                                <div class="panel-body p25 pt10">
                                    <div class="section-divider mb50">
                                        <span>Relat&oacute;rio do Question&aacute;rio</span>
                                    </div>
                                    <table class="table table-bordered">
                                        <tr>
                                            <td>Total de envios</td>
                                            <td><?php echo $totalDePessoasQueEnviaramORelatorio; ?></td>
                                        </tr>  
<!--                                        <tr>
                                            <td colspan="3" class="text-center">Quantidade de pessoas que agem pela:</td>
                                        </tr>  -->
                                        <?php
                                        for ($k = 1; $k <= 3; $k++) {
                                            switch ($k) {
                                                case 1:
                                                    $label = 'Import&acirc;ncia';
                                                    break;
                                                case 2:
                                                    $label = 'Urg&ecircncia';
                                                    break;
                                                case 3:
                                                    $label = 'Circustancial';
                                                    break;
                                                default:
                                                    $label = '';
                                                    break;
                                            }
                                            echo '<tr>';
                                            echo '<td class="text-right">' . $label . '</td>';
                                            echo '<td class="text-right">' . number_format($performance[$k], 2, ',', '.') . '%</td>';
//                                            echo '<td>' . $performanceTotal[$k] . '%</td>';
                                            echo '</tr>';
                                        }
                                        ?>
                                    </table>
                                </div>
                                <!-- end .form-body section -->
                            </div>
                            <!-- end .panel--> 
                        </div>
                    </div>
                    <!-- end: .admin-form -->
                </div>
                <!-- End: .tray-center -->
            </section>
            <!-- End: Content -->
        </div>
        <!-- End: Main -->

        <!-- BEGIN: PAGE SCRIPTS -->

        <!-- jQuery -->
        <script src="/visao/absoluteAdmin/vendor/jquery/jquery-1.11.1.min.js"></script>
        <script src="/visao/absoluteAdmin/vendor/jquery/jquery_ui/jquery-ui.min.js"></script>

        <!-- Theme Javascript -->
        <script src="/visao/absoluteAdmin/assets/js/main.js"></script>

        <script src="/visao/css/bootstrap/js/bootstrap.min.js"></script>

        <script src="/visao/js/googleAnalytics.js"></script>
        <!-- END: PAGE SCRIPTS -->
    </body>
</html>