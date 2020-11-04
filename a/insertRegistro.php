<?php
    /* CONTROLE DE VARIAVEL */

    $msg = "Campo obrigat&oacute;rio vazio.";

        if(empty($_POST['idlogin'])) { die("reload"); }
        if(empty($_POST['rand'])) { die("Vari&aacute;vel de controle nula."); }

    try {
        include_once('../conexao.php');

            //Obrigatório

            if(!empty($_POST['phm'])) {
                $tipo = 'PHM';
                $sql = $pdo->prepare("INSERT INTO registro (login_idlogin,tipo,dia,mes,ano,hora) VALUES (:idlogin,:tipo,:dia,:mes,:ano,:hora)");
                $sql->bindParam(':idlogin', $_POST['idlogin'], PDO::PARAM_INT);
                $sql->bindParam(':tipo', $tipo, PDO::PARAM_STR);
                $sql->bindParam(':dia', $_POST['dia'], PDO::PARAM_STR);
                $sql->bindParam(':mes', $_POST['mes'], PDO::PARAM_STR);
                $sql->bindParam(':ano', $_POST['ano'], PDO::PARAM_STR);
                $sql->bindParam(':hora', $_POST['phm'], PDO::PARAM_STR);
                $res = $sql->execute();

                    if(!$res) {
                        $sql = $sql->errorInfo();
                        die($sql[2]);
                    }
                    else {
                        $true = 1;
                    }

                $sql->closeCursor();
                unset($tipo);
            } else {
                die('O come&ccedil;o do expediente não pode ser nulo.');
            }

            //Obrigatório

            if(!empty($_POST['shm'])) {
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
                    }
                    else {
                        $true++;
                    }

                $sql->closeCursor();
                unset($tipo);
            } else {
                die('A sa&iacute;da para o almo&ccedil;o não pode ser nula.');
            }

            //Não obrigatório

            if(!empty($_POST['pht'])) {
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
                    }
                    else {
                        $true++;
                    }

                $sql->closeCursor();
                unset($tipo);
            }

            //Não obrigatório

            if(!empty($_POST['sht'])) {
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
                    }
                    else {
                        $true++;
                    }

                $sql->closeCursor();
                unset($tipo);
            }

            if($true == 2 or $true == 4) {
                #echo'<url>index.php#tab_'.$_POST['tab'].'</url>';
                echo'<url>index.php</url>';
            }
    }
    catch(PDOException $e) {
        echo'<span>Erro ao conectar o servidor: '.$e->getMessage().'</span>';
    }
?>
