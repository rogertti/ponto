<?php
    /* CLEAR CACHE */
    
    header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    //header("Content-Type: application/xml; charset=utf-8");
    
    try {
        include_once('../conexao.php');

        /* BUSCA DADOS DO USUARIO */

        $py = md5('idlogin');
        $sql = $pdo->prepare("SELECT idlogin,nome,usuario,senha,email,tipo FROM login WHERE idlogin = :idlogin");
        $sql->bindParam(':idlogin', $_GET[''.$py.''], PDO::PARAM_INT);
        $sql->execute();
        $ret = $sql->rowCount();

            if($ret > 0) {
                $lin = $sql->fetch(PDO::FETCH_OBJ);
?>
<form class="form-edita-usuario">
    <div class="modal-header">
        <button type="button" class="close closed" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Edita usu&aacute;rio <small>(<i class="fa fa-asterisk"></i> Campo obrigat&oacute;rio)</small></h4>
    </div><!-- /.modal-header -->
    <div class="modal-body">
        <input type="hidden" id="idlogin" value="<?php echo $lin->idlogin; ?>">

        <div class="form-group">
            <label for="nome-"><i class="fa fa-asterisk"></i> Nome</label>
            <input type="text" id="nome-" class="form-control" maxlength="255" value="<?php echo $lin->nome; ?>" title="Digite o nome do usu&aacute;rio" placeholder="Nome" required>
        </div>
        <div class="form-group">
            <label for="usuario-"><i class="fa fa-asterisk"></i> Usu&aacute;rio</label>
            <div class="input-group col-md-4">
                <input type="text" id="usuario-" class="form-control" maxlength="20" value="<?php echo base64_decode($lin->usuario); ?>" title="Digite o usu&aacute;rio" placeholder="Usu&aacute;rio" required>
            </div>
        </div>
        <div class="form-group">
            <label for="senha-"><i class="fa fa-asterisk"></i> Senha</label>
            <div class="input-group col-md-4">
                <input type="password" id="senha-" class="form-control" maxlength="20" value="<?php echo base64_decode($lin->senha); ?>" title="Digite a senha" placeholder="Senha" required>
            </div>
        </div>
        <div class="form-group">
            <label for="email-"><i class="fa fa-asterisk"></i> Email</label>
            <input type="email" id="email-" class="form-control" maxlength="100" value="<?php echo $lin->email; ?>" title="Digite o email do usu&aacute;rio" placeholder="Email" required>
        </div>
        <div class="control-icheck">
            <div class="form-group">
                <label for="tipo-usuario-"><i class="fa fa-asterisk"></i> Tipo</label>
                <div class="input-group">
                <?php
                    if($lin->tipo == "A") {
                        echo'
                        <span class="form-icheck"><input type="radio" name="tipo-usuario-" value="A" checked> Administrador</span>
                        <span class="form-icheck"><input type="radio" name="tipo-usuario-" value="U"> Usu&aacute;rio</span>';
                    }
                    else {
                        echo'
                        <span class="form-icheck"><input type="radio" name="tipo-usuario-" value="A"> Administrador</span>
                        <span class="form-icheck"><input type="radio" name="tipo-usuario-" value="U" checked> Usu&aacute;rio</span>';
                    }
                ?>
                </div>
            </div>
        </div>
    </div><!-- /.modal-body -->
    <div class="modal-footer">
        <button type="button" class="btn btn-default btn-flat pull-left closed" data-dismiss="modal">Fechar</button>
        <button type="submit" class="btn btn-primary btn-flat btn-submit-edita-usuario">Salvar</button>
    </div><!-- /.modal-footer -->
</form>
<script async src="../js/apart.min.js"></script>
<?php
            } //if($ret > 0)
            else {
                echo'
                <div class="callout">
                    <h4>Par&acirc;mentro incorreto</h4>
                </div>';
            }

        unset($pdo,$sql,$ret,$py);
    }
    catch(PDOException $e) {
        echo'Falha ao conectar o servidor '.$e->getMessage();
    }
?>
