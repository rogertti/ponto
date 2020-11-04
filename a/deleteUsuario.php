<?php
    /* EXCLUIR USUARIO */

    try {
        include_once('../conexao.php');

        $py = md5('idlogin');

        //EXCLUINDO OS REGISTROS DO USUARIO

        $sql = $pdo->prepare("DELETE FROM registro WHERE login_idlogin = :idlogin");
        $sql->bindParam(':idlogin', $_GET[''.$py.''], PDO::PARAM_INT);
        $res = $sql->execute();
        
            if(!$res) {
                var_dump($sql->errorInfo());
                exit;
            } else {
                $sql->closeCursor();
            }
        
        //EXCLUINDO O USUARIO
        
        $sql = $pdo->prepare("DELETE FROM login WHERE idlogin = :idlogin");
        $sql->bindParam(':idlogin', $_GET[''.$py.''], PDO::PARAM_INT);
        $res = $sql->execute();

            if(!$res) {
                var_dump($sql->errorInfo());
                exit;
            } else {
                $sql->closeCursor();

                //ALTERANDO O AUTOINCREMENTO

                $sql2 = $pdo->prepare("SELECT idlogin FROM login");
                $res2 = $sql2->execute();
                $ret2 = $sql2->rowCount();

                    if($ret2 > 0) {
                        $idlogin = $pdo->lastInsertId();
                        $idlogin++;
                        $sql2 = $pdo->prepare("ALTER TABLE login AUTO_INCREMENT = :idlogin");
                        $sql2->bindParam(':idlogin', $idlogin, PDO::PARAM_INT);
                        $res2 = $sql2->execute();

                            if(!$res2) {
                                var_dump($sql2->errorInfo());
                                exit;
                            } else {
                                header('location:usuario.php');
                            }

                        $sql2->closeCursor();
                    } else {
                        $idlogin = 1;
                        $sql2 = $pdo->prepare("ALTER TABLE login AUTO_INCREMENT = :idlogin");
                        $sql2->bindParam(':idlogin', $idlogin, PDO::PARAM_INT);
                        $res2 = $sql2->execute();

                            if(!$res2) {
                                var_dump($sql2->errorInfo());
                                exit;
                            } else {
                                header('location:usuario.php');
                            }

                        $sql2->closeCursor();
                    }

                unset($sql2,$res2,$ret2,$idlogin);
            }
        
        $sql->closeCursor();
        unset($pdo,$sql,$res,$py,$usuario);
    } catch(PDOException $e) {
        echo 'Erro ao conectar o servidor '.$e->getMessage();
    }
?>