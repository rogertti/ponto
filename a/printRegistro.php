<?php
    require_once('../config.php');

        if(empty($_SESSION['key'])) {
            header ('location:../');
        }

    $m = 1;
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
        <link rel="stylesheet" href="../css/smoke.min.css">
        <link rel="stylesheet" href="../css/layout.min.css">
        <link rel="stylesheet" href="../css/core.min.css">
        <style media="print">
            * {
                overflow: visible !important;
            }
            
            body {
                font-size: 1em;
            }

            h2.page-header {
                font-size: 2em;
            }

            .table>thead>tr>th {
                padding: 2px !important;;
            }

           .table>tbody>tr>td {
                padding: 2px !important;;
            }

            .table>tfoot>tr>th {
                padding: 2px !important;;
            }

            .table>tfoot>tr>td {
                padding: 2px !important;;
            }
        </style>
        <!--[if lt IE 9]><script src="../js/html5shiv.min.js"></script><script src="../js/respond.min.js"></script><![endif]-->
    </head>
    <body>
        <!-- Main content -->
        <section class="invoice">
            <?php
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

                try {
                    include_once('../conexao.php');

                    $pylogin = md5('idlogin');
                    $pynome = md5('nome');
                    $pymes = md5('mes');
                    $pyano = md5('ano');
                    $pathora = '00:00:00';
                    #$mes = date('m');
                    $mes = $_GET[''.$pymes.''];
                    $ano = $_GET[''.$pyano.''];

                    #$sql = $pdo->prepare("SELECT login.log,registro.tipo,registro.dia,registro.hora FROM login,registro WHERE registro.login_idlogin = login.idlogin AND login.idlogin = :idlogin AND registro.mes = :mes AND registro.ano = :ano AND registro.hora <> :hora ORDER BY registro.dia,registro.hora");
                    $sql = $pdo->prepare("SELECT registro.tipo,registro.dia,registro.hora,nota.texto AS log FROM registro INNER JOIN nota ON nota.login_idlogin = registro.login_idlogin INNER JOIN login ON registro.login_idlogin = login.idlogin WHERE nota.login_idlogin = :idlogin AND nota.mes = :mes AND nota.ano = :ano AND login.idlogin = :idlogin AND registro.mes = :mes AND registro.ano = :ano AND registro.hora <> :hora ORDER BY registro.dia,registro.hora");
                    $sql->bindParam(':idlogin', $_GET[''.$pylogin.''], PDO::PARAM_INT);
                    $sql->bindParam(':mes', $mes, PDO::PARAM_STR);
                    $sql->bindParam(':ano', $ano, PDO::PARAM_STR);
                    $sql->bindParam(':hora', $pathora, PDO::PARAM_STR);
                    $res = $sql->execute();
                    $ret = $sql->rowCount();

                        if($ret > 0) {
                            $hm = '';

                            echo'
                            <div class="row">
                                <div class="col-xs-12">
                                    <h2 class="page-header">'.$_GET[''.$pynome.''].' - '.mes_extenso($mes).' de '.$ano.'</h2>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 table-responsive">
                                    <table class="table table-border table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Dia</th>
                                                    <th>Come&ccedil;o do expediente</th>
                                                    <th>Sa&iacute;da para o almo&ccedil;o</th>
                                                    <th>Chegada do almo&ccedil;o</th>
                                                    <th>Fim do expediente</th>
                                                </tr>
                                            </thead>
                                            <tbody>';

                                while($lin = $sql->fetch(PDO::FETCH_OBJ)) {
                                    //Suprime os segundos
                                    $hora = substr($lin->hora,0,5);

                                        if(isset($dia)) {
                                            if($dia == $lin->dia) {
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
                                                /*if($tipo == 'PHM' and $lin->tipo == 'PHT') {
                                                    //$hm .= '<td></td><td>'.$hora.' h</td>';
                                                    $hm .= '<td>'.$hora.' h</td>';
                                                    $tipo = $lin->tipo;
                                                } elseif ($tipo == 'PHM' and $lin->tipo == 'SHT') {
                                                    $hm .= '<td></td><td></td><td>'.$hora.' h</td>';
                                                } elseif ($tipo == 'SHM' and $lin->tipo == 'SHT') {
                                                    //$hm .= '<td></td><td>'.$hora.' h</td>';
                                                    $hm .= '<td>'.$hora.' h</td>';
                                                } else {
                                                    $hm .= '<td>'.$hora.' h</td>';
                                                    $tipo = $lin->tipo;
                                                }*/
                                            } else {
                                                switch ($lin->tipo) {
                                                    case 'PHM':
                                                        $hm .= '
                                                        <tr>
                                                            <td>'.$lin->dia.'</td>
                                                            <td>'.$hora.' h</td>';
                                                        $tipo = $lin->tipo;
                                                        break;
                                                    case 'SHM':
                                                        $hm .= '
                                                        <tr>
                                                            <td>'.$lin->dia.'</td>
                                                            <td></td>
                                                            <td>'.$hora.' h</td>';
                                                        $tipo = $lin->tipo;
                                                        break;
                                                    case 'PHT':
                                                        $hm .= '
                                                        <tr>
                                                            <td>'.$lin->dia.'</td>
                                                            <td></td>
                                                            <td></td>
                                                            <td>'.$hora.' h</td>';
                                                        $tipo = $lin->tipo;
                                                        break;
                                                    case 'SHT':
                                                        $hm .= '
                                                        <tr>
                                                            <td>'.$lin->dia.'</td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td>'.$hora.' h</td>';
                                                        $tipo = $lin->tipo;
                                                        break;
                                                }
                                            }
                                        } else {
                                            switch ($lin->tipo) {
                                                case 'PHM':
                                                    $hm .= '
                                                    <tr>
                                                        <td>'.$lin->dia.'</td>
                                                        <td>'.$hora.' h</td>';
                                                    $tipo = $lin->tipo;
                                                    break;
                                                case 'SHM':
                                                    $hm .= '
                                                    <tr>
                                                        <td>'.$lin->dia.'</td>
                                                        <td></td>
                                                        <td>'.$hora.' h</td>';
                                                    $tipo = $lin->tipo;
                                                    break;
                                                case 'PHT':
                                                    $hm .= '
                                                    <tr>
                                                        <td>'.$lin->dia.'</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td>'.$hora.' h</td>';
                                                    $tipo = $lin->tipo;
                                                    break;
                                                case 'SHT':
                                                    $hm .= '
                                                    <tr>
                                                        <td>'.$lin->dia.'</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td>'.$hora.' h</td>';
                                                    $tipo = $lin->tipo;
                                                    break;
                                            }
                                        }

                                    $dia = $lin->dia;
                                    $log = $lin->log;
                                }

                            echo $hm.'</tr>';

                            echo'
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 pre-log"><pre>'.$log.'</pre></div>
                            </div>';
                        } //$ret
                }
                catch(PDOException $e) {
                    echo'<span>Erro ao conectar o servidor: '.$e->getMessage().'</span>';
                }
            ?>
        </section>

        <script src="../js/jquery-2.1.4.min.js"></script>
        <script>
            $(function() {
                /* PRINT */

                <?php if(!empty($ret)) { ?>
                print();

                $(window).mouseleave(function() {
                    location.href = "<?php echo $_SESSION['geturl']; ?>";
                });      
                <?php } ?>
            });
        </script>
    </body>
</html>
<?php unset($cfg,$m,$e,$pdo,$fmes,$pylogin,$pynome,$sql,$res,$ret,$lin,$ano,$mes,$hora); ?>