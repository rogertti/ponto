<?php
    /* CLEAR CACHE */
        
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    //header("Content-Type: application/xml; charset=utf-8");

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
    <div class="modal-header">
        <button type="button" class="close closed" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">&nbsp;</h4>
    </div><!-- /.modal-header -->
    <div class="modal-body">
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

            /* IMPRIMI TODOS OS REGISTROS DO MES */

            #$sql = $pdo->prepare("SELECT login.log,registro.tipo,registro.dia,registro.hora FROM login,registro WHERE registro.login_idlogin = login.idlogin AND login.idlogin = :idlogin AND registro.mes = :mes AND registro.ano = :ano AND registro.hora <> :hora ORDER BY registro.dia,registro.hora");
            #$sql = $pdo->prepare("SELECT registro.tipo,registro.dia,registro.hora,nota.texto AS log FROM registro INNER JOIN nota ON nota.login_idlogin = registro.login_idlogin INNER JOIN login ON registro.login_idlogin = login.idlogin WHERE nota.login_idlogin = :idlogin AND nota.mes = :mes AND nota.ano = :ano AND login.idlogin = :idlogin AND registro.mes = :mes AND registro.ano = :ano AND registro.hora <> :hora ORDER BY registro.dia,registro.hora");
            $sql = $pdo->prepare("SELECT registro.tipo,registro.dia,registro.hora FROM registro INNER JOIN login ON registro.login_idlogin = login.idlogin WHERE login.idlogin = :idlogin AND registro.mes = :mes AND registro.ano = :ano AND registro.hora <> :hora ORDER BY registro.dia,registro.hora");
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
                        }

                    echo $hm.'</tr>';

                    echo'
                                </tbody>
                            </table>
                        </div>
                    </div>';
                }

            $sql->closeCursor();

            /* BUSCA A NOTA */

            $sql = $pdo->prepare("SELECT texto FROM nota WHERE login_idlogin = :idlogin AND mes = :mes AND ano = :ano");
            $sql->bindParam(':idlogin', $_GET[''.$pylogin.''], PDO::PARAM_INT);
            $sql->bindParam(':mes', $mes, PDO::PARAM_STR);
            $sql->bindParam(':ano', $ano, PDO::PARAM_STR);
            $res = $sql->execute();
            $ret = $sql->rowCount();

                if ($ret > 0) {
                    $lin = $sql->fetch(PDO::FETCH_OBJ);
                    $nota = '<div class="row"><div class="col-xs-12 pre-log"><pre>'.$lin->texto.'</pre></div></div>';
                } else {
                    $nota = '';
                }

            $sql->closeCursor();

            /* IMPRIMI A SOMA DOS REGISTROS DO MES */

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
                    <!--<div class="row">
                        <div class="col-xs-12">
                            <h2 class="page-header">'.$_GET[''.$pynome.''].' - '.mes_extenso($mes).' de '.$ano.'</h2>
                        </div>
                    </div>-->

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

                    echo $nota;

                    echo'<div style="overflow: hidden;height: 80px;">';

                        if($pos_out > $neg_out) {
                            echo'<span class="lead">O funcion&aacute;rio trabalhou <strong>'.diffTimeFinal($neg, $pos).'</strong> a <span class="label label-success">mais</span></span>';
                        } elseif($pos_out == $neg_out) {
                            echo'<span class="lead">O funcion&aacute;rio n&atilde;o deve e n&atilde; tem horas para compensar.</span>';
                        } else {
                            echo'<span class="lead">O funcion&aacute;rio trabalhou <strong>'.diffTimeFinal($pos, $neg).'</strong> a <span class="label label-danger">menos</span></span>';
                        }

                        echo $day_fault;

                    echo'
                        </div>
                        <!-- <div>
                            <a class="btn btn-default btn-flat" href="'.$_SESSION['geturl'].'">Voltar</a>
                            <a class="btn btn-primary btn-flat" href="printCloseRegistro.php?'.$pylogin.'='.$_GET[''.$pylogin.''].'&'.$pynome.'='.urlencode($_GET[''.$pynome.'']).'&'.$pyano.'='.$_GET[''.$pyano.''].'&'.$pymes.'='.$_GET[''.$pymes.''].'">Imprimir</a>
                        </div> -->
                    </div>
                    </div>';

                    $link = $pylogin.'='.$_GET[''.$pylogin.''].'&'.$pynome.'='.urlencode($_GET[''.$pynome.'']).'&'.$pyano.'='.$_GET[''.$pyano.''].'&'.$pymes.'='.$_GET[''.$pymes.''];
                } //$ret
            
            $sql->closeCursor();
        }
        catch(PDOException $e) {
            echo'<span>Erro ao conectar o servidor: '.$e->getMessage().'</span>';
        }
    ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default btn-flat pull-left closed" data-dismiss="modal">Fechar</button>
        <a href="printCloseRegistro.php?<?php echo $link; ?>" class="btn btn-primary btn-flat btn-submit-add-registro">Imprimir</a>
    </div><!-- /.modal-footer -->
    
    <script src="../js/jquery-2.1.4.min.js"></script>
    
<?php unset($cfg,$m,$e,$pdo,$fmes,$pylogin,$pynome,$sql,$res,$ret,$lin,$ano,$mes,$hora); ?>