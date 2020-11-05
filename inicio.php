<?php
    require_once('config.php');

        if(empty($_SESSION['key'])) {
            header ('location:./');
        }
    
    //Funções

    function diaSemana($data) {
        $diasemana = array('Domingo', 'Segunda', 'Ter&ccedil;a', 'Quarta', 'Quinta', 'Sexta', 'S&aacute;bado');
        #$data = date('Y-m-d');

        // Varivel que recebe o dia da semana (0 = Domingo, 1 = Segunda ...)
        $diasemana_numero = date('w', strtotime($data));
        
        return $diasemana[$diasemana_numero];
    }

    function diaFeriado($datado) {
        $url = 'https://api.calendario.com.br/?json=true&ano=2017&ibge=4203204&token=cm9nZXJ0dGlAcG0ubWUmaGFzaD0xNjc4MTY0MTk';
        $data = file_get_contents($url);
        $feriados = json_decode($data);

            foreach($feriados as $feriado) {
                if(($feriado->type_code == '1') or ($feriado->type_code == '3')) {
                    $dia = substr($feriado->date,0,2);
                    $mes = substr($feriado->date,3,2);
                    $feriado->date = $dia.'/'.$mes;
                    #echo $feriado->date.'<br>';

                        if($feriado->date == $datado) {
                            return $feriado->date;
                        } 
                }
            }
    }

    function nomeFeriado($datado) {
        $url = 'https://api.calendario.com.br/?json=true&ano=2017&ibge=4203204&token=cm9nZXJ0dGlAcG0ubWUmaGFzaD0xNjc4MTY0MTk';
        $data = file_get_contents($url);
        $feriados = json_decode($data);

            foreach($feriados as $feriado) {
                if(($feriado->type_code == '1') or ($feriado->type_code == '3')) {
                    $dia = substr($feriado->date,0,2);
                    $mes = substr($feriado->date,3,2);
                    $feriado->date = $dia.'/'.$mes;

                        if($feriado->date == $datado) {
                            return $feriado->name;
                        }
                } 
            }
    }

    function mes_extenso($fmes) {
        switch ($fmes) {
            case '01': $fmes = 'Janeiro'; break;
            case '02': $fmes = 'Fevereiro'; break;
            case '03': $fmes = 'Mar&ccedil;o'; break;
            case '04': $fmes = 'Abril'; break;
            case '05': $fmes = 'Maio'; break;
            case '06': $fmes = 'Junho'; break;
            case '07': $fmes = 'Julho'; break;
            case '08': $fmes = 'Agosto'; break;
            case '09': $fmes = 'Setembro'; break;
            case '10': $fmes = 'Outubro'; break;
            case '11': $fmes = 'Novembro'; break;
            case '12': $fmes = 'Dezembro'; break;
        }

        return $fmes;
    }

    //Esse bloco controla a linha do tempo

    $getmes = md5('mes');
    $getano = md5('ano');

        if(isset($_GET[''.$getmes.''])) {
            $mes = $_GET[''.$getmes.''];
        } else {
            $mes = date('m');
        }

        if(isset($_GET['left'])) {
            if($mes == '12') {
                $ano = $_GET[''.$getano.''] - 1;
            } else {
                $ano = $_GET[''.$getano.''];
            }
        }

        if(isset($_GET['right'])) {
            if($mes == '01') {
                $ano = $_GET[''.$getano.''] + 1;
            } else {
                $ano = $_GET[''.$getano.''];
            }
        }

        if(isset($_GET['pick'])) {
            $ano = $_GET[''.$getano.''];
        }

        if ((!isset($_GET['left'])) and (!isset($_GET['right'])) and (!isset($_GET['pick']))) {
            $ano = date('Y');
        }

    //Controla o link ativo na barra lateral

    $m = 1;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <title><?php echo $cfg['titulo']; ?></title>
        <link rel="icon" type="image/png" href="img/favicon.png">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="stylesheet" href="css/ionicons.min.css">
        <link rel="stylesheet" href="css/bootstrap-datepicker.min.css">
        <link rel="stylesheet" href="css/smoke.min.css">
        <link rel="stylesheet" href="css/layout.min.css">
        <link rel="stylesheet" href="css/core.min.css">
        <!--[if lt IE 9]><script src="js/html5shiv.min.js"></script><script src="js/respond.min.js"></script><![endif]-->
    </head>
    <body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
        <div class="wrapper">
            <!-- Main Header -->
            <header class="main-header"><?php include_once('header.php'); ?></header>

            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar"><?php include_once('sidebar.php'); ?></aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <?php
                    $hora = date('H:i:s');
                    $data = date('d/m/Y');

                    #if(diaSemana() != 'domingo') {
                        if(diaFeriado(date('d/m')) != true) {
                            #$registra_ponto = '<span class="pull-right lead"><a class="btn btn-primary" id="reg-'.$hora.'-'.$data.'" data-toggle="modal" data-target="#modal-add-registro" href="#" title="Clique para registrar o ponto"><i class="fa fa-clock-o"></i> Registrar o ponto</a></span>';
                            $registra_ponto = '<span class="pull-right lead"><a class="btn btn-primary clock registra-ponto" id="reg-'.$hora.'-'.$data.'" href="#" title="Clique para registrar o ponto"><i class="fa fa-clock-o"></i> '.date('H:i:s').' Registrar o ponto</a></span>';
                        } else {
                            $registra_ponto = '<span class="pull-right lead">Feriado: '.nomeFeriado(date('d/m')).'</span>';
                        }
                    #} else {
                    #    $registra_ponto = '<span class="pull-right lead">Hoje &eacute; domingo.</span>';
                    #}
                ?>
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        <span><?php echo $_SESSION['name']; ?></span>
                        <!--<span class="clock"><?php echo date('H:i:s'); ?></span>-->
                        <?php echo $registra_ponto; ?>
                    </h1>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="box">
                        <div class="box-body">
                        <?php
                            try {
                                include_once('conexao.php');

                                $mesleft = $mes - 1;
                                $mesright = $mes + 1;
                                    
                                    if(strlen($mesleft) == 1) {
                                        $mesleft = '0'.$mesleft;
                                        
                                            if($mesleft == '00') {
                                                $mesleft = '12';
                                            }
                                    }
                                
                                    if(strlen($mesright) == 1) {
                                        $mesright = '0'.$mesright;
                                            
                                            if($mesright == '13') {
                                                $mesright = '01';
                                            }
                                    } else {
                                        if($mesright == '13') {
                                            $mesright = '01';
                                        }
                                    }

                                $_SESSION['id'] = (int)$_SESSION['id'];
                                #$mes = date('m');
                                #$ano = date('Y');
                                #$week = date('N');
                                $hora = '00:00:00';
                                $prevday = date('d', strtotime(date('Y-m-d') .' -1 day'));
                                #$next_date = date('Y-m-d', strtotime(date('Y-m-d') .' +1 day'));

                                // Verifica se todos os períodos foram registrados no dia anterior
                                $sql = $pdo->prepare("SELECT tipo FROM registro WHERE login_idlogin = :idlogin AND dia = :dia AND mes = :mes AND ano = :ano AND hora <> :hora ORDER BY dia, hora");
                                $sql->bindParam(':idlogin', $_SESSION['id'], PDO::PARAM_INT);
                                $sql->bindParam(':dia', $prevday, PDO::PARAM_STR);
                                $sql->bindParam(':mes', $mes, PDO::PARAM_STR);
                                $sql->bindParam(':ano', $ano, PDO::PARAM_STR);
                                $sql->bindParam(':hora', $hora, PDO::PARAM_STR);
                                $res = $sql->execute();
                                $ret = $sql->rowCount();

                                    if ($ret < 4) {
                                        $noprevday = true;

                                        echo'
                                        <div class="div-previous-date">
                                            <p class="lead">Os registros do dia anterior não foram finalizados, comunique o administrador.</p>
                                        </div>';
                                    } else {
                                        $noprevday = false;
                                    }
                                
                                $sql->closeCursor();

                                // Busca em todos os registros do mês
                                $sql = $pdo->prepare("SELECT tipo,dia,hora FROM registro WHERE login_idlogin = :idlogin AND mes = :mes AND ano = :ano AND hora <> :hora ORDER BY dia, hora");
                                $sql->bindParam(':idlogin', $_SESSION['id'], PDO::PARAM_INT);
                                $sql->bindParam(':mes', $mes, PDO::PARAM_STR);
                                $sql->bindParam(':ano', $ano, PDO::PARAM_STR);
                                $sql->bindParam(':hora', $hora, PDO::PARAM_STR);
                                $res = $sql->execute();
                                $ret = $sql->rowCount();

                                    if ($ret > 0) {
                                        $hm = '';

                                        echo'
                                        <!--<h4 class="">'.mes_extenso($mes).' de '.$ano.'</h4>-->
                                        <div class="div-time">
                                            <div class="div-time-left text-center">
                                                <a class="lead" href="inicio?'.$getmes.'='.$mesleft.'&'.$getano.'='.$ano.'&left=1" title="M&ecirc;s anterior">
                                                    <i class="fa fa-arrow-left"></i>
                                                </a>
                                            </div>
                                            <div class="div-time-center">
                                                <p class="lead text-center">
                                                    <!--<span class="text-bold text-uppercase">'.mes_extenso($mes).' de '.$ano.'</span>-->
                                                    <input type="text" class="date-pick text-center" value="'.mes_extenso($mes).' de '.$ano.'" readonly>
                                                </p>
                                            </div>
                                            <div class="div-time-right text-center">
                                                <a class="lead" href="inicio?'.$getmes.'='.$mesright.'&'.$getano.'='.$ano.'&right=1" title="Pr&oacute;ximo m&ecirc;s">
                                                    <i class="fa fa-arrow-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                                                            
                                        <hr>

                                        <table class="table table-border table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th><span class="hidden-xs">In&iacute;cio do expediente</span><span class="hidden-sm hidden-md hidden-lg">In&iacute;cio</span></th>
                                                    <th><span class="hidden-xs">Sa&iacute;da para o almo&ccedil;o</span><span class="hidden-sm hidden-md hidden-lg">Sa&iacute;da</span></th>
                                                    <th><span class="hidden-xs">Chegada do almo&ccedil;o</span><span class="hidden-sm hidden-md hidden-lg">Volta</span></th>
                                                    <th><span class="hidden-xs">Fim do expediente</span><span class="hidden-sm hidden-md hidden-lg">Fim</span></th>
                                                </tr>
                                            </thead>
                                            <tbody>';

                                        while($lin = $sql->fetch(PDO::FETCH_OBJ)) {
                                            //Suprime os segundos
                                            $hora = substr($lin->hora,0,5);

                                                //Condição para iniciar o grid, vai para a primeira condição do else,
                                                //depois retorna comparando os tipos
                                                if(isset($dia)) {
                                                    if($dia == $lin->dia) {
                                                        #echo $tipo.' '.$lin->tipo.'<br>';
                                                        if($tipo == 'PHM' and $lin->tipo == 'SHM') {
                                                            $hm .= '<td>'.$hora.' h</td>';
                                                            $tipo = $lin->tipo;
                                                        } elseif($tipo == 'SHM' and $lin->tipo == 'PHT') {
                                                            $hm .= '<td>'.$hora.' h</td>';
                                                            $tipo = $lin->tipo;
                                                        } elseif($tipo == 'PHT' and $lin->tipo == 'SHT') {
                                                            $hm .= '<td>'.$hora.' h</td>';
                                                            $tipo = $lin->tipo;
                                                        } else {
                                                            $hm .= '<td>'.$hora.' h</td>';
                                                            $tipo = 'PHM';
                                                        }
                                                    } else {
                                                        switch($lin->tipo) {
                                                            case 'PHM':
                                                                $hm .= '
                                                                <tr>
                                                                    <td>'.$lin->dia.' - '.diaSemana($ano.'-'.$mes.'-'.$lin->dia).'</td>
                                                                    <td>'.$hora.' h</td>';
                                                                $tipo = $lin->tipo;
                                                                break;
                                                            case 'SHM':
                                                                $hm .= '
                                                                <tr>
                                                                    <td>'.$lin->dia.' - '.diaSemana($ano.'-'.$mes.'-'.$lin->dia).'</td>
                                                                    <td></td>
                                                                    <td>'.$hora.' h</td>';
                                                                $tipo = $lin->tipo;
                                                                break;
                                                            case 'PHT':
                                                                $hm .= '
                                                                <tr>
                                                                    <td>'.$lin->dia.' - '.diaSemana($ano.'-'.$mes.'-'.$lin->dia).'</td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td>'.$hora.' h</td>';
                                                                $tipo = $lin->tipo;
                                                                break;
                                                            case 'SHT':
                                                                $hm .= '
                                                                <tr>
                                                                    <td>'.$lin->dia.' - '.diaSemana($ano.'-'.$mes.'-'.$lin->dia).'</td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td>'.$hora.' h</td>';
                                                                $tipo = $lin->tipo;
                                                                break;
                                                        }
                                                    }
                                                } else {
                                                    switch($lin->tipo) {
                                                        case 'PHM':
                                                            $hm .= '
                                                            <tr>
                                                                <td>'.$lin->dia.' - '.diaSemana($ano.'-'.$mes.'-'.$lin->dia).'</td>
                                                                <td>'.$hora.' h</td>';
                                                            $tipo = $lin->tipo;
                                                            break;
                                                        case 'SHM':
                                                            $hm .= '
                                                            <tr>
                                                                <td>'.$lin->dia.' - '.diaSemana($ano.'-'.$mes.'-'.$lin->dia).'</td>
                                                                <td></td>
                                                                <td>'.$hora.' h</td>';
                                                            $tipo = $lin->tipo;
                                                            break;
                                                        case 'PHT':
                                                            $hm .= '
                                                            <tr>
                                                                <td>'.$lin->dia.' - '.diaSemana($ano.'-'.$mes.'-'.$lin->dia).'</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td>'.$hora.' h</td>';
                                                            $tipo = $lin->tipo;
                                                            break;
                                                        case 'SHT':
                                                            $hm .= '
                                                            <tr>
                                                                <td>'.$lin->dia.' - '.diaSemana($ano.'-'.$mes.'-'.$lin->dia).'</td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td>'.$hora.' h</td>';
                                                            $tipo = $lin->tipo;
                                                            break;
                                                    } //switch
                                                } //else

                                            $dia = $lin->dia;
                                        } //while

                                        echo $hm.'
                                                </tr>
                                            </tbody>
                                        </table>';
                                    } else {
                                        echo'
                                        <div class="div-time">
                                            <div class="div-time-left text-center">
                                                <a class="lead" href="inicio?'.$getmes.'='.$mesleft.'&'.$getano.'='.$ano.'&left=1" title="M&ecirc;s anterior">
                                                    <i class="fa fa-arrow-left"></i>
                                                </a>
                                            </div>
                                            <div class="div-time-center">
                                                <p class="lead text-center">
                                                    <!--<span class="text-bold text-uppercase">'.mes_extenso($mes).' de '.$ano.'</span>-->
                                                    <input type="text" class="date-pick text-center" value="'.mes_extenso($mes).' de '.$ano.'" readonly>
                                                </p>
                                            </div>
                                            <div class="div-time-right text-center">
                                                <a class="lead" href="inicio?'.$getmes.'='.$mesright.'&'.$getano.'='.$ano.'&right=1" title="Pr&oacute;ximo m&ecirc;s">
                                                    <i class="fa fa-arrow-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                                                            
                                        <hr>
                                        
                                        <h4 class="text-center">Nenhum registro encontrado nesse m&ecirc;s</h4>';
                                    }
                            }
                            catch(PDOException $e) {
                                echo'<span>Erro ao conectar o servidor: '.$e->getMessage().'</span>';
                            }
                        ?>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->

            <!-- Modal -->
            <div class="modal fade" id="modal-add-registro" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form class="form-add-registro">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title">Aviso</h4>
                            </div>
                            <div class="modal-body">
                                <span>O login foi feito com com dados pr&eacute;-definidos, &eacute; recomend&aacute;vel que voc&ecirc; altere seus dados.</span>
                            </div>
                            <div class="modal-footer">
                                <a class="btn btn-primary btn-flat" title="Alterar os dados" href="usuario">Alterar os dados</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modal-alert-change" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form class="form-recupera-senha">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title">Aviso</h4>
                            </div>
                            <div class="modal-body">
                                <span>O login foi feito com com dados pr&eacute;-definidos, &eacute; recomend&aacute;vel que voc&ecirc; altere seus dados.</span>
                            </div>
                            <div class="modal-footer">
                                <a class="btn btn-primary btn-flat" title="Alterar os dados" href="usuario">Alterar os dados</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Main Footer -->
            <footer class="main-footer"><?php include_once('footer.php'); ?></footer>
        </div><!-- ./wrapper -->

        <script src="js/jquery-2.1.4.min.js"></script>
        <script defer src="js/bootstrap.min.js"></script>
        <script async src="js/smoke.min.js"></script>
        <script src="js/bootstrap-datepicker.min.js"></script>
        <script src="js/bootstrap-datepicker.pt-BR.min.js"></script>
        <script async src="js/core.min.js"></script>
        <script async>
            /*jslint browser: true*/
            /*jslint node: true*/
            /*jslint browser: true*/
            /*jslint nomen: true*/
            /*jslint plusplus: true*/
            /*global $, jQuery, alert*/

            "use strict";

            $(document).ready(function () {
                /* RELOGIO */

                var clock = function () {
                    var currentTime = new Date(), currentHours = currentTime.getHours(), currentMinutes = currentTime.getMinutes(), currentSeconds = currentTime.getSeconds(), currentTimeString;
                    currentMinutes = (currentMinutes < 10 ? "0" : "") + currentMinutes;
                    currentSeconds = (currentSeconds < 10 ? "0" : "") + currentSeconds;
                    currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds;
                    $(".clock").html('<i class="fa fa-clock-o"></i> ' + currentTimeString + ' Registrar o ponto');
                };

                setInterval(clock, 1000);

                /* DATEPICKER */

                $(".date-pick").datepicker({
                    language: 'pt-BR',
                    format: "mm yyyy",
                    startView: 1,
                    minViewMode: 1
                }).on('hide', function(e) {
                    var dt = e.target.value.split(' ');
                    location.href = "inicio?<?php echo $getmes; ?>=" + dt[0] + "&<?php echo $getano; ?>=" + dt[1] + "&pick=1";
                });

                /* REGISTRAR O PONTO */

                $(".content-wrapper").on('click', '.registra-ponto', function (e) {
                    e.preventDefault();
                    $('.registra-ponto').addClass('hide');

                    var click = this.id.split('-'), hora = click[1], data = click[2];
                    var gdate = new Date(),
                        thor = gdate.getHours(),
                        tmin = gdate.getMinutes(),
                        tseg = gdate.getSeconds(),
                        tdia = gdate.getDate(),
                        tmes = gdate.getMonth(),
                        tano = gdate.getFullYear(), hora, data;

                        if (thor.length == 1) {
                            thor = '0' + thor;
                        }
                        if (tmin.length == 1) {
                            tmin = '0' + tmin;
                        }
                        if (tseg.length == 1) {
                            tseg = '0' + tseg;
                        }
                        if (tdia.length == 1) {
                            tdia = tdia + 1;
                            tdia = '0' + tdia;
                        }
                        else {
                            tdia = tdia + 1;
                        }
                        if (tmes.length == 1) {
                            tmes = tmes + 1;
                            tmes = '0' + tmes;
                        }
                        else {
                            tmes = tmes + 1;
                        }
                        if (tano.length == 1) {
                            tano = '0' + tano;
                        }

                    hora = thor + ':' + tmin + ':' + tseg;
                    //data = tdia + '/' + tmes + '/' + tano;

                    $.ajax({
                        type: 'GET',
                        url: 'insertRegistro.php?hora=' + hora + '&data=' + data,
                        cache: false,
                        success: function (data) {
                            switch (data) {
                            case 'true':
                                $.smkAlert({text: 'O ponto foi registrado com sucesso.', type: 'success', time: 2});
                                window.setTimeout("location.href='inicio'", 500);
                                break;
                            default:
                                $.smkAlert({text: data, type: 'warning', time: 2});
                                break;
                            }
                        }
                    });
                });

                /* MODAL ATUALIZAR OS DADOS */
                <?php
                    if ($_SESSION['pat'] == 'O') {
                ?>
                $(window).load(function () {
                    $("#modal-alert-change").modal("show");
                });
                <?php
                    }

                    if ($noprevday == true) {
                ?>
                    $(".registra-ponto, .div-time").addClass("hide");
                <?php
                    }
                ?>
            });
        </script>
    </body>
</html>
<?php unset($cfg,$adm,$admactive,$m,$pdo,$e,$hora,$data,$registra_ponto,$sql,$res,$ret,$lin,$mes,$ano,$mes_extenso,$fmes,$hora); ?>
