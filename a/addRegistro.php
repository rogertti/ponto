<?php
    /* CLEAR CACHE */
    
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    //header("Content-Type: application/xml; charset=utf-8");
?>
<form class="form-add-registro">
    <div class="modal-header">
        <button type="button" class="close closed" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Adicionar registro <small>(<i class="fa fa-asterisk"></i> Campo obrigat&oacute;rio)</small></h4>
    </div><!-- /.modal-header -->
    <div class="modal-body">
        <input type="hidden" id="idlogin-add" value="<?php echo $_GET['idlogin']; ?>">
        <input type="hidden" id="mes-" value="<?php echo $_GET['mes']; ?>">
        <input type="hidden" id="ano-" value="<?php echo $_GET['ano']; ?>">
        <input type="hidden" id="tab-" value="<?php echo $_GET['tab']; ?>">
        <?php
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
                                    #return $feriado->date;
                                    return true;
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

            //Buscando o total de dias do mês
            $alldias = array();
            $days_month = cal_days_in_month(CAL_GREGORIAN, $_GET['mes'], $_GET['ano']);
            $x = 1;

                for($i = 0;$i < $days_month;$i++) {
                    if(strlen($x) == 1) {
                        $x = '0'.$x;
                    }

                    #array_push($alldias, $x);
                    $alldias[] = $x;
                    $x++;
                }

            unset($days_month,$x);

            //Buscando os dias em que foram registrados os horários
            try {
                include_once('../conexao.php');

                $sql = $pdo->prepare("SELECT dia FROM registro WHERE login_idlogin = :idlogin AND mes = :mes AND ano = :ano ORDER BY dia");
                $sql->bindParam(':idlogin', $_GET['idlogin'], PDO::PARAM_INT);
                $sql->bindParam(':mes', $_GET['mes'], PDO::PARAM_STR);
                $sql->bindParam(':ano', $_GET['ano'], PDO::PARAM_STR);
                $sql->execute();
                $ret = $sql->rowCount();

                    if($ret > 0) {
                        $regdias = array();

                            while($lin = $sql->fetch(PDO::FETCH_OBJ)) {
                                #array_push($regdias, $lin->dia);
                                $regdias[] = $lin->dia;
                            }

                        $difdias = array_diff($alldias, $regdias);

                            if(!empty($difdias)) {
                                $difdias = array_values($difdias);

                                echo'
                                <div class="form-group">
                                    <label for="dia-"><i class="fa fa-asterisk"></i> Dia</label>
                                    <div class="input-group col-md-4">
                                        <select id="dia-" class="form-control" required>
                                            <option value="" selected></option>';

                                    foreach($difdias as $dia) {
                                        if((diaFeriado($dia.'/'.$_GET['mes'])) != true) {
                                            echo'<option value="'.$dia.'">'.$dia.' - '.diaSemana($_GET['ano'].'-'.$_GET['mes'].'-'.$dia).'</option>';
                                        } else {
                                            echo'<option value="'.$dia.'" disabled>'.$dia.' - '.nomeFeriado($dia.'/'.$_GET['mes']).'</option>';
                                        }
                                    }

                                echo'
                                        </select>
                                    </div>
                                </div>';
                            } else {
                                //todos os dias preenchidos
                            }
                    }

                $sql->closeCursor();
            }
            catch(PDOException $e) {
                echo'Falha ao conectar o servidor '.$e->getMessage();
            }
        ?>
        <div class="form-group">
            <label for="phm-"><i class="fa fa-asterisk"></i> Come&ccedil;o do Expediente</label>
            <div class="input-group col-md-4">
                <input type="text" id="phm-" class="form-control" maxlength="8" title="Digite o hor&aacute;rio" placeholder="Hor&aacute;rio" required>
            </div>
        </div>
        <div class="form-group">
            <label for="shm-"><i class="fa fa-asterisk"></i> Sa&iacute;da para o Almo&ccedil;o</label>
            <div class="input-group col-md-4">
                <input type="text" id="shm-" class="form-control" maxlength="8" title="Digite o hor&aacute;rio" placeholder="Hor&aacute;rio" required>
            </div>
        </div>
        <div class="form-group">
            <label for="pht-">Chegada do Almo&ccedil;o</label>
            <div class="input-group col-md-4">
                <input type="text" id="pht-" class="form-control" maxlength="8" title="Digite o hor&aacute;rio" placeholder="Hor&aacute;rio">
            </div>
        </div>
        <div class="form-group">
            <label for="sht-">Fim do Expediente</label>
            <div class="input-group col-md-4">
                <input type="text" id="sht-" class="form-control" maxlength="8" title="Digite o hor&aacute;rio" placeholder="Hor&aacute;rio">
            </div>
        </div>
    </div><!-- /.modal-body -->
    <div class="modal-footer">
        <button type="button" class="btn btn-default btn-flat pull-left closed" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary btn-flat btn-submit-add-registro">Salvar</button>
    </div><!-- /.modal-footer -->
</form>
<script async src="../js/maskedinput.min.js"></script>
<script async src="../js/apart.min.js"></script>
