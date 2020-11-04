<?php
    /* CLEAR CACHE */
    
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    //header("Content-Type: application/xml; charset=utf-8");
    
    try {
        include_once('../conexao.php');

        /* BUSCA DADOS DO REGISTRO */

        #$py = md5('idlogin');
        $sql = $pdo->prepare("SELECT idregistro,tipo,dia,mes,ano,hora FROM registro WHERE login_idlogin = :idlogin AND dia = :dia AND mes = :mes AND ano = :ano ORDER BY hora");
        $sql->bindParam(':idlogin', $_GET['idlogin'], PDO::PARAM_INT);
        $sql->bindParam(':dia', $_GET['dia'], PDO::PARAM_STR);
        $sql->bindParam(':mes', $_GET['mes'], PDO::PARAM_STR);
        $sql->bindParam(':ano', $_GET['ano'], PDO::PARAM_STR);
        $sql->execute();
        $ret = $sql->rowCount();

            if($ret > 0) {
                $alltipos = array('PHM','SHM','PHT','SHT');
                $tipos = array();

                echo'
                <form class="form-edita-registro">
                    <div class="modal-header">
                        <button type="button" class="close closed" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title">Edita registro <small>(<i class="fa fa-asterisk"></i> Campo obrigat&oacute;rio)</small></h4>
                    </div><!-- /.modal-header -->
                    <div class="modal-body">
                        <input type="hidden" id="idlogin-" value="'.$_GET['idlogin'].'">
                        <input type="hidden" id="dia" value="'.$_GET['dia'].'">
                        <input type="hidden" id="mes" value="'.$_GET['mes'].'">
                        <input type="hidden" id="ano" value="'.$_GET['ano'].'">
                        <input type="hidden" id="tab" value="'.$_GET['tab'].'">';

                while($lin = $sql->fetch(PDO::FETCH_OBJ)) {
                    if($lin->tipo == 'PHM') {
                        if(empty($lin->hora)) {
                            $phm = '
                            <div class="form-group">
                                <label for="phm">Come&ccedil;o do Expediente</label>
                                <div class="input-group col-md-4">
                                    <input type="hidden" id="idregistro_phm" value="">
                                    <input type="text" id="phm" class="form-control" maxlength="8" title="Digite o hor&aacute;rio" placeholder="Hor&aacute;rio">
                                </div>
                            </div>';
                        } else {
                            $phm = '
                            <div class="form-group">
                                <label for="phm"><i class="fa fa-asterisk"></i> Come&ccedil;o do Expediente</label>
                                <div class="input-group col-md-4">
                                    <input type="hidden" id="idregistro_phm" value="'.$lin->idregistro.'">
                                    <input type="text" id="phm" class="form-control" value="'.$lin->hora.'" maxlength="8" title="Digite o hor&aacute;rio" placeholder="Hor&aacute;rio">
                                </div>
                            </div>';
                        }

                        $tipos[] = $lin->tipo;
                    }

                    if($lin->tipo == 'SHM') {
                        if (empty($lin->hora)) {
                            $shm = '
                            <div class="form-group">
                                <label for="shm">Sa&iacute;da para o Almo&ccedil;o</label>
                                <div class="input-group col-md-4">
                                    <input type="hidden" id="idregistro_shm" value="">
                                    <input type="text" id="shm" class="form-control" maxlength="8" title="Digite o hor&aacute;rio" placeholder="Hor&aacute;rio">
                                </div>
                            </div>';
                        } else {
                            $shm = '
                            <div class="form-group">
                                <label for="shm"><i class="fa fa-asterisk"></i> Sa&iacute;da para o Almo&ccedil;o</label>
                                <div class="input-group col-md-4">
                                    <input type="hidden" id="idregistro_shm" value="'.$lin->idregistro.'">
                                    <input type="text" id="shm" class="form-control" value="'.$lin->hora.'" maxlength="8" title="Digite o hor&aacute;rio" placeholder="Hor&aacute;rio">
                                </div>
                            </div>';
                        }

                        $tipos[] = $lin->tipo;
                    }

                    if($lin->tipo == 'PHT') {
                        if(empty($lin->hora)) {
                            $pht = '
                            <div class="form-group">
                                <label for="pht">Chegada do Almo&ccedil;o</label>
                                <div class="input-group col-md-4">
                                    <input type="hidden" id="idregistro_pht" value="">
                                    <input type="text" id="pht" class="form-control" maxlength="8" title="Digite o hor&aacute;rio" placeholder="Hor&aacute;rio">
                                </div>
                            </div>';
                        } else {
                            $pht = '
                            <div class="form-group">
                                <label for="pht"><i class="fa fa-asterisk"></i> Chegada do Almo&ccedil;o</label>
                                <div class="input-group col-md-4">
                                    <input type="hidden" id="idregistro_pht" value="'.$lin->idregistro.'">
                                    <input type="text" id="pht" class="form-control" value="'.$lin->hora.'" maxlength="8" title="Digite o hor&aacute;rio" placeholder="Hor&aacute;rio">
                                </div>
                            </div>';
                        }

                        $tipos[] = $lin->tipo;
                    }

                    if($lin->tipo == 'SHT') {
                        if(empty($lin->hora)) {
                            $sht = '
                            <div class="form-group">
                                <label for="sht">Fim do Expediente</label>
                                <div class="input-group col-md-4">
                                    <input type="hidden" id="idregistro_sht" value="">
                                    <input type="text" id="sht" class="form-control" maxlength="8" title="Digite o hor&aacute;rio" placeholder="Hor&aacute;rio">
                                </div>
                            </div>';
                        } else {
                            $sht = '
                            <div class="form-group">
                                <label for="sht"><i class="fa fa-asterisk"></i> Fim do Expediente</label>
                                <div class="input-group col-md-4">
                                    <input type="hidden" id="idregistro_sht" value="'.$lin->idregistro.'">
                                    <input type="text" id="sht" class="form-control" value="'.$lin->hora.'" maxlength="8" title="Digite o hor&aacute;rio" placeholder="Hor&aacute;rio">
                                </div>
                            </div>';
                        }

                        $tipos[] = $lin->tipo;
                    }
                } //while

                $diff = array_diff($alltipos, $tipos);
                
                if(!empty($diff)) {
                    $diff = array_values($diff);

                        foreach($diff as $tipo) {
                            switch($tipo) {
                                case 'PHM':
                                    $phm = '
                                    <div class="form-group">
                                        <label for="phm">Come&ccedil;o do Expediente</label>
                                        <div class="input-group col-md-4">
                                            <input type="hidden" id="idregistro_phm" value="">
                                            <input type="text" id="phm" class="form-control" maxlength="8" title="Digite o hor&aacute;rio" placeholder="Hor&aacute;rio">
                                        </div>
                                    </div>';
                                    break;
                                case 'SHM':
                                    $shm = '
                                    <div class="form-group">
                                        <label for="shm">Sa&iacute;da para o Almo&ccedil;o</label>
                                        <div class="input-group col-md-4">
                                            <input type="hidden" id="idregistro_shm" value="">
                                            <input type="text" id="shm" class="form-control" maxlength="8" title="Digite o hor&aacute;rio" placeholder="Hor&aacute;rio">
                                        </div>
                                    </div>';
                                    break;
                                case 'PHT':
                                    $pht = '
                                    <div class="form-group">
                                        <label for="pht">Chegada do Almo&ccedil;o</label>
                                        <div class="input-group col-md-4">
                                            <input type="hidden" id="idregistro_pht" value="">
                                            <input type="text" id="pht" class="form-control" maxlength="8" title="Digite o hor&aacute;rio" placeholder="Hor&aacute;rio">
                                        </div>
                                    </div>';
                                    break;
                                case 'SHT':
                                    $sht = '
                                    <div class="form-group">
                                        <label for="sht">Fim do expediente</label>
                                        <div class="input-group col-md-4">
                                            <input type="hidden" id="idregistro_sht" value="">
                                            <input type="text" id="sht" class="form-control" maxlength="8" title="Digite o hor&aacute;rio" placeholder="Hor&aacute;rio">
                                        </div>
                                    </div>';
                                    break;
                            } //switch
                        } //foreach
                } //if

                echo $phm.$shm.$pht.$sht;

                echo'
                    </div><!-- /.modal-body -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-flat pull-left closed" data-dismiss="modal">Fechar</button>
                        <button type="submit" class="btn btn-primary btn-flat btn-submit-edita-registro">Salvar</button>
                    </div><!-- /.modal-footer -->
                </form>
                <script async src="../js/maskedinput.min.js"></script>
                <script async src="../js/apart.min.js"></script>';
            } else {
                echo'
                <div class="callout">
                    <h4>Par&acirc;mentro incorreto</h4>
                </div>';
            }

        $sql->closeCursor();
        unset($pdo,$sql,$ret,$lin);
    }
    catch(PDOException $e) {
        echo'Falha ao conectar o servidor '.$e->getMessage();
    }
?>