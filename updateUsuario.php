<?php
    require_once('config.php');

    $msg = "Campo obrigat&oacute;rio vazio.";

        if (empty($_POST['rand'])) { die('Vari&aacute;vel de controle nula.'); }
        if (empty($_POST['idlogin'])) { die('reload'); }
        if (empty($_POST['nome'])) { die(); } else { 
            $filtro = 1;
            $_POST['nome'] = str_replace("'","&#39;",$_POST['nome']);
            $_POST['nome'] = str_replace('"','&#34;',$_POST['nome']);
            $_POST['nome'] = str_replace('%','&#37;',$_POST['nome']);
        }
        if (empty($_POST['usuario'])) { die(); } else { $filtro++; }
        if (empty($_POST['senha'])) { die(); } else { $filtro++; }
        if (empty($_POST['email'])) { die(); } else { $filtro++; }

        if ($filtro == 4) {
            try {
                include_once('conexao.php');
                
                //EVITA O PADRAO
                
                $sql = $pdo->prepare("SELECT idlogin FROM login WHERE usuario = :usuario AND senha = :senha AND idlogin = :idlogin");
                $sql->bindParam(':usuario', $_POST['usuario'], PDO::PARAM_STR);
                $sql->bindParam(':senha', $_POST['senha'], PDO::PARAM_STR);
                $sql->bindParam(':idlogin', $_POST['idlogin'], PDO::PARAM_INT);
                $sql->execute();
                $ret = $sql->rowCount();

                    if ($ret > 0) {
                        die('Os dados pr&eacute;-definidos n&atilde;o foram alterados, pelo menos a senha deve ser trocada.');
                    }

                $sql->closeCursor();
                
                //EVITA DUPLICATA

                $sql = $pdo->prepare("SELECT idlogin FROM login WHERE (usuario = :usuario OR email = :email) AND idlogin <> :idlogin");
                $sql->bindParam(':usuario', $_POST['usuario'], PDO::PARAM_STR);
                $sql->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
                $sql->bindParam(':idlogin', $_POST['idlogin'], PDO::PARAM_INT);
                $sql->execute();
                $ret = $sql->rowCount();

                    if ($ret > 0) {
                        die('Esse usu&aacute;rio j&aacute; est&aacute; em uso.');
                    }

                $sql->closeCursor();
                
                //ATUALIZA O USUARIO
                
                $padrao = 'X';
                $sql = $pdo->prepare("UPDATE login SET nome = :nome, usuario = :usuario, senha = :senha, email = :email, padrao = :padrao WHERE idlogin = :idlogin");
                $sql->bindParam(':nome', $_POST['nome'], PDO::PARAM_STR);
                $sql->bindParam(':usuario', $_POST['usuario'], PDO::PARAM_STR);
                $sql->bindParam(':senha', $_POST['senha'], PDO::PARAM_STR);
                $sql->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
                $sql->bindParam(':padrao', $padrao, PDO::PARAM_STR);
                $sql->bindParam(':idlogin', $_POST['idlogin'], PDO::PARAM_INT);
                $res = $sql->execute();
                
                    if (!$res) {
                        $sql = $sql->errorInfo();
                        die($sql[2]);
                    }
                    else {
                        echo'true';
                    }
            }
            catch(PDOException $e) {
                echo'<span>Erro ao conectar o servidor: '.$e->getMessage().'</span>';
            }
        }

    unset($msg,$filtro,$cfg,$pdo,$e,$sql,$res,$ret,$padrao);
?>