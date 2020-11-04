<!-- sidebar: style can be found in sidebar.less -->
<section class="sidebar">
    <!-- Sidebar Menu -->
    <ul class="sidebar-menu">
    <?php
        switch($m) {
            case 1:
                echo'
                <li class="active"><a class="toggle-page" href="index.php" title="Vis&atilde;o geral"><i class="fa fa-clock-o"></i> <span>In&iacute;cio</span></a></li>
                <li><a class="toggle-page" href="usuario.php" title="Dados do usu&aacute;rio"><i class="fa fa-user"></i> <span>Usu&aacute;rio</span></a></li>';
            break;
            case 2:
                echo'
                <li><a class="toggle-page" href="index.php" title="Vis&atilde;o geral"><i class="fa fa-clock-o"></i> <span>In&iacute;cio</span></a></li>
                <li class="active"><a class="toggle-page" href="usuario.php" title="Dados do usu&aacute;rio"><i class="fa fa-user"></i> <span>Usu&aacute;rio</span></a></li>';
            break;
            default:
                echo'
                <li><a class="toggle-page" href="index.php" title="Vis&atilde;o geral"><i class="fa fa-clock-o"></i> <span>In&iacute;cio</span></a></li>
                <li><a class="toggle-page" href="usuario.php" title="Dados do usu&aacute;rio"><i class="fa fa-user"></i> <span>Usu&aacute;rio</span></a></li>';
            break;
        }
    ?>
    </ul><!-- /.sidebar-menu -->
</section>
<!-- /.sidebar -->
