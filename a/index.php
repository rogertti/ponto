<?php
    require_once('../config.php');
        
        if(empty($_SESSION['key'])) {
            header ('location:../');
        }

    function diaSemana($data) {
        $diasemana = array('Domingo', 'Segunda', 'Ter&ccedil;a', 'Quarta', 'Quinta', 'Sexta', 'S&aacute;bado');
        #$data = date('Y-m-d');

        // Varivel que recebe o dia da semana (0 = Domingo, 1 = Segunda ...)
        $diasemana_numero = date('w', strtotime($data));
        
        return $diasemana[$diasemana_numero];
    }

    function mes_extenso ($fmes) {
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

    $m = 1;

    //GET URL
    $geturl = $_SERVER['REQUEST_URI'];
    $geturl = explode('/', $geturl);
    $_SESSION['geturl'] = $geturl[2]; // 2 para server e 3 para local
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <title><?php echo $cfg['titulo']; ?></title>
        <link rel="icon" type="image/png" href="../img/favicon.png">
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/font-awesome.min.css">
        <link rel="stylesheet" href="../css/ionicons.min.css">
        <link rel="stylesheet" href="../css/bootstrap-datepicker.min.css">
        <link rel="stylesheet" href="../css/smoke.min.css">
        <link rel="stylesheet" href="../css/layout.min.css">
        <link rel="stylesheet" href="../css/core.min.css">
        <!--[if lt IE 9]><script src="../js/html5shiv.min.js"></script><script src="../js/respond.min.js"></script><![endif]-->
    </head>
    <body class="hold-transition skin-blue sidebar-mini sidebar-collapse">
        <div class="wrapper">
            <!-- Main Header -->
            <header class="main-header"><?php include_once('header.php'); ?></header>

            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar"><?php include_once('sidebar.php'); ?></aside>

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>In&iacute;cio</h1>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="box">
                        <div class="box-body">
                        <?php
                            $hora = date('H:i:s');
                            $data = date('d/m/Y');

                            try {
                                include_once('../conexao.php');

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
                                        
                                $sql = $pdo->prepare("SELECT login.idlogin,login.nome AS funcionario,login.log,registro.tipo,registro.dia,registro.hora FROM registro INNER JOIN login ON registro.login_idlogin = login.idlogin WHERE registro.mes = :mes AND registro.ano = :ano ORDER BY login.nome");
                                $sql->bindParam(':mes', $mes, PDO::PARAM_STR);
                                $sql->bindParam(':ano', $ano, PDO::PARAM_STR);
                                $res = $sql->execute();
                                $ret = $sql->rowCount();
                                
                                    if($ret > 0) {
                                        $nav = '';
                                        $tab = '';
                                        $script = '';
                                        $i = 1;
                                        $pathora = '00:00:00';
                                        $pylogin = md5('idlogin');
                                        $pynome = md5('nome');
                                        $pyano = md5('ano');
                                        
                                        echo'
                                        <div class="div-time">
                                            <div class="div-time-left text-center">
                                                <a class="lead" href="index.php?'.$getmes.'='.$mesleft.'&'.$getano.'='.$ano.'&left=1" title="M&ecirc;s anterior">
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
                                                <a class="lead" href="index.php?'.$getmes.'='.$mesright.'&'.$getano.'='.$ano.'&right=1" title="Pr&oacute;ximo m&ecirc;s">
                                                    <i class="fa fa-arrow-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <hr>
                                        
                                        <div class="nav-tabs-custom">';

                                        $nav .= '<ul class="nav nav-tabs employees">';
                                        $tab .= '<div class="tab-content">';

                                            while($lin = $sql->fetch(PDO::FETCH_OBJ)) {
                                                if($i == 1) {
                                                    $funcionario = explode(' ', $lin->funcionario);
                                                    $nav .= '<li class="active"><a class="tt" title="'.$lin->funcionario.'" href="#tab_'.$i.'" data-toggle="tab">'.$funcionario[0].'</a></li>';
                                                    $tab .= '<div class="tab-pane active" id="tab_'.$i.'">';

                                                    $sql2 = $pdo->prepare("SELECT tipo,dia,mes,ano,hora FROM registro WHERE login_idlogin = :idlogin AND mes = :mes AND ano = :ano AND hora <> :hora ORDER BY dia, hora");
                                                    $sql2->bindParam(':idlogin', $lin->idlogin, PDO::PARAM_INT);
                                                    $sql2->bindParam(':mes', $mes, PDO::PARAM_STR);
                                                    $sql2->bindParam(':ano', $ano, PDO::PARAM_STR);
                                                    $sql2->bindParam(':hora', $pathora, PDO::PARAM_STR);
                                                    $res2 = $sql2->execute();
                                                    $ret2 = $sql2->rowCount();

                                                        if($ret2 > 0) {
                                                            $tab .= '
                                                            <table class="table table-border table-striped table-hover">
                                                                <thead>
                                                                    <tr>
                                                                        <th><a class="tt" data-toggle="modal" data-target="#add-registro" title="Adicionar registro" href="addRegistro.php?idlogin='.$lin->idlogin.'&mes='.$mes.'&ano='.date('Y').'&tab='.$i.'"><i class="fa fa-plus"></i></a></th>
                                                                        <th>Dia</th>
                                                                        <th><span class="hidden-xs">In&iacute;cio do expediente</span><span class="hidden-sm hidden-md hidden-lg">In&iacute;cio</span></th>
                                                                        <th><span class="hidden-xs">Sa&iacute;da para o almo&ccedil;o</span><span class="hidden-sm hidden-md hidden-lg">Sa&iacute;da</span></th>
                                                                        <th><span class="hidden-xs">Chegada do almo&ccedil;o</span><span class="hidden-sm hidden-md hidden-lg">Volta</span></th>
                                                                        <th><span class="hidden-xs">Fim do expediente</span><span class="hidden-sm hidden-md hidden-lg">Fim</span></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>';

                                                            while($lin2 = $sql2->fetch(PDO::FETCH_OBJ)) {
                                                                $hora = substr($lin2->hora,0,5);

                                                                    if(isset($dia)) {
                                                                        if($dia == $lin2->dia) {
                                                                            $tab .= '<td>'.$hora.' h</td>';
                                                                        } else {
                                                                            switch($lin2->tipo) {
                                                                                case 'PHM':
                                                                                    $tab .= '
                                                                                    <tr>
                                                                                        <td>
                                                                                            <a class="tt" data-toggle="modal" data-target="#edita-registro" title="Editar registro" href="editaRegistro.php?idlogin='.$lin->idlogin.'&dia='.$lin2->dia.'&mes='.$lin2->mes.'&ano='.$lin2->ano.'&tab='.$i.'">
                                                                                                <i class="fa fa-edit"></i>
                                                                                            </a>
                                                                                        </td>
                                                                                        <td>'.$lin2->dia.' - '.diaSemana($ano.'-'.$mes.'-'.$lin2->dia).'</td>
                                                                                        <td>'.$hora.' h</td>';
                                                                                    break;
                                                                                case 'SHM':
                                                                                    $tab .= '
                                                                                    <tr>
                                                                                        <td>
                                                                                            <a class="tt" data-toggle="modal" data-target="#edita-registro" title="Editar registro" href="editaRegistro.php?idlogin='.$lin->idlogin.'&dia='.$lin2->dia.'&mes='.$lin2->mes.'&ano='.$lin2->ano.'&tab='.$i.'">
                                                                                                <i class="fa fa-edit"></i>
                                                                                            </a>
                                                                                        </td>
                                                                                        <td>'.$lin2->dia.' - '.diaSemana($ano.'-'.$mes.'-'.$lin2->dia).'</td>
                                                                                        <td></td>
                                                                                        <td>'.$hora.' h</td>';
                                                                                    break;
                                                                                case 'PHT':
                                                                                    $tab .= '
                                                                                    <tr>
                                                                                        <td>
                                                                                            <a class="tt" data-toggle="modal" data-target="#edita-registro" title="Editar registro" href="editaRegistro.php?idlogin='.$lin->idlogin.'&dia='.$lin2->dia.'&mes='.$lin2->mes.'&ano='.$lin2->ano.'&tab='.$i.'">
                                                                                                <i class="fa fa-edit"></i>
                                                                                            </a>
                                                                                        </td>
                                                                                        <td>'.$lin2->dia.' - '.diaSemana($ano.'-'.$mes.'-'.$lin2->dia).'</td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td>'.$hora.' h</td>';
                                                                                    break;
                                                                                case 'SHT':
                                                                                    $tab .= '
                                                                                    <tr>
                                                                                        <td>
                                                                                            <a class="tt" data-toggle="modal" data-target="#edita-registro" title="Editar registro" href="editaRegistro.php?idlogin='.$lin->idlogin.'&dia='.$lin2->dia.'&mes='.$lin2->mes.'&ano='.$lin2->ano.'&tab='.$i.'">
                                                                                                <i class="fa fa-edit"></i>
                                                                                            </a>
                                                                                        </td>
                                                                                        <td>'.$lin2->dia.' - '.diaSemana($ano.'-'.$mes.'-'.$lin2->dia).'</td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td>'.$hora.' h</td>';
                                                                                    break;
                                                                            } //switch
                                                                        } // else
                                                                    } else {
                                                                        switch ($lin2->tipo) {
                                                                            case 'PHM':
                                                                                $tab .= '
                                                                                <tr>
                                                                                    <td>
                                                                                        <a class="tt" data-toggle="modal" data-target="#edita-registro" title="Editar registro" href="editaRegistro.php?idlogin='.$lin->idlogin.'&dia='.$lin2->dia.'&mes='.$lin2->mes.'&ano='.$lin2->ano.'&tab='.$i.'">
                                                                                            <i class="fa fa-edit"></i>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td>'.$lin2->dia.' - '.diaSemana($ano.'-'.$mes.'-'.$lin2->dia).'</td>
                                                                                    <td>'.$hora.' h</td>';
                                                                                break;
                                                                            case 'SHM':
                                                                                $tab .= '
                                                                                <tr>
                                                                                    <td>
                                                                                        <a class="tt" data-toggle="modal" data-target="#edita-registro" title="Editar registro" href="editaRegistro.php?idlogin='.$lin->idlogin.'&dia='.$lin2->dia.'&mes='.$lin2->mes.'&ano='.$lin2->ano.'&tab='.$i.'">
                                                                                            <i class="fa fa-edit"></i>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td>'.$lin2->dia.' - '.diaSemana($ano.'-'.$mes.'-'.$lin2->dia).'</td>
                                                                                    <td></td>
                                                                                    <td>'.$hora.' h</td>';
                                                                                break;
                                                                            case 'PHT':
                                                                                $tab .= '
                                                                                <tr>
                                                                                    <td>
                                                                                        <a class="tt" data-toggle="modal" data-target="#edita-registro" title="Editar registro" href="editaRegistro.php?idlogin='.$lin->idlogin.'&dia='.$lin2->dia.'&mes='.$lin2->mes.'&ano='.$lin2->ano.'&tab='.$i.'">
                                                                                            <i class="fa fa-edit"></i>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td>'.$lin2->dia.' - '.diaSemana($ano.'-'.$mes.'-'.$lin2->dia).'</td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td>'.$hora.' h</td>';
                                                                                break;
                                                                            case 'SHT':
                                                                                $tab .= '
                                                                                <tr>
                                                                                    <td>
                                                                                        <a class="tt" data-toggle="modal" data-target="#edita-registro" title="Editar registro" href="editaRegistro.php?idlogin='.$lin->idlogin.'&dia='.$lin2->dia.'&mes='.$lin2->mes.'&ano='.$lin2->ano.'&tab='.$i.'">
                                                                                            <i class="fa fa-edit"></i>
                                                                                        </a>
                                                                                    </td>
                                                                                    <td>'.$lin2->dia.' - '.diaSemana($ano.'-'.$mes.'-'.$lin2->dia).'</td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td>'.$hora.' h</td>';
                                                                                break;
                                                                        } //switch
                                                                    } //else

                                                                $dia = $lin2->dia;
                                                            } //while

                                                            $tab .= '
                                                                        </tr>
                                                                    </tbody>
                                                                </table>';

                                                                $sql3 = $pdo->prepare("SELECT texto,mes,ano FROM nota WHERE login_idlogin = :idlogin AND mes = :mes AND ano = :ano");
                                                                $sql3->bindParam(':idlogin', $lin->idlogin, PDO::PARAM_INT);
                                                                $sql3->bindParam(':mes', $mes, PDO::PARAM_STR);
                                                                $sql3->bindParam(':ano', $ano, PDO::PARAM_STR);
                                                                $res3 = $sql3->execute();
                                                                $ret3 = $sql3->rowCount();
    
                                                                    if($ret3 > 0) {
                                                                        $lin3 = $sql3->fetch(PDO::FETCH_OBJ);
    
                                                                        $tab .= '
                                                                        <form class="form-log-'.$lin->idlogin.'">
                                                                            <input type="hidden" id="idlogin-log-'.$lin->idlogin.'" value="'.$lin->idlogin.'">
                                                                            <input type="hidden" id="mes-log-'.$lin->idlogin.'" value="'.$lin3->mes.'">
                                                                            <input type="hidden" id="ano-log-'.$lin->idlogin.'" value="'.$lin3->ano.'">
    
                                                                            <div class="form-group">
                                                                                <label for="log"><i class="fa fa-asterisk"></i> Observa&ccedil;&atilde;o</label>
                                                                                <div class="input-group col-xs-12 col-sm-12 col-md-12">
                                                                                    <textarea id="log-'.$lin->idlogin.'" rows="5" class="form-control" placeholder="dd/mm/aaaa -> Lorem ipsum dolor iamet;" required>'.$lin3->texto.'</textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <button type="submit" class="btn btn-primary btn-flat pull-right btn-submit-log">Salvar</button>
                                                                            </div>
                                                                        </form>';
                                                                    } else {
                                                                        $tab .= '
                                                                        <form class="form-log-'.$lin->idlogin.'">
                                                                            <input type="hidden" id="idlogin-log-'.$lin->idlogin.'" value="'.$lin->idlogin.'">
                                                                            <input type="hidden" id="mes-log-'.$lin->idlogin.'" value="'.$mes.'">
                                                                            <input type="hidden" id="ano-log-'.$lin->idlogin.'" value="'.$ano.'">
    
                                                                            <div class="form-group">
                                                                                <label for="log"><i class="fa fa-asterisk"></i> Observa&ccedil;&atilde;o</label>
                                                                                <div class="input-group col-xs-12 col-sm-12 col-md-12">
                                                                                    <textarea id="log-'.$lin->idlogin.'" rows="5" class="form-control" placeholder="dd/mm/aaaa -> Lorem ipsum dolor iamet;" required></textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <button type="submit" class="btn btn-primary btn-flat pull-right btn-submit-log">Salvar observa&ccedil;&atilde;o</button>
                                                                            </div>
                                                                        </form>';
                                                                    }
    
                                                                $sql3->closeCursor();

                                                            $tab .= '
                                                                <a class="btn btn-success btn-flat" href="closeRegistro.php?'.$pylogin.'='.$lin->idlogin.'&'.$pynome.'='.urlencode($lin->funcionario).'&'.$pyano.'='.$ano.'&'.$getmes.'='.$mes.'" data-toggle="modal" data-target="#close-month">Fechar o m&ecirc;s</a>
                                                                <a class="btn btn-default btn-flat" href="printRegistro.php?'.$pylogin.'='.$lin->idlogin.'&'.$pynome.'='.urlencode($lin->funcionario).'&'.$pyano.'='.$ano.'&'.$getmes.'='.$mes.'">Imprimir os registros</a>
                                                            </div><!-- tab-pane -->';
                                                            
                                                            $script .= '
                                                            /* LOG */

                                                            $(".form-log-'.$lin->idlogin.'").submit(function (e) {
                                                                e.preventDefault();

                                                                $.post("manageNota.php", { idlogin: $("#idlogin-log-'.$lin->idlogin.'").val(), mes: $("#mes-log-'.$lin->idlogin.'").val(), ano: $("#ano-log-'.$lin->idlogin.'").val(), texto: $("#log-'.$lin->idlogin.'").val(), rand: Math.random()}, function (data) {
                                                                    $(".btn-submit-log").html(\'<img src="../img/rings.svg" class="loader-svg">\').fadeTo(150, 1);

                                                                    switch (data) {
                                                                    case "true":
                                                                        $.smkAlert({text: "Observa&ccedil;&atilde;o registrada com sucesso.", type: "success", time: 2});
                                                                        //$(".form-novo-usuario")[0].reset();
                                                                        break;

                                                                    default:
                                                                        $.smkAlert({text: data, type: "warning", time: 3});
                                                                        break;
                                                                    }

                                                                    $(".btn-submit-log").html("Salvar").fadeTo(150, 1);
                                                                });

                                                                return false;
                                                            });';

                                                            unset($dia);
                                                        } //if $ret
                                                } //if $i
                                                else {
                                                    if ($funcionario != $lin->funcionario) {
                                                        $funcionario = explode(' ', $lin->funcionario);
                                                        $nav .= '<li><a class="tt" title="'.$lin->funcionario.'" href="#tab_'.$i.'" data-toggle="tab">'.$funcionario[0].'</a></li>';
                                                        $tab .= '<div class="tab-pane" id="tab_'.$i.'">';

                                                        $sql2 = $pdo->prepare("SELECT tipo,dia,mes,ano,hora FROM registro WHERE login_idlogin = :idlogin AND mes = :mes AND ano = :ano AND hora <> :hora ORDER BY dia, hora");
                                                        $sql2->bindParam(':idlogin', $lin->idlogin, PDO::PARAM_INT);
                                                        $sql2->bindParam(':mes', $mes, PDO::PARAM_STR);
                                                        $sql2->bindParam(':ano', $ano, PDO::PARAM_STR);
                                                        $sql2->bindParam(':hora', $pathora, PDO::PARAM_STR);
                                                        $res2 = $sql2->execute();
                                                        $ret2 = $sql2->rowCount();

                                                            if($ret2 > 0) {
                                                                //$tab = '';
                                                                $tab .= '
                                                                <table class="table table-border table-striped table-hover">
                                                                    <thead>
                                                                        <tr>
                                                                            <th><a class="tt" data-toggle="modal" data-target="#add-registro" title="Adicionar registro" href="addRegistro.php?idlogin='.$lin->idlogin.'&mes='.$mes.'&ano='.date('Y').'&tab='.$i.'"><i class="fa fa-plus"></i></a></th>
                                                                            <th>Dia</th>
                                                                            <th>Come&ccedil;o do expediente</th>
                                                                            <th>Sa&iacute;da para o almo&ccedil;o</th>
                                                                            <th>Chegada do almo&ccedil;o</th>
                                                                            <th>Fim do expediente</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>';

                                                                while($lin2 = $sql2->fetch(PDO::FETCH_OBJ)) {
                                                                    $hora = substr($lin2->hora,0,5);

                                                                        if(isset($dia)) {
                                                                            if($dia == $lin2->dia) {
                                                                                if($tipo == 'PHM' and $lin2->tipo == 'SHM') {
                                                                                    $tab .= '<td>'.$hora.' h</td>';
                                                                                    $tipo = $lin2->tipo;
                                                                                } elseif($tipo == 'SHM' and $lin2->tipo == 'PHT') {
                                                                                    $tab .= '<td>'.$hora.' h</td>';
                                                                                    $tipo = $lin2->tipo;
                                                                                } elseif($tipo == 'PHT' and $lin2->tipo == 'SHT') {
                                                                                    $tab .= '<td>'.$hora.' h</td>';
                                                                                    $tipo = $lin2->tipo;
                                                                                } else {
                                                                                    $tab .= '<td>'.$hora.' h</td>';
                                                                                    $tipo = 'PHM';
                                                                                }
                                                                                /*if($tipo == 'PHM' and $lin2->tipo == 'PHT') {
                                                                                    #$tab .= '<td></td><td>'.$hora.' h</td>';
                                                                                    $tab .= '<td>'.$hora.' h</td>';
                                                                                    $tipo = $lin2->tipo;
                                                                                } elseif ($tipo == 'PHM' and $lin2->tipo == 'SHT') {
                                                                                    $tab .= '<td></td><td></td><td>'.$hora.' h</td>';
                                                                                } elseif ($tipo == 'SHM' and $lin2->tipo == 'SHT') {
                                                                                    #$tab .= '<td></td><td>'.$hora.' h</td>';
                                                                                    $tab .= '<td>'.$hora.' h</td>';
                                                                                } else {
                                                                                    $tab .= '<td>'.$hora.' h</td>';
                                                                                    $tipo = $lin->tipo;
                                                                                }*/
                                                                            } else {
                                                                                switch ($lin2->tipo) {
                                                                                    case 'PHM':
                                                                                        $tab .= '
                                                                                        <tr>
                                                                                            <td>
                                                                                                <a class="tt" data-toggle="modal" data-target="#edita-registro" title="Editar registro" href="editaRegistro.php?idlogin='.$lin->idlogin.'&dia='.$lin2->dia.'&mes='.$lin2->mes.'&ano='.$lin2->ano.'&tab='.$i.'">
                                                                                                    <i class="fa fa-edit"></i>
                                                                                                </a>
                                                                                            </td>
                                                                                            <td>'.$lin2->dia.' - '.diaSemana($ano.'-'.$mes.'-'.$lin2->dia).'</td>
                                                                                            <td>'.$hora.' h</td>';
                                                                                        $tipo = $lin2->tipo;
                                                                                        break;
                                                                                    case 'SHM':
                                                                                        $tab .= '
                                                                                        <tr>
                                                                                            <td>
                                                                                                <a class="tt" data-toggle="modal" data-target="#edita-registro" title="Editar registro" href="editaRegistro.php?idlogin='.$lin->idlogin.'&dia='.$lin2->dia.'&mes='.$lin2->mes.'&ano='.$lin2->ano.'&tab='.$i.'">
                                                                                                    <i class="fa fa-edit"></i>
                                                                                                </a>
                                                                                            </td>
                                                                                            <td>'.$lin2->dia.' - '.diaSemana($ano.'-'.$mes.'-'.$lin2->dia).'</td>
                                                                                            <td></td>
                                                                                            <td>'.$hora.' h</td>';
                                                                                        $tipo = $lin2->tipo;
                                                                                        break;
                                                                                    case 'PHT':
                                                                                        $tab .= '
                                                                                        <tr>
                                                                                            <td>
                                                                                                <a class="tt" data-toggle="modal" data-target="#edita-registro" title="Editar registro" href="editaRegistro.php?idlogin='.$lin->idlogin.'&dia='.$lin2->dia.'&mes='.$lin2->mes.'&ano='.$lin2->ano.'&tab='.$i.'">
                                                                                                    <i class="fa fa-edit"></i>
                                                                                                </a>
                                                                                            </td>
                                                                                            <td>'.$lin2->dia.' - '.diaSemana($ano.'-'.$mes.'-'.$lin2->dia).'</td>
                                                                                            <td></td>
                                                                                            <td></td>
                                                                                            <td>'.$hora.' h</td>';
                                                                                        $tipo = $lin2->tipo;
                                                                                        break;
                                                                                    case 'SHT':
                                                                                        $tab .= '
                                                                                        <tr>
                                                                                            <td>
                                                                                                <a class="tt" data-toggle="modal" data-target="#edita-registro" title="Editar registro" href="editaRegistro.php?idlogin='.$lin->idlogin.'&dia='.$lin2->dia.'&mes='.$lin2->mes.'&ano='.$lin2->ano.'&tab='.$i.'">
                                                                                                    <i class="fa fa-edit"></i>
                                                                                                </a>
                                                                                            </td>
                                                                                            <td>'.$lin2->dia.' - '.diaSemana($ano.'-'.$mes.'-'.$lin2->dia).'</td>
                                                                                            <td></td>
                                                                                            <td></td>
                                                                                            <td></td>
                                                                                            <td>'.$hora.' h</td>';
                                                                                        $tipo = $lin2->tipo;
                                                                                        break;
                                                                                }
                                                                            }
                                                                        } else {
                                                                            switch ($lin2->tipo) {
                                                                                case 'PHM':
                                                                                    $tab .= '
                                                                                    <tr>
                                                                                        <td>
                                                                                            <a class="tt" data-toggle="modal" data-target="#edita-registro" title="Editar registro" href="editaRegistro.php?idlogin='.$lin->idlogin.'&dia='.$lin2->dia.'&mes='.$lin2->mes.'&ano='.$lin2->ano.'&tab='.$i.'">
                                                                                                <i class="fa fa-edit"></i>
                                                                                            </a>
                                                                                        </td>
                                                                                        <td>'.$lin2->dia.' - '.diaSemana($ano.'-'.$mes.'-'.$lin2->dia).'</td>
                                                                                        <td>'.$hora.' h</td>';
                                                                                    $tipo = $lin2->tipo;
                                                                                    break;
                                                                                case 'SHM':
                                                                                    $tab .= '
                                                                                    <tr>
                                                                                        <td>
                                                                                            <a class="tt" data-toggle="modal" data-target="#edita-registro" title="Editar registro" href="editaRegistro.php?idlogin='.$lin->idlogin.'&dia='.$lin2->dia.'&mes='.$lin2->mes.'&ano='.$lin2->ano.'&tab='.$i.'">
                                                                                                <i class="fa fa-edit"></i>
                                                                                            </a>
                                                                                        </td>
                                                                                        <td>'.$lin2->dia.' - '.diaSemana($ano.'-'.$mes.'-'.$lin2->dia).'</td>
                                                                                        <td></td>
                                                                                        <td>'.$hora.' h</td>';
                                                                                    $tipo = $lin2->tipo;
                                                                                    break;
                                                                                case 'PHT':
                                                                                    $tab .= '
                                                                                    <tr>
                                                                                        <td>
                                                                                            <a class="tt" data-toggle="modal" data-target="#edita-registro" title="Editar registro" href="editaRegistro.php?idlogin='.$lin->idlogin.'&dia='.$lin2->dia.'&mes='.$lin2->mes.'&ano='.$lin2->ano.'&tab='.$i.'">
                                                                                                <i class="fa fa-edit"></i>
                                                                                            </a>
                                                                                        </td>
                                                                                        <td>'.$lin2->dia.' - '.diaSemana($ano.'-'.$mes.'-'.$lin2->dia).'</td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td>'.$hora.' h</td>';
                                                                                    $tipo = $lin2->tipo;
                                                                                    break;
                                                                                case 'SHT':
                                                                                    $tab .= '
                                                                                    <tr>
                                                                                        <td>
                                                                                            <a class="tt" data-toggle="modal" data-target="#edita-registro" title="Editar registro" href="editaRegistro.php?idlogin='.$lin->idlogin.'&dia='.$lin2->dia.'&mes='.$lin2->mes.'&ano='.$lin2->ano.'&tab='.$i.'">
                                                                                                <i class="fa fa-edit"></i>
                                                                                            </a>
                                                                                        </td>
                                                                                        <td>'.$lin2->dia.' - '.diaSemana($ano.'-'.$mes.'-'.$lin2->dia).'</td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td></td>
                                                                                        <td>'.$hora.' h</td>';
                                                                                    $tipo = $lin2->tipo;
                                                                                    break;
                                                                            } //switch
                                                                        } //else

                                                                    $dia = $lin2->dia;
                                                                } //while

                                                                $tab .= '
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>';

                                                                $sql3 = $pdo->prepare("SELECT texto,mes,ano FROM nota WHERE login_idlogin = :idlogin AND mes = :mes AND ano = :ano");
                                                                $sql3->bindParam(':idlogin', $lin->idlogin, PDO::PARAM_INT);
                                                                $sql3->bindParam(':mes', $mes, PDO::PARAM_STR);
                                                                $sql3->bindParam(':ano', $ano, PDO::PARAM_STR);
                                                                $res3 = $sql3->execute();
                                                                $ret3 = $sql3->rowCount();
    
                                                                    if($ret3 > 0) {
                                                                        $lin3 = $sql3->fetch(PDO::FETCH_OBJ);
    
                                                                        $tab .= '
                                                                        <form class="form-log-'.$lin->idlogin.'">
                                                                            <input type="hidden" id="idlogin-log-'.$lin->idlogin.'" value="'.$lin->idlogin.'">
                                                                            <input type="hidden" id="mes-log-'.$lin->idlogin.'" value="'.$lin3->mes.'">
                                                                            <input type="hidden" id="ano-log-'.$lin->idlogin.'" value="'.$lin3->ano.'">
    
                                                                            <div class="form-group">
                                                                                <label for="log"><i class="fa fa-asterisk"></i> Observa&ccedil;&atilde;o</label>
                                                                                <div class="input-group col-xs-12 col-sm-12 col-md-12">
                                                                                    <textarea id="log-'.$lin->idlogin.'" rows="5" class="form-control" placeholder="dd/mm/aaaa -> Lorem ipsum dolor iamet;" required>'.$lin3->texto.'</textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <button type="submit" class="btn btn-primary btn-flat pull-right btn-submit-log">Salvar observa&ccedil;&atilde;o</button>
                                                                            </div>
                                                                        </form>';
                                                                    } else {
                                                                        $tab .= '
                                                                        <form class="form-log-'.$lin->idlogin.'">
                                                                            <input type="hidden" id="idlogin-log-'.$lin->idlogin.'" value="'.$lin->idlogin.'">
                                                                            <input type="hidden" id="mes-log-'.$lin->idlogin.'" value="'.$mes.'">
                                                                            <input type="hidden" id="ano-log-'.$lin->idlogin.'" value="'.$ano.'">
    
                                                                            <div class="form-group">
                                                                                <label for="log"><i class="fa fa-asterisk"></i> Observa&ccedil;&atilde;o</label>
                                                                                <div class="input-group col-xs-12 col-sm-12 col-md-12">
                                                                                    <textarea id="log-'.$lin->idlogin.'" rows="5" class="form-control" placeholder="dd/mm/aaaa -> Lorem ipsum dolor iamet;" required></textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <button type="submit" class="btn btn-primary btn-flat pull-right btn-submit-log">Salvar observa&ccedil;&atilde;o</button>
                                                                            </div>
                                                                        </form>';
                                                                    }
    
                                                                $sql3->closeCursor();

                                                                $tab .= '
                                                                    <a class="btn btn-success btn-flat" href="closeRegistro.php?'.$pylogin.'='.$lin->idlogin.'&'.$pynome.'='.urlencode($lin->funcionario).'&'.$pyano.'='.$ano.'&'.$getmes.'='.$mes.'" data-toggle="modal" data-target="#close-month">Fechar o m&ecirc;s</a>
                                                                    <a class="btn btn-default btn-flat" href="printRegistro.php?'.$pylogin.'='.$lin->idlogin.'&'.$pynome.'='.urlencode($lin->funcionario).'&'.$pyano.'='.$ano.'&'.$getmes.'='.$mes.'">Imprimir os registros</a>
                                                                </div><!-- tab-pane -->';

                                                                $script .= '
                                                                /* LOG */

                                                                $(".form-log-'.$lin->idlogin.'").submit(function (e) {
                                                                    e.preventDefault();

                                                                    $.post("manageNota.php", { idlogin: $("#idlogin-log-'.$lin->idlogin.'").val(), mes: $("#mes-log-'.$lin->idlogin.'").val(), ano: $("#ano-log-'.$lin->idlogin.'").val(), texto: $("#log-'.$lin->idlogin.'").val(), rand: Math.random()}, function (data) {
                                                                        $(".btn-submit-log").html(\'<img src="../img/rings.svg" class="loader-svg">\').fadeTo(150, 1);

                                                                        switch (data) {
                                                                        case "true":
                                                                            $.smkAlert({text: "Observa&ccedil;&atilde;o registrada com sucesso.", type: "success", time: 2});
                                                                            //$(".form-novo-usuario")[0].reset();
                                                                            break;

                                                                        default:
                                                                            $.smkAlert({text: data, type: "warning", time: 3});
                                                                            break;
                                                                        }

                                                                        $(".btn-submit-log").html("Salvar").fadeTo(150, 1);
                                                                    });

                                                                    return false;
                                                                });';

                                                                unset($dia);
                                                            } //if ret2
                                                    } //if
                                                } //else

                                            $funcionario = $lin->funcionario;
                                            $i++;
                                            } //while

                                        $tab .= '</div><!-- tab-content --></div><!-- tab-custom -->';
                                        $nav .= '</ul>';
                                        echo $nav.$tab;
                                    } //if $ret
                                    else {
                                        $script = '';
                                        
                                        echo'
                                        <div class="div-time">
                                            <div class="div-time-left text-center">
                                                <a class="lead" href="index.php?'.$getmes.'='.$mesleft.'&'.$getano.'='.$ano.'&left=1" title="M&ecirc;s anterior">
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
                                                <a class="lead" href="index.php?'.$getmes.'='.$mesright.'&'.$getano.'='.$ano.'&right=1" title="Pr&oacute;ximo m&ecirc;s">
                                                    <i class="fa fa-arrow-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <hr>
                                        
                                        <h4 class="text-center">Nenhum registro encontrado para o m&ecirc;s de '.mes_extenso($mes).'</h4>';
                                    }
                            } //try
                            catch(PDOException $e) {
                                echo'<span>Erro ao conectar o servidor: '.$e->getMessage().'</span>';
                            }
                        ?>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->

            <!-- modal -->
            <div class="modal fade" id="add-registro" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content"></div>
                </div>
            </div>

            <div class="modal fade" id="edita-registro" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content"></div>
                </div>
            </div>

            <div class="modal fade" id="close-month" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content"></div>
                </div>
            </div>
            <!-- modal -->

            <!-- Main Footer -->
            <footer class="main-footer"><?php include_once('../footer.php'); ?></footer>
        </div><!-- ./wrapper -->

        <script src="../js/jquery-2.1.4.min.js"></script>
        <script defer src="../js/bootstrap.min.js"></script>
        <script async src="../js/smoke.min.js"></script>
        <script src="../js/bootstrap-datepicker.min.js"></script>
        <script src="../js/bootstrap-datepicker.pt-BR.min.js"></script>
        <script async src="../js/core.min.js"></script>
        <script>
            $(document).ready(function () {
                <?php echo $script; ?>

                /* DATEPICKER */

                $(".date-pick").datepicker({
                    language: 'pt-BR',
                    format: "mm yyyy",
                    startView: 1,
                    minViewMode: 1
                }).on('hide', function(e) {
                    var dt = e.target.value.split(' ');
                    location.href = "index.php?<?php echo $getmes; ?>=" + dt[0] + "&<?php echo $getano; ?>=" + dt[1] + "&pick=1";
                });

                // Active TAB

                $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
		            localStorage.setItem('activeTab', $(e.target).attr('href'));
                });
                
                var activeTab = localStorage.getItem('activeTab');
                
                    if(activeTab){
                        $('.employees a[href="' + activeTab + '"]').tab('show');
                    }
            });
        </script>
    </body>
</html>
<?php unset($cfg,$adm,$admactive,$m,$pdo,$e,$hora,$data,$registra_ponto,$sql,$res,$ret,$lin,$mes,$ano,$mes_extenso,$fmes,$nav,$cnt,$i,$pylogin,$pynome,$geturl,$funcionario); ?>
