<?php
    require_once('config.php');
    #require_once('classGeoPlugin.php');

    #$geoplugin = new geoPlugin();
    #$geoplugin->locate();

    if(isset($_SESSION['key'])) {
        header('location:inicio');
    }

    if(file_exists('installAplicativo.php')) {
        header('location:instalacao');
    }
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
        <link rel="stylesheet" href="css/smoke.min.css">
        <link rel="stylesheet" href="css/core.min.css">
        <!--[if lt IE 9]><script src="js/html5shiv.min.js"></script><script src="js/respond.min.js"></script><![endif]-->
    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <?php echo $cfg['titulo_extenso']; ?>
            </div><!-- /.login-logo -->
            <div class="login-box-body">
                <p class="login-box-msg"><strong><?php echo $cfg['descricao']; ?></strong></p>
                <form class="form-login">
                    <?php
                        /*if(!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on') {
                            $nossl = 1;

                            echo'        
                            <h3 class="text-center">Conexão não segura!</h3>
                            <p class="text-center"><a href="https://ponto.embracore.com.br" class="lead">Novo acesso em conex&atilde;o segura</a></p>';
                        } *//*elseif($geoplugin->ip != '45.4.112.18') {
                            $noip = 1;

                            echo'
                            <h3 class="text-center">Acesso Negado!</h3>
                            <p class="text-center">Voc&ecirc; n&atilde;o est&aacute; acessando o programa da empresa.</p>';
                        } */#else {
                    ?>
                    <div class="form-group has-feedback">
                        <input type="text" id="usuario" class="form-control" maxlength="20" title="Digite o seu usu&aacute;rio" placeholder="Usu&aacute;rio" required>
                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" id="senha" class="form-control" maxlength="20" title="Digite a sua senha" placeholder="Senha" required>
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-xs-offset-8 col-xs-4">
                            <button type="submit" class="btn btn-primary btn-block btn-flat btn-submit-login">Entrar</button>
                        </div><!-- /.col -->
                    </div>
                    <?php
                        #}
                    ?>
                </form>

                <a class="rcv" data-toggle="modal" data-target="#recover-pass" href="#" title="Eu esqueci a minha senha"><i class="ion ion-android-sad"></i> Eu esqueci a minha senha</a>
                <!--<br><a href="#" title="Ir para o site"><i class="fa fa-arrow-right"></i> Ir para o site</a>-->
            </div><!-- /.login-box-body -->
        </div><!-- /.login-box -->

        <!-- Modal -->
        <div class="modal fade" id="recover-pass" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form class="form-recupera-senha">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title">Recuperar a senha <small>(<i class="fa fa-asterisk"></i> Campo obrigat&oacute;rio)</small></h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="email"><i class="fa fa-asterisk"></i> Email</label>
                                <input type="email" id="email" class="form-control" maxlength="100" title="Digite o seu email" placeholder="Email" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default btn-flat pull-left" data-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-primary btn-flat btn-submit-recover">Recuperar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div><!-- /.modal -->

        <script src="js/jquery-2.1.4.min.js"></script>
        <script defer src="js/bootstrap.min.js"></script>
        <script async src="js/smoke.min.js"></script>
        <script async src="js/apart.min.js"></script>
        <?php
            if(isset($nossl) || isset($noip)) {
        ?>
        <script>$('.rcv').addClass('hide');</script>
        <?php
            }
        ?>
    </body>
</html>
<?php unset($ip,$nossl,$noip,$geoplugin); ?>
