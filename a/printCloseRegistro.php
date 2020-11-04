<?php
    require_once('../config.php');

        if(empty($_SESSION['key'])) {
            header ('location:../');
        }

    $m = 1;
    $pylogin = md5('idlogin');
    $pynome = md5('nome');
    $pymes = md5('mes');
    $pyano = md5('ano');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <title><?php echo $cfg['titulo']; ?></title>
        <link rel="icon" type="image/png" href="img/favicon.png">
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/font-awesome.min.css">
        <link rel="stylesheet" href="../css/ionicons.min.css">
        <link rel="stylesheet" href="../css/smoke.min.css">
        <link rel="stylesheet" href="../css/layout.min.css">
        <link rel="stylesheet" href="../css/core.min.css">
        <!--[if lt IE 9]><script src="../js/html5shiv.min.js"></script><script src="../js/respond.min.js"></script><![endif]-->
    </head>
    <body>
        <!-- Main content -->
        <section class="invoice">
            <?php
                function diaSemana($datado) {
                    $diasemana = array('Domingo', 'Segunda', 'Ter&ccedil;a', 'Quarta', 'Quinta', 'Sexta', 'S&aacute;bado');
                    #$data = date('Y-m-d');
                    // Varivel que recebe o dia da semana (0 = Domingo, 1 = Segunda ...)
                    $diasemana_numero = date('w', strtotime($datado));
                    
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
                                        #return $feriado->date;
                                        return true;
                                    }
                            }
                        }
                }

                //Função que calcula o excedente ou faltante às 8 horas
                function diffTimeMinus($entrada, $saida) {
                    $hora1 = explode(":", $entrada);
                    $hora2 = explode(":", $saida);
                    
                    $acumulador1 = ($hora1[0] * 3600) + ($hora1[1] * 60) + $hora1[2];
                    $acumulador2 = ($hora2[0] * 3600) + ($hora2[1] * 60) + $hora2[2];
                    $resultado = $acumulador2 - $acumulador1;
            
                    $hora_ponto = floor($resultado / 3600);
                    $resultado = $resultado - ($hora_ponto * 3600);
            
                    $min_ponto = floor($resultado / 60);
                    $resultado = $resultado - ($min_ponto * 60);
            
                    $secs_ponto = $resultado;
                    $tempo = $hora_ponto.":".$min_ponto.":".$secs_ponto;
            
                    return '- '.$tempo;
                }

                //Função que calcula o excedente ou faltante às 8 horas
                function diffTime($entrada, $saida) {
                    $hora1 = explode(":", $entrada);
                    $hora2 = explode(":", $saida);
                    
                    $acumulador1 = ($hora1[0] * 3600) + ($hora1[1] * 60) + $hora1[2];
                    $acumulador2 = ($hora2[0] * 3600) + ($hora2[1] * 60) + $hora2[2];
                    $resultado = $acumulador2 - $acumulador1;
            
                    $hora_ponto = floor($resultado / 3600);
                    $resultado = $resultado - ($hora_ponto * 3600);
            
                    $min_ponto = floor($resultado / 60);
                    $resultado = $resultado - ($min_ponto * 60);
            
                    $secs_ponto = $resultado;
                    $tempo = $hora_ponto.":".$min_ponto.":".$secs_ponto;
            
                    return '+ '.$tempo;
                }

                //Função que calcula o excedente ou faltante às 8 horas
                function diffTimeFinal($entrada, $saida) {
                    $hora1 = explode(":", $entrada);
                    $hora2 = explode(":", $saida);
                    
                    $acumulador1 = ($hora1[0] * 3600) + ($hora1[1] * 60) + $hora1[2];
                    $acumulador2 = ($hora2[0] * 3600) + ($hora2[1] * 60) + $hora2[2];
                    $resultado = $acumulador2 - $acumulador1;
            
                    $hora_ponto = floor($resultado / 3600);
                    $resultado = $resultado - ($hora_ponto * 3600);
            
                    $min_ponto = floor($resultado / 60);
                    $resultado = $resultado - ($min_ponto * 60);
            
                    $secs_ponto = $resultado;

                        if(strlen($hora_ponto) == 1) {
                            $hora_ponto = '0'.$hora_ponto;
                        }

                        if(strlen($min_ponto) == 1) {
                            $min_ponto = '0'.$min_ponto;
                        }

                    #$tempo = $hora_ponto.":".$min_ponto.":".$secs_ponto;
                    #$tempo = $hora_ponto.":".$min_ponto;
            
                    return $hora_ponto.' horas e '.$min_ponto.' minutos';
                }

                //Função que calcula as horas trabalhadas do funcionário
                function workTime($value) {
                    $workday = explode(',', $value);
                    $datado = explode(' ', $workday[0]);
                    
                    //invertendo a data
                    $anoi = substr($datado[0],0,4);
                    $mesi = substr($datado[0],5,2);
                    $diai = substr($datado[0],8);
                    $datado[0] = $diai."/".$mesi."/".$anoi;
                    $datadoi = $anoi.'-'.$mesi.'-'.$diai;
                    $diasemana = diaSemana($datadoi);

                    $entrada = new DateTime($workday[0]);
                    $saida = new DateTime($workday[3]);
                    $diff_trabalhada = $entrada->diff($saida);
                    $horas_totais = ($diff_trabalhada->h * 60) + $diff_trabalhada->i;

                    $saida_intervalo = new DateTime($workday[1]);
                    $volta_intervalo = new DateTime($workday[2]);
                    $diff_intervalo = $saida_intervalo->diff($volta_intervalo);
                    $horas_intervalo = ($diff_intervalo->h * 60) + $diff_intervalo->i;

                    //subtraindo essas horas totais do intervalo temos a quantidade de horas trabalhadas
                    $horas_trabalhadas = $horas_totais - $horas_intervalo;
                    $horas = (int)($horas_trabalhadas / 60); //horas inteiras trabalhadas
                    $minutos = $horas_trabalhadas % 60; //minutos trabalhados

                        if(strlen($horas) == 1) {
                            $horas = '0'.$horas;
                        }

                        if(strlen($minutos) == 1) {
                            $minutos = '0'.$minutos;
                        }

                    $total_horas = $horas.':'.$minutos.':00';
                    $total_horas_out = str_replace(':', '', $total_horas);

                    echo '<tr><td>'.$datado[0].'</td><td>'.$diasemana.'</td><td>'.$total_horas.'</td></tr>';

                        if($total_horas_out < '80000') {
                            return diffTimeMinus($total_horas, '08:00:00');
                        } else {
                            return diffTime('08:00:00', $total_horas);
                        }

                    #return true;
                }

                //Função que calcula as horas trabalhadas do funcionário
                function workTimeSat($value) {
                    $workday = explode(',', $value);
                    $datado = explode(' ', $workday[0]);

                    //invertendo a data
                    $anoi = substr($datado[0],0,4);
                    $mesi = substr($datado[0],5,2);
                    $diai = substr($datado[0],8);
                    $datado[0] = $diai."/".$mesi."/".$anoi;
                    $datadoi = $anoi.'-'.$mesi.'-'.$diai;
                    $diasemana = diaSemana($datadoi);

                    $entrada = new DateTime($workday[0]);
                    $saida = new DateTime($workday[1]);
                    $diff_trabalhada = $entrada->diff($saida);
                    $horas_totais = ($diff_trabalhada->h * 60) + $diff_trabalhada->i;

                    //subtraindo essas horas totais do intervalo temos a quantidade de horas trabalhadas
                    $horas = (int)($horas_totais / 60); //horas inteiras trabalhadas
                    $minutos = $horas_totais % 60; //minutos trabalhados

                        if(strlen($horas) == 1) {
                            $horas = '0'.$horas;
                        }

                        if(strlen($minutos) == 1) {
                            $minutos = '0'.$minutos;
                        }

                    $total_horas = $horas.':'.$minutos.':00';
                    $total_horas_out = str_replace(':', '', $total_horas);

                    echo '<tr><td>'.$datado[0].'</td><td>'.$diasemana.'</td><td>'.$total_horas.'</td></tr>';

                        if($total_horas < '40000') {
                            return diffTimeMinus($total_horas, '04:00:00');
                        } else {
                            return diffTime('04:00:00', $total_horas);
                        }

                    #return true;
                }

                //Função que calcula as horas trabalhadas do funcionário
                function workTimeAlt($workday) {
                    #$workday = explode(' ', $value);
                    $datado = explode(' ', $workday);
                    
                    //invertendo a data
                    $anoi = substr($datado[0],0,4);
                    $mesi = substr($datado[0],5,2);
                    $diai = substr($datado[0],8);
                    $datado = $diai."/".$mesi."/".$anoi;
                    $datadoi = $anoi.'-'.$mesi.'-'.$diai;
                    #echo $datado.' '.$datadoi; exit;
                    $diasemana = diaSemana($datadoi);
                    #echo $diasemana; exit;

                    $entrada = new DateTime($workday);
                    $saida = new DateTime($workday);
                    $diff_trabalhada = $entrada->diff($saida);
                    $horas_totais = ($diff_trabalhada->h * 60) + $diff_trabalhada->i;
                    #echo $horas_totais; exit;

                    //subtraindo essas horas totais do intervalo temos a quantidade de horas trabalhadas
                    $horas = (int)($horas_totais / 60); //horas inteiras trabalhadas
                    $minutos = $horas_totais % 60; //minutos trabalhados

                        if(strlen($horas) == 1) {
                            $horas = '0'.$horas;
                        }

                        if(strlen($minutos) == 1) {
                            $minutos = '0'.$minutos;
                        }

                    $total_horas = $horas.':'.$minutos.':00';
                    $total_horas_out = str_replace(':', '', $total_horas);

                    echo '<tr><td>'.$datado.'</td><td>'.$diasemana.'</td><td>'.$total_horas.'</td></tr>';

                        if($total_horas < '40000') {
                            return diffTimeMinus($total_horas, '04:00:00');
                        } else {
                            return diffTime('04:00:00', $total_horas);
                        }

                    #return true;
                }

                //Função que formata a duração de tempo
                function formatTime($duracaoMinutos) {
                    $duracaoMinutos = abs($duracaoMinutos); //torna o número positivo
                    $h = floor($duracaoMinutos / 60);
                    $m = $duracaoMinutos % 60;

                    return ($h < 10 ? "0{$h}" : $h). ':'. ($m < 10 ? "0{$m}" : $m);
                }

                //Função que mostra o mês por extenso
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

                //Buscando o total de dias do mês

                $all_days = array();
                $days_month = cal_days_in_month(CAL_GREGORIAN, $_GET[''.$pymes.''], $_GET[''.$pyano.'']);
                $x = 1;

                    for($i = 0;$i < $days_month;$i++) {
                        if(strlen($x) == 1) {
                            $x = '0'.$x;
                        }

                        $all_days[] = $x;
                        $x++;
                    }
                
                unset($days_month,$x);

                try {
                    include_once('../conexao.php');

                    $pathora = '00:00:00';
                    $mes = $_GET[''.$pymes.''];
                    $ano = $_GET[''.$pyano.''];

                    $sql = $pdo->prepare("SELECT registro.tipo,registro.dia,registro.mes,registro.ano,registro.hora FROM registro,login WHERE registro.login_idlogin = login.idlogin AND login.idlogin = :idlogin AND registro.mes = :mes AND registro.ano = :ano AND registro.hora <> :hora ORDER BY registro.dia,registro.hora");
                    $sql->bindParam(':idlogin', $_GET[''.$pylogin.''], PDO::PARAM_INT);
                    $sql->bindParam(':mes', $mes, PDO::PARAM_STR);
                    $sql->bindParam(':ano', $ano, PDO::PARAM_STR);
                    $sql->bindParam(':hora', $pathora, PDO::PARAM_STR);
                    $res = $sql->execute();
                    $ret = $sql->rowCount();

                        if($ret > 0) {
                            $i = 0;
                            $dia_trabalho = '';
                            $worked_days = array();
                            #$pylogin = md5('idlogin');
                            #$pynome = md5('nome');
                            #$pymes = md5('mes');
                            #$pyano = md5('ano');

                            echo'
                            <div class="row">
                                <div class="col-xs-12">
                                    <h2 class="page-header">'.$_GET[''.$pynome.''].' - '.mes_extenso($mes).' de '.$ano.'</h2>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 table-responsive">';

                                while($lin = $sql->fetch(PDO::FETCH_OBJ)) {
                                    if(isset($dia)) {
                                        if($dia == $lin->dia) {
                                            $dia_trabalho .= $lin->ano.'/'.$lin->mes.'/'.$lin->dia.' '.$lin->hora.',';
                                        } else {
                                            $dia_trabalho .= ') array('.$lin->ano.'/'.$lin->mes.'/'.$lin->dia.' '.$lin->hora.',';
                                        }
                                    } else {
                                        $dia_trabalho .= 'array('.$lin->ano.'/'.$lin->mes.'/'.$lin->dia.' '.$lin->hora.',';
                                    }

                                    $dia = $lin->dia;
                                    $worked_days[] = $lin->dia;
                                }

                            //Comparando os dias trabalhados com os dias do mês
                            //e realinhando os índices, caso não seja nulo.

                            $days_not_worked = array_diff($all_days, $worked_days);
                            /*print_r($all_days);
                            echo'<br>';
                            print_r($worked_days);
                            echo'<br>';
                            print_r($days_not_worked); exit;*/

                                if(!empty($days_not_worked)) {
                                    $days_not_worked = array_values($days_not_worked);

                                        foreach($days_not_worked as $dayoff) {
                                            if(diaSemana($ano.'-'.$mes.'-'.$dayoff) != 'Domingo') {
                                                if(diaFeriado($dayoff.'/'.$mes) != true) {
                                                    $real_days_off[] = $dayoff;
                                                }
                                            } 
                                        }

                                        $days_off_count = count($real_days_off);
                                        $n = 1;
                                        $day_fault = '';
                                        $day_fault .= '<br><span class="lead">O funcion&aacute;rio faltou no(s) dia(s): <strong>';

                                            if(isset($real_days_off)) {
                                                foreach($real_days_off as $dayz) {
                                                    if($n < $days_off_count) {
                                                        $day_fault .= $dayz.', ';
                                                    } else {
                                                        $day_fault .= $dayz;
                                                    }
                                                    
                                                    $n++;
                                                }
                                            }

                                        $day_fault .= '</strong></span>';
                                        unset($days_off_count,$real_days_off,$n);
                                }
                            
                            #print_r($real_days_off); exit;

                            //Cálculo dos dias trabalhados

                            #echo $dia_trabalho; exit;
                            $dias_trabalhados = explode('array', $dia_trabalho);
                            #print_r($dias_trabalhados); exit;
                            $reg = '';
                            $diff = '';

                            echo'
                            <table class="table table-border table-striped table-hover">
                                <tr>
                                    <th>Data</th>
                                    <th>Dia</th>
                                    <th>Horas Trabalhadas</th>
                                </tr>';

                                foreach($dias_trabalhados as $dia_trabalho) {
                                    #echo $dia_trabalho.'<br>';
                                    $registros = explode(',', $dia_trabalho);

                                        foreach($registros as $registro) {
                                            #echo strlen($registro).': '.$registro.'<br>';

                                            if(strlen($registro) == 20) {
                                                $reg .= str_replace('(', '', $registro).',';
                                            } elseif(strlen($registro) == 19) {
                                                $reg .= $registro.',';
                                            } elseif(strlen($registro) == 2) {
                                                $reg = rtrim($reg, ', ');
                                                #echo $reg.'<br>';

                                                    if(strlen($reg) == 79) {
                                                        #echo $reg.'<br>';
                                                        #echo workTime($reg).'<br>';
                                                        $diff .= workTime($reg).',';
                                                    } elseif(strlen($reg) == 39) {
                                                        #echo $reg.'<br>';
                                                        #echo workTimeSat($reg).'<br>';
                                                        $diff .= workTimeSat($reg).',';
                                                    } elseif(strlen($reg) == 19) {
                                                        #echo $reg.'<br>';
                                                        #echo workTimeAlt($reg).'<br>';
                                                        $diff .= workTimeAlt($reg);
                                                    }
                                                
                                                $reg = '';
                                            } else {
                                                $reg = rtrim($reg, ', ');
                                                #echo $reg.'<br>';

                                                    if(strlen($reg) == 79) {
                                                        #echo $reg.'<br>';
                                                        #echo workTime($reg).'<br>';
                                                        $diff .= workTime($reg).',';
                                                    } elseif(strlen($reg) == 39) {
                                                        #echo $reg.'<br>';
                                                        #echo workTimeSat($reg).'<br>';
                                                        $diff .= workTimeSat($reg).',';
                                                    } elseif(strlen($reg) == 19) {
                                                        #echo $reg.'<br>';
                                                        #echo workTimeAlt($reg).'<br>';
                                                        $diff .= workTimeAlt($reg);
                                                    }
                                                
                                                $reg = '';
                                            }
                                        } //foreach
                                } //foreach
                            
                            #exit;
                            echo'</table>';
                            #exit;

                            //Até aqui uma tabela é montada que calcula 
                            //o quanto de tempo foi trabalhado por dia

                            $positivos = 0;
                            $negativos = 0;
                            #echo $diff.'<br>';
                            $diff = explode(',', $diff);
                            #print_r($diff); exit;

                                foreach($diff as $diferenca) {
                                    #echo $diferenca.'<br>';
                                    if(!empty($diferenca)) {
                                        $v = explode(' ', $diferenca);
                                        #print_r($v).'<br>';
                                        $campos = explode(':', $v[1]);
                                        #print_r($campos).'<br>';
                                        $duracaoMinutos = ($campos[0] * 60) + $campos[1];
                                        #echo $duracaoMinutos.'<br>';

                                            if($v[0] == '+') {
                                                $positivos += $duracaoMinutos;
                                            } elseif($v[0] == '-') {
                                                $negativos += $duracaoMinutos;
                                            }
                                    }
                                }
                            
                            #exit;
                            $pos = formatTime($positivos).':00';
                            $neg = formatTime($negativos).':00';
                            $pos_out = str_replace(':', '', $pos);
                            $neg_out = str_replace(':', '', $neg);
                            #echo 'pos: '.$positivos.' | neg: '.$negativos;
                            #echo 'pos: '.$pos.' | neg: '.$neg.'<br>';
                            #echo 'pos: '.$pos_out.' | neg: '.$neg_out.'<br>';
                            #exit;

                            echo'<div style="overflow: hidden;height: 80px;">';

                                if($pos_out > $neg_out) {
                                    echo'<span class="lead">O funcion&aacute;rio trabalhou <strong>'.diffTimeFinal($neg, $pos).' a mais</strong></span>';
                                } elseif($pos_out == $neg_out) {
                                    echo'<span class="lead">O funcion&aacute;rio n&atilde;o deve e n&atilde; tem horas para compensar.</span>';
                                } else {
                                    echo'<span class="lead">O funcion&aacute;rio trabalhou <strong>'.diffTimeFinal($pos, $neg).' a menos</strong></span>';
                                }

                                echo $day_fault;

                            echo'
                                </div>
                                <div>
                                    <p>___________________________________________________<br><i>Assinatura</i></p>
                                </div>
                            </div>
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