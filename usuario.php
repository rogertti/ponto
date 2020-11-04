<?php
    require_once('config.php');

        if(empty($_SESSION['key'])) {
            header ('location:./');
        }

    $m = 2;
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
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>Usu&aacute;rio</h1>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="box">
                        <div class="box-body">
                        <?php
                            try {
                                include_once('conexao.php');

                                $sql = $pdo->prepare("SELECT idlogin,nome,usuario,senha,email FROM login WHERE idlogin = :idlogin");
                                $sql->bindParam(':idlogin', $_SESSION['id'], PDO::PARAM_INT);
                                $res = $sql->execute();
                                $ret = $sql->rowCount();

                                    if ($ret > 0) {
                                        $lin = $sql->fetch(PDO::FETCH_OBJ);
                        ?>
                            <form class="form-edita-usuario-single">
                                <input type="hidden" id="idlogin" value="<?php echo $lin->idlogin; ?>">

                                <div class="form-group">
                                    <label for="nome"><i class="fa fa-asterisk"></i> Nome</label>
                                    <div class="input-group col-xs-12 col-sm-6 col-md-4">
                                        <input type="text" id="nome" class="form-control" value="<?php echo $lin->nome; ?>" maxlength="255" title="Digite o nome do usu&aacute;rio" placeholder="Nome" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="usuario"><i class="fa fa-asterisk"></i> Usu&aacute;rio</label>
                                    <div class="input-group col-xs-12 col-sm-2 col-md-2">
                                        <input type="text" id="usuario" class="form-control" value="<?php echo base64_decode($lin->usuario); ?>" maxlength="20" title="Digite o usu&aacute;rio" placeholder="Usu&aacute;rio" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="senha"><i class="fa fa-asterisk"></i> Senha</label>
                                    <div class="input-group col-xs-12 col-sm-2 col-md-2">
                                        <input type="password" id="senha" class="form-control" value="<?php echo base64_decode($lin->senha); ?>" maxlength="20" title="Digite a senha" placeholder="Senha" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email"><i class="fa fa-asterisk"></i> Email</label>
                                    <div class="input-group col-xs-12 col-sm-6 col-md-4">
                                        <input type="email" id="email" class="form-control" value="<?php echo $lin->email; ?>" maxlength="100" title="Digite o email" placeholder="Email" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-flat btn-submit-usuario">Salvar</button>
                                </div>
                            </form>
                        <?php
                                    } // $ret
                            }
                            catch(PDOException $e) {
                                echo'<span>Erro ao conectar o servidor: '.$e->getMessage().'</span>';
                            }
                        ?>
                        </div><!-- /.box-body -->
                    </div><!-- /.box -->
                </section><!-- /.content -->
            </div><!-- /.content-wrapper -->

            <!-- Main Footer -->
            <footer class="main-footer"><?php include_once('footer.php'); ?></footer>
        </div><!-- ./wrapper -->

        <script src="js/jquery-2.1.4.min.js"></script>
        <script defer src="js/bootstrap.min.js"></script>
        <script async src="js/smoke.min.js"></script>
        <script async src="js/core.min.js"></script>
    </body>
</html>
<?php unset($cfg,$adm,$admactive,$m,$pdo,$e,$sql,$res,$ret,$lin); ?>
