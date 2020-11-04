<?php
    require_once('config.php');

        if (empty($_GET['hora'])) {
            die();
        } else {
            $filtro = 1;
            $hora = explode(':', $_GET['hora']);

            if (strlen($hora[0]) == 1) {
                $hora[0] = '0'.$hora[0];
            }

            if (strlen($hora[1]) == 1) {
                $hora[1] = '0'.$hora[1];
            }

            if (strlen($hora[2]) == 1) {
                $hora[2] = '0'.$hora[2];
            }

            $_GET['hora'] = $hora[0].':'.$hora[1].':'.$hora[2];
        }

        if (empty($_GET['data'])) {
            die();
        } else {
            $filtro++;
            $dia = substr($_GET['data'], 0, 2);
            $mes = substr($_GET['data'], 3, 2);
            $ano = substr($_GET['data'], 6);
            $_GET['data'] = $ano."-".$mes."-".$dia;
        }

        if ($filtro == 2) {
            try {
                include_once('conexao.php');

                //DESCOBRE O PERIODO

                $_SESSION['id'] = (int)$_SESSION['id'];

                $sql = $pdo->prepare("SELECT tipo FROM registro WHERE login_idlogin = :idlogin AND dia = :dia AND mes = :mes AND ano = :ano");
                $sql->bindParam(':idlogin', $_SESSION['id'], PDO::PARAM_INT);
                $sql->bindParam(':dia', $dia, PDO::PARAM_STR);
                $sql->bindParam(':mes', $mes, PDO::PARAM_STR);
                $sql->bindParam(':ano', $ano, PDO::PARAM_STR);
                $res = $sql->execute();
                $ret = $sql->rowCount();

                if ($ret == 0) {
                    $tipo = 'PHM';
                } else {
                    $tipos = array('PHM','SHM','PHT','SHT');
                    $tipo_found = array();

                    while ($lin = $sql->fetch(PDO::FETCH_OBJ)) {
                        $tipo_found[] = $lin->tipo;
                    }
                        
                    $tipo = array_diff($tipos, $tipo_found);
                        
                    if (!empty($tipo)) {
                        $tipo = array_values($tipo);
                        $tipo = $tipo[0];
                    } else {
                        die('Em todos os per&iacute;odos desse dia, o ponto j&aacute; foi registrado.');
                    }
                }

                $sql->closeCursor();

                //EVITA DUPLICATA

                $sql = $pdo->prepare("SELECT tipo FROM registro WHERE login_idlogin = :idlogin AND tipo = :tipo AND dia = :dia AND mes = :mes AND ano = :ano");
                $sql->bindParam(':idlogin', $_SESSION['id'], PDO::PARAM_INT);
                $sql->bindParam(':tipo', $tipo, PDO::PARAM_STR);
                $sql->bindParam(':dia', $dia, PDO::PARAM_STR);
                $sql->bindParam(':mes', $mes, PDO::PARAM_STR);
                $sql->bindParam(':ano', $ano, PDO::PARAM_STR);
                $res = $sql->execute();
                $ret = $sql->rowCount();

                if ($ret > 0) {
                    die('O ponto j&aacute; foi registrado nesse per&iacute;odo.');
                }

                $sql->closeCursor();

                //REGISTRA O PONTO

                $sql = $pdo->prepare("INSERT INTO registro (login_idlogin,tipo,dia,mes,ano,hora) VALUES (:idlogin,:tipo,:dia,:mes,:ano,:hora)");
                $sql->bindParam(':idlogin', $_SESSION['id'], PDO::PARAM_INT);
                $sql->bindParam(':tipo', $tipo, PDO::PARAM_STR);
                $sql->bindParam(':dia', $dia, PDO::PARAM_STR);
                $sql->bindParam(':mes', $mes, PDO::PARAM_STR);
                $sql->bindParam(':ano', $ano, PDO::PARAM_STR);
                $sql->bindParam(':hora', $_GET['hora'], PDO::PARAM_STR);
                $res = $sql->execute();

                if (!$res) {
                    $sql = $sql->errorInfo();
                    die($sql[2]);
                } else {
                    echo'true';
                }

                $sql->closeCursor();
            } catch (PDOException $e) {
                echo'<span>Erro ao conectar o servidor: '.$e->getMessage().'</span>';
            }
        } else {
            echo'Par&acirc;metro incorreto.';
        }

    unset($cfg,$filtro,$dia,$mes,$ano,$sql,$res,$tipo,$pdo,$e,$hora,$data);
