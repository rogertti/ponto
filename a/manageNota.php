<?php
    /* CONTROLE DE VARIAVEL */

    $msg = "Campo obrigat&oacute;rio vazio.";

        if(empty($_POST['rand'])) { die("Vari&aacute;vel de controle nula."); }
        if(empty($_POST['idlogin'])) { die("Vari&aacute;vel de controle nula."); }
        if(empty($_POST['mes'])) { die("Vari&aacute;vel de controle nula."); }
        if(empty($_POST['ano'])) { die("Vari&aacute;vel de controle nula."); }
        if(empty($_POST['texto'])) { die($msg); } else {
            $filtro = 1;

            $_POST['texto'] = str_replace("'","&#39;",$_POST['texto']);
            $_POST['texto'] = str_replace('"','&#34;',$_POST['texto']);
            $_POST['texto'] = str_replace('%','&#37;',$_POST['texto']);
        }

        if($filtro == 1) {
            try {
                include_once('../conexao.php');

                //Verifica se o registro existe na tabela
                //Se não existir, insere. Se exisitir, atualiza

                $sql = $pdo->prepare("SELECT texto FROM nota WHERE login_idlogin = :idlogin AND mes = :mes AND ano = :ano");
                $sql->bindParam(':idlogin', $_POST['idlogin'], PDO::PARAM_INT);
                $sql->bindParam(':mes', $_POST['mes'], PDO::PARAM_STR);
                $sql->bindParam(':ano', $_POST['ano'], PDO::PARAM_STR);
                $res = $sql->execute();
                $ret = $sql->rowCount();

                    if($ret > 0) {
                        $sql2 = $pdo->prepare("UPDATE nota SET texto = :texto WHERE login_idlogin = :idlogin");
                        $sql2->bindParam(':texto', $_POST['texto'], PDO::PARAM_STR);
                        $sql2->bindParam(':idlogin', $_POST['idlogin'], PDO::PARAM_INT);
                        $res2 = $sql2->execute();

                            if(!$res2) {
                                var_dump($sql2->errorInfo());
                                exit;
                            } else {
                                echo'true';
                            }

                        $sql2->closeCursor();
                    } else {
                        $sql2 = $pdo->prepare("INSERT INTO nota (login_idlogin,mes,ano,texto) VALUES (:idlogin,:mes,:ano,:texto)");
                        $sql2->bindParam(':idlogin', $_POST['idlogin'], PDO::PARAM_INT);
                        $sql2->bindParam(':mes', $_POST['mes'], PDO::PARAM_STR);
                        $sql2->bindParam(':ano', $_POST['ano'], PDO::PARAM_STR);
                        $sql2->bindParam(':texto', $_POST['texto'], PDO::PARAM_STR);
                        $res2 = $sql2->execute();

                            if(!$res2) {
                                var_dump($sql2->errorInfo());
                                exit;
                            } else {
                                echo'true';
                            }

                        $sql2->closeCursor();
                    }

                $sql->closeCursor();
                unset($sql,$res,$ret,$sql2,$res2);
            } catch(PDOException $e) {
                echo'Falha ao conectar o servidor '.$e->getMessage();
            }
        } //if filtro

    unset($msg,$filtro);
?>