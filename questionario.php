<?php
$tituloQuestionario = 'Questionário: Como é a produtividade do seu trabalho?';
$perguntas[1] = 'A) Tenho realizado tarefas que não me trazem resultado ministerial, pessoal ou profissional, por comodidade, necessidade, ordens ou falta de opção.';
$perguntas[2] = 'B) Não consigo realizar tudo que me propus fazer no dia e preciso avançar no horário até muito tarde, atrapalhando outras rotinas importantes.';
$perguntas[3] = 'C) Quando recebo um novo e-mail ou mensagem no celular, costumo dar uma olhada para checar o conteúdo.';
$perguntas[4] = 'D) Tenho dedicado regularmente tempo para pessoas importantes em minha vida.';
$perguntas[5] = 'E) Costumo resolver problemas e urgências que acontecem inesperadamente no meu dia-a-dia.';
$perguntas[6] = 'F) Costumo aceitar facilmente tarefas que outras pessoas me pedem.';
$perguntas[7] = 'G) Consigo tempo para esporte, lazer e atividades pessoais.';
$perguntas[8] = 'H) Tenho o hábito de deixar para a última hora a conclusão de atividades diversas.';
$perguntas[9] = 'I) Escrevo metas, bem especificadas e com passos para alcançá-la';

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

$daoGeral = new DaoGeral();
$daoGeral->abreConexao();
$sqlConsultarSeEnviouORelatorio = 'SELECT id FROM questionario WHERE numeroIdentificador = "#numeroIdentificador"; ';
$sqlConsultarSeEnviouORelatorio = str_replace('#numeroIdentificador', $numeroIdentificador, $sqlConsultarSeEnviouORelatorio);
$result = mysql_query($sqlConsultarSeEnviouORelatorio);
if (mysql_result($result, 0)) {
    echo "<script type='text/javascript'>alert('Questionario ja enviado!');</script>";
    echo "<script type='text/javascript'>location.href='visao/ciclo/gruposArvore.php';</script>";
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
                    <div class="tab-content mw800 center-block center-children">
                        <div class="admin-form">
                            <form method="post" action="/" id="form-login1">
                                <div class="panel panel-dark heading-border mw800">
                                    <div class="panel-body p25 pt10">
                                        <div class="section-divider mb50">
                                            <span><?php echo $tituloQuestionario; ?></span>
                                        </div>

                                        <?php
                                        for ($indiceDePerguntas = 1; $indiceDePerguntas <= count($perguntas); $indiceDePerguntas++) {
                                            echo '<div class="section row">';
                                            echo '<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
                                            echo '<span>' . $perguntas[$indiceDePerguntas] . '</span>';

                                            for ($indiceDeRadioBotao = 1; $indiceDeRadioBotao <= 5; $indiceDeRadioBotao++) {
                                                switch ($indiceDeRadioBotao) {
                                                    case 1:
                                                        $labelRadio = 'Nunca';
                                                        break;
                                                    case 2:
                                                        $labelRadio = 'Raramente';
                                                        break;
                                                    case 3:
                                                        $labelRadio = 'Ás Vezes';
                                                        break;
                                                    case 4:
                                                        $labelRadio = 'Quase Sempre';
                                                        break;
                                                    case 5:
                                                        $labelRadio = 'Sempre';
//                                                        $checked = 'checked';
                                                        break;
                                                    default:
                                                        $labelRadio = '';
                                                        $checked = '';
                                                        break;
                                                }
                                                echo '<label class="option block mt15">';
                                                echo '<input type="radio" '
                                                . 'id="resposta' . $indiceDePerguntas . '" '
                                                . 'name="resposta' . $indiceDePerguntas . '" '
                                                . 'value="' . $indiceDeRadioBotao . '" ' . $checked . ' required>';
                                                echo '<span class="radio"></span>' . $labelRadio;
                                                echo '</label>';
                                            }

                                            echo '</div>';
                                            echo '</div>';
                                        }
                                        ?>
                                        <button id="botaoLogar" type="button" class="button btn-dark pull-right mt20" 
                                                onclick="processarQuestionario(<?php echo count($perguntas); ?>);" >
                                            <span id="spanLogar">Enviar</span>
                                            <span id="spanLoader" class="hidden"><img src="visao/imagens/17.gif" /></span>
                                        </button>                                        
                                    </div>
                                    <!-- end .form-body section -->
                                    <div class="panel-footer">
                                        <div class="panel panel-dark heading-border">
                                            <div class="panel-body">
                                                <div class="section-divider mb20">
                                                    <span>Resultado do Question&aacute;rio</span>
                                                </div>
                                                <p>Muito bem! Para finalizar, vamos descobrir a percentagem do seu tempo em cada esfera da Tríade:</p>
                                                <!-- Pie Chart -->
                                                <div class="panel">
                                                    <div class="panel-body pn">
                                                        <div id="high-pie-questionario" style="width: 100%; height: 210px; margin: 0 auto"></div>
                                                    </div>
                                                </div>
                                                <div id="divResposta" class="hidden">
                                                    <p>Importante – São as coisas que trazem resultados e tem tempo para serem feitas.</p>
                                                    <ul>
                                                        <li>São atividades que podem esperar horas, dias, semanas etc.</li>
                                                        <li>Você tem uma sensação positiva na execução do importante.</li>
                                                        <li>É a esfera da estrada certa, na qual você coloca seu carro e sabe que na linha de chegada estará o resultado planejado</li>
                                                        <li>Por ex.: o planejamento estratégico, reuniões de monitoramento, trabalhos realizados para prevenção de problemas, lazer, exercícios fisico, estabelecimento de parcerias, orações, ajuda ao próximo, etc…</li>
                                                        <li>A tarefas importantes tem prazo para ser feitas, do contrário elas seriam urgentes, proporcionam prazer em serem executadas, em geral são espontâneas</li>
                                                    </ul>
                                                    <p>Urgente – A esfera da urgência abrange todas as atividades na qual o tempo está curto ou acabou.</p>
                                                    <ul>
                                                        <li>São as atividades que chegam em cima da hora, que em alguns casos não podem ser previstas.</li>
                                                        <li>Essas atividades geram pressão, estresse, correria.</li>
                                                        <li>O maior erro das urgências é defini-las como prioridades, assim muitos se preocupam em priorizar as urgências. Muitas pessoas fazem isso inconscientemente por se sentirem bem em serem as solucionadoras de problemas que ninguém resolveu, e assim se sente atuando no papel de herói.</li>
                                                        <li>Se fizer uma analogia da sua vida com um voo de avião, você está na posição de piloto ou passageiro?</li>
                                                    </ul>
                                                    <p>Circunstancial – cobre as tarefas desnecessárias, sem resultados.</p>
                                                    <ul>
                                                        <li>São os gastos de tempo de forma inútil,</li>
                                                        <li>Tarefas feitas por comodidade ou por serem ‘socialmente’ apropriadas.</li>
                                                        <li>É a esfera da estrada que não leva a lugar nenhum</li>
                                                        <li>Por ex.: uma visita que chega de surpresa, o cafezinho, etc…</li>
                                                        <li>Podem ser importantes ou urgentes para outras pessoas, mas não para você</li>
                                                        <li>São coisas que você faz em excesso e acaba perdendo tempo desnecessariamente</li>
                                                        <li>Estão contra sua plena vontade</li>
                                                        <li>Você aceita por educação, por condições ou por medo de dizer não</li>
                                                        <li>Geram a sensação de insatisfação, angústia, saturamento, decepção.</li>
                                                    </ul>
                                                </div>     
                                            </div>                                           
                                        </div>
                                    </div>
                                </div>
                                <!-- end .panel-->                              
                            </form>
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
        <script src="/visao/absoluteAdmin/vendor/plugins/highcharts/highcharts.js"></script>
        <script src="/visao/js/funcoesGraficosPie.js?v=0.1"></script>

        <script src="/visao/css/bootstrap/js/bootstrap.min.js"></script>

        <script src="/visao/js/googleAnalytics.js"></script>

        <script type="text/javascript">
                                                    function processarQuestionario(totalDePerguntas) {


                                                        var resposta = [0, 0, 0];
                                                        var perguntas = [];
                                                        var label = [];
                                                        var dados = [];

                                                        for (var i = 1; i <= totalDePerguntas; i++) {
                                                            var stringInput = "input[name=resposta" + i + "]:checked";
                                                            perguntas[i] = parseInt($(stringInput).val());
                                                            if (isNaN(perguntas[i])) {
                                                                alert('Selecione ao menos uma resposta em cada questão!');
                                                                return false;
                                                            }
                                                            if (i === 2 || i === 5 || i === 8) {
                                                                resposta[1] += perguntas[i];
                                                            }
                                                            if (i === 4 || i === 7 || i === 9) {
                                                                resposta[0] += perguntas[i];
                                                            }
                                                            if (i === 1 || i === 3 || i === 6) {
                                                                resposta[2] += perguntas[i];
                                                            }
                                                        }

                                                        $('#spanLogar').html('Processando');
                                                        $('#spanLoader').toggleClass('hidden');
                                                        $('#botaoLogar').attr('disabled', true);

                                                        var totalGeral = resposta[0] + resposta[1] + resposta[2];
                                                        for (var j = 0; j < 3; j++) {
                                                            resposta[j] = resposta[j] / totalGeral * 100;
                                                            switch (j) {
                                                                case 0:
                                                                    label[j] = 'Importancia  ' + resposta[j].toPrecision(4) + '%';
                                                                    break;
                                                                case 1:
                                                                    label[j] = 'Urgencia  ' + resposta[j].toPrecision(4) + '%';
                                                                    break;
                                                                case 2:
                                                                    label[j] = 'Circustancial  ' + resposta[j].toPrecision(4) + '%';
                                                                    break;
                                                            }
                                                            dados[j] = [label[j], parseInt(resposta[j].toPrecision(2))];
                                                        }

                                                        demoHighPies(dados, 'high-pie-questionario');

                                                        $.ajax({
                                                            url: "questionarioProcessamento.php",
                                                            data: {
                                                                acao: 'salvar',
                                                                r1: perguntas[1],
                                                                r2: perguntas[2],
                                                                r3: perguntas[3],
                                                                r4: perguntas[4],
                                                                r5: perguntas[5],
                                                                r6: perguntas[6],
                                                                r7: perguntas[7],
                                                                r8: perguntas[8],
                                                                r9: perguntas[9]
                                                            },
                                                            type: "POST",
                                                            success: function (resposta) {
                                                                if (parseInt(resposta) === 1) {
                                                                    $('#spanLogar').html('Terminado');
                                                                    $('#spanLoader').toggleClass('hidden');
                                                                    $('#divResposta').toggleClass('hidden');
                                                                } else {
                                                                    alert('Erro de conexão!');
                                                                }
                                                            }
                                                        });
                                                    }
        </script>

        <!-- END: PAGE SCRIPTS -->
    </body>
</html>