<?php
    require_once('../config.php');

    /* CONTROLE DE VARIAVEL */

    $msg = "Campo obrigat&oacute;rio vazio.";

        if(empty($_POST['idlogin'])) { die("reload"); }
        if(empty($_POST['rand'])) { die("Vari&aacute;vel de controle nula."); }

        if((empty($_POST['idregistro_phm'])) && (!empty($_POST['phm']))) {
            try {
                include_once('../conexao.php');

                //REGISTRA O PONTO

                $tipo = 'PHM';
                $sql = $pdo->prepare("INSERT INTO registro (login_idlogin,tipo,dia,mes,ano,hora) VALUES (:idlogin,:tipo,:dia,:mes,:ano,:hora)");
                $sql->bindParam(':idlogin', $_POST['idlogin'], PDO::PARAM_INT);
                $sql->bindParam(':tipo', $tipo, PDO::PARAM_STR);
                $sql->bindParam(':dia', $_POST['dia'], PDO::PARAM_STR);
                $sql->bindParam(':mes', $_POST['mes'], PDO::PARAM_STR);
                $sql->bindParam(':ano', $_POST['ano'], PDO::PARAM_STR);
                $sql->bindParam(':hora', $_POST['phm'], PDO::PARAM_STR);
                $res = $sql->execute();

                    if (!$res) {
                        $sql = $sql->errorInfo();
                        die($sql[2]);
                    } else {
                        $true = 'true';
                    }
            } catch(PDOException $e) {
                echo'<span>Erro ao conectar o servidor: '.$e->getMessage().'</span>';
            }
        } else {
            try {
                include_once('../conexao.php');

                //REGISTRA O PONTO

                $sql = $pdo->prepare("UPDATE registro SET hora = :hora WHERE idregistro = :idregistro");
                $sql->bindParam(':idregistro', $_POST['idregistro_phm'], PDO::PARAM_INT);
                $sql->bindParam(':hora', $_POST['phm'], PDO::PARAM_STR);
                $res = $sql->execute();

                    if (!$res) {
                        $sql = $sql->errorInfo();
                        die($sql[2]);
                    } else {
                        $true = 'true';
                    }
            } catch(PDOException $e) {
                echo'<span>Erro ao conectar o servidor: '.$e->getMessage().'</span>';
            }
        }

        if((empty($_POST['idregistro_shm'])) && (!empty($_POST['shm']))) {
            try {
                include_once('../conexao.php');

                //REGISTRA O PONTO

                $tipo = 'SHM';
                $sql = $pdo->prepare("INSERT INTO registro (login_idlogin,tipo,dia,mes,ano,hora) VALUES (:idlogin,:tipo,:dia,:mes,:ano,:hora)");
                $sql->bindParam(':idlogin', $_POST['idlogin'], PDO::PARAM_INT);
                $sql->bindParam(':tipo', $tipo, PDO::PARAM_STR);
                $sql->bindParam(':dia', $_POST['dia'], PDO::PARAM_STR);
                $sql->bindParam(':mes', $_POST['mes'], PDO::PARAM_STR);
                $sql->bindParam(':ano', $_POST['ano'], PDO::PARAM_STR);
                $sql->bindParam(':hora', $_POST['shm'], PDO::PARAM_STR);
                $res = $sql->execute();

                    if (!$res) {
                        $sql = $sql->errorInfo();
                        die($sql[2]);
                    } else {
                        $true = 'true';
                    }
            } catch(PDOException $e) {
                echo'<span>Erro ao conectar o servidor: '.$e->getMessage().'</span>';
            }
        } else {
            try {
                include_once('../conexao.php');

                //REGISTRA O PONTO

                $sql = $pdo->prepare("UPDATE registro SET hora = :hora WHERE idregistro = :idregistro");
                $sql->bindParam(':idregistro', $_POST['idregistro_shm'], PDO::PARAM_INT);
                $sql->bindParam(':hora', $_POST['shm'], PDO::PARAM_STR);
                $res = $sql->execute();

                    if (!$res) {
                        $sql = $sql->errorInfo();
                        die($sql[2]);
                    } else {
                        $true = 'true';
                    }
            } catch(PDOException $e) {
                echo'<span>Erro ao conectar o servidor: '.$e->getMessage().'</span>';
            }
        }

        if((empty($_POST['idregistro_pht'])) && (!empty($_POST['pht']))) {
            try {
                include_once('../conexao.php');

                //REGISTRA O PONTO

                $tipo = 'PHT';
                $sql = $pdo->prepare("INSERT INTO registro (login_idlogin,tipo,dia,mes,ano,hora) VALUES (:idlogin,:tipo,:dia,:mes,:ano,:hora)");
                $sql->bindParam(':idlogin', $_POST['idlogin'], PDO::PARAM_INT);
                $sql->bindParam(':tipo', $tipo, PDO::PARAM_STR);
                $sql->bindParam(':dia', $_POST['dia'], PDO::PARAM_STR);
                $sql->bindParam(':mes', $_POST['mes'], PDO::PARAM_STR);
                $sql->bindParam(':ano', $_POST['ano'], PDO::PARAM_STR);
                $sql->bindParam(':hora', $_POST['pht'], PDO::PARAM_STR);
                $res = $sql->execute();

                    if (!$res) {
                        $sql = $sql->errorInfo();
                        die($sql[2]);
                    } else {
                        $true = 'true';
                    }
            } catch(PDOException $e) {
                echo'<span>Erro ao conectar o servidor: '.$e->getMessage().'</span>';
            }
        } else {
            try {
                include_once('../conexao.php');

                //REGISTRA O PONTO

                $sql = $pdo->prepare("UPDATE registro SET hora = :hora WHERE idregistro = :idregistro");
                $sql->bindParam(':idregistro', $_POST['idregistro_pht'], PDO::PARAM_INT);
                $sql->bindParam(':hora', $_POST['pht'], PDO::PARAM_STR);
                $res = $sql->execute();

                    if (!$res) {
                        $sql = $sql->errorInfo();
                        die($sql[2]);
                    } else {
                        $true = 'true';
                    }
            } catch(PDOException $e) {
                echo'<span>Erro ao conectar o servidor: '.$e->getMessage().'</span>';
            }
        }

        if((empty($_POST['idregistro_sht'])) && (!empty($_POST['sht']))) {
            try {
                include_once('../conexao.php');

                //REGISTRA O PONTO

                $tipo = 'SHT';
                $sql = $pdo->prepare("INSERT INTO registro (login_idlogin,tipo,dia,mes,ano,hora) VALUES (:idlogin,:tipo,:dia,:mes,:ano,:hora)");
                $sql->bindParam(':idlogin', $_POST['idlogin'], PDO::PARAM_INT);
                $sql->bindParam(':tipo', $tipo, PDO::PARAM_STR);
                $sql->bindParam(':dia', $_POST['dia'], PDO::PARAM_STR);
                $sql->bindParam(':mes', $_POST['mes'], PDO::PARAM_STR);
                $sql->bindParam(':ano', $_POST['ano'], PDO::PARAM_STR);
                $sql->bindParam(':hora', $_POST['sht'], PDO::PARAM_STR);
                $res = $sql->execute();

                    if (!$res) {
                        $sql = $sql->errorInfo();
                        die($sql[2]);
                    } else {
                        $true = 'true';
                    }
            } catch(PDOException $e) {
                echo'<span>Erro ao conectar o servidor: '.$e->getMessage().'</span>';
            }
        } else {
            try {
                include_once('../conexao.php');

                //REGISTRA O PONTO

                $sql = $pdo->prepare("UPDATE registro SET hora = :hora WHERE idregistro = :idregistro");
                $sql->bindParam(':idregistro', $_POST['idregistro_sht'], PDO::PARAM_INT);
                $sql->bindParam(':hora', $_POST['sht'], PDO::PARAM_STR);
                $res = $sql->execute();

                    if (!$res) {
                        $sql = $sql->errorInfo();
                        die($sql[2]);
                    } else {
                        $true = 'true';
                    }
            } catch(PDOException $e) {
                echo'<span>Erro ao conectar o servidor: '.$e->getMessage().'</span>';
            }
        }

        if(isset($true)) {
            #echo'<url>index.php#tab_'.$_POST['tab'].'</url>';
            #echo'<url>index.php</url>';
            echo'<url>'.$_SESSION['geturl'].'</url>';
        }
?>
