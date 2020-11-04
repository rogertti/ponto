<?php
    require_once('../config.php');

        if(empty($_SESSION['key'])) {
            header ('location:../');
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
        <link rel="icon" type="image/png" href="../img/favicon.png">
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <link rel="stylesheet" href="../css/font-awesome.min.css">
        <link rel="stylesheet" href="../css/ionicons.min.css">
        <link rel="stylesheet" href="../css/smoke.min.css">
        <link rel="stylesheet" href="../css/dataTables.bootstrap.min.css">
        <link rel="stylesheet" href="../css/dataTables.responsive.bootstrap.min.css">
        <link rel="stylesheet" href="../css/icheck.min.css">
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
                    <h1>Usu&aacute;rio <span class="pull-right lead"><a class="btn btn-primary" data-toggle="modal" data-target="#novo-usuario" title="Clique para cadastrar um novo usu&aacute;rio" href="#"><i class="fa fa-user"></i> Novo usu&aacute;rio</a></span></h1>
                </section>

                <!-- Main content -->
                <section class="content">
                    <div class="box">
                        <div class="box-body">
                        <?php
                            try {
                                include_once('../conexao.php');

                                $sql = $pdo->prepare("SELECT idlogin,nome,usuario,email,tipo FROM login ORDER BY nome,usuario,email");
                                $res = $sql->execute();
                                $ret = $sql->rowCount();

                                    if ($ret > 0) {
                                        echo'
                                        <table class="table table-striped table-bordered table-hover table-data dt-responsive nowrap">
                                            <thead>
                                                <tr>
                                                    <th>Nome</th>
                                                    <th>Usu&aacute;rio</th>
                                                    <th>Email</th>
                                                    <th style="width: 120px;">Tipo</th>
                                                    <th style="width: 60px;"></th>
                                                </tr>
                                            </thead>
                                            <tbody>';

                                        $py = md5('idlogin');

                                            while($lin = $sql->fetch(PDO::FETCH_OBJ)) {
                                                switch ($lin->tipo) {
                                                    case 'U': $lin->tipo = '<span class="label label-default">Usu&aacute;rio</label>'; break;
                                                    case 'A': $lin->tipo = '<span class="label label-primary">Administrador</label>'; break;
                                                }

                                                echo'
                                                <tr>
                                                    <td>'.$lin->nome.'</td>
                                                    <td>'.base64_decode($lin->usuario).'</td>
                                                    <td>'.$lin->email.'</td>
                                                    <td>'.$lin->tipo.'</td>
                                                    <td style="text-align: center;">
                                                        <span><a data-toggle="modal" data-target="#edita-usuario" title="Editar o usu&aacute;rio" href="editaUsuario.php?'.$py.'='.$lin->idlogin.'"><i class="fa fa-pencil"></i></a></span>
                                                        <span><a class="delete-usuario" id="'.$py.'-'.$lin->idlogin.'" title="Excluir o usu&aacute;rio" href="#"><i class="fa fa-trash-o"></i></a></span>
                                                    </td>
                                                </tr>';
                                            }

                                        echo'
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Nome</th>
                                                    <th>Usu&aacute;rio</th>
                                                    <th>Email</th>
                                                    <th>Tipo</th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>
                                        </table>';
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

            <!-- modal -->
            <div class="modal fade" id="novo-usuario" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form class="form-novo-usuario">
                            <div class="modal-header">
                                <button type="button" class="close closed" data-dismiss="modal" aria-hidden="true">&times;</button>
                                <h4 class="modal-title">Novo usu&aacute;rio <small>(<i class="fa fa-asterisk"></i> Campo obrigat&oacute;rio)</small></h4>
                            </div><!-- /.modal-header -->
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="nome"><i class="fa fa-asterisk"></i> Nome</label>
                                    <div class="input-group col-xs-12 col-sm-12 col-md-12">
                                        <input type="text" id="nome" class="form-control" maxlength="255" title="Digite o nome do usu&aacute;rio" placeholder="Nome" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="usuario"><i class="fa fa-asterisk"></i> Usu&aacute;rio</label>
                                    <div class="input-group col-xs-6 col-sm-6 col-md-6">
                                        <input type="text" id="usuario" class="form-control" maxlength="20" title="Digite o usu&aacute;rio" placeholder="Usu&aacute;rio" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="senha"><i class="fa fa-asterisk"></i> Senha</label>
                                    <div class="input-group col-xs-6 col-sm-6 col-md-6">
                                        <input type="password" id="senha" class="form-control" maxlength="20" title="Digite a senha" placeholder="Senha" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email"><i class="fa fa-asterisk"></i> Email</label>
                                    <div class="input-group col-xs-12 col-sm-12 col-md-12">
                                        <input type="email" id="email" class="form-control" maxlength="100" title="Digite o email" placeholder="Email" required>
                                    </div>
                                </div>
                                <div class="control-icheck">
                                    <div class="form-group">
                                        <label for="tipo-usuario-"><i class="fa fa-asterisk"></i> Tipo</label>
                                        <div class="input-group">
                                            <span class="form-icheck"><input type="radio" name="tipo-usuario" value="A"> Administrador</span>
                                            <span class="form-icheck"><input type="radio" name="tipo-usuario" value="U" checked> Usu&aacute;rio</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default btn-flat pull-left closed" data-dismiss="modal">Fechar</button>
                                <button type="submit" class="btn btn-primary btn-flat btn-submit-novo-usuario">Salvar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="edita-usuario" role="dialog" aria-hidden="true">
                <div class="modal-dialog">
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
        <script src="../js/jquery.dataTables.min.js"></script>
        <script src="../js/dataTables.bootstrap.min.js"></script>
        <script src="../js/dataTables.responsive.min.js"></script>
        <script src="../js/dataTables.responsive.bootstrap.min.js"></script>
        <script async src="../js/icheck.min.js"></script>
        <script async src="../js/core.min.js"></script>
    </body>
</html>
<?php unset($cfg,$m,$e,$pdo); ?>
