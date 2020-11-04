<?php
	session_start();
	#error_reporting(0);
    #ini_set('output_buffering', 4096);
    #ini_set('session.auto_start', 1);
    ini_set('display_errors','on');
    date_default_timezone_set('America/Sao_Paulo');
	$cfg = array(
        'titulo'=>'Ponto',
        'titulo_extenso'=>'<strong>Ponto</strong>',
        'titulo_abreviado'=>'<strong>P</strong>O',
        'descricao'=>'Gerenciador de ponto',
        'empresa'=>'Embracore, Ltda.',
        'endereco1'=>'Rua Nome da Rua, 00',
        'endereco2'=>'Bairro - Cidade - EO',
        'telefone1'=>'(00)0000 0000',
        'telefone2'=>'(00)0000 0000',
        'site'=>'www.site.com.br',
        'email'=>'email@email.com'
    );

    //Time from server?

    $info = getdate();
    $date = $info['mday'];
    $month = $info['mon'];
    $year = $info['year'];
    $hour = $info['hours'];
    $min = $info['minutes'];
    $sec = $info['seconds'];
    #print_r($info);
?>
