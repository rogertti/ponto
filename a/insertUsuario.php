<?php
    /* CONTROLE DE VARIAVEL */

    $msg = "Campo obrigat&oacute;rio vazio.";

        if(empty($_POST['rand'])) {die("Vari&aacute;vel de controle nula."); }
        if(empty($_POST['nome'])) { die($msg); } else {
            $filtro = 1;

            $_POST['nome'] = str_replace("'","&#39;",$_POST['nome']);
            $_POST['nome'] = str_replace('"','&#34;',$_POST['nome']);
            $_POST['nome'] = str_replace('%','&#37;',$_POST['nome']);
        }
        if(empty($_POST['usuario'])) { die($msg); } else { $filtro++; }
        if(empty($_POST['senha'])) { die($msg); } else { $filtro++; }
        if(empty($_POST['email'])) { die($msg); } else { $filtro++; }
        if(empty($_POST['tipo'])) { die($msg); } else { $filtro++; }

        if($filtro == 5) {
            try {
                include_once('../conexao.php');

                /* CONTROLE DE DUPLICATAS */

                $sql = $pdo->prepare("SELECT idlogin FROM login WHERE email = :email");
                $sql->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
                $sql->execute();
                $ret = $sql->rowCount();

                    if($ret > 0) {
                        die('Esse email j&aacute; est&aacute; sendo usado.');
                    }

                $sql->closeCursor();
                unset($sql,$ret);

                /* TENTA INSERIR NO BANCO */

                $sql = $pdo->prepare("INSERT INTO login (nome,usuario,senha,email,tipo) VALUES (:nome,:usuario,:senha,:email,:tipo)");
                $sql->bindParam(':nome', $_POST['nome'], PDO::PARAM_STR);
                $sql->bindParam(':usuario', $_POST['usuario'], PDO::PARAM_STR);
                $sql->bindParam(':senha', $_POST['senha'], PDO::PARAM_STR);
                $sql->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
                $sql->bindParam(':tipo', $_POST['tipo'], PDO::PARAM_STR);
                $res = $sql->execute();

                    if(!$res) {
                        var_dump($sql->errorInfo());
                        exit;
                    } else {
                        echo'true';
                    }

                $sql->closeCursor();
                unset($pdo,$sql,$res);
            } catch(PDOException $e) {
                echo'Falha ao conectar o servidor '.$e->getMessage();
            }
        } //if filtro

    unset($msg,$filtro);
?>