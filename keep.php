<?php
    require_once 'config.php';

    function diaSemana() {
        $diasemana = array('domingo', 'segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado');
        $data = date('Y-m-d');
        // Varivel que recebe o dia da semana (0 = Domingo, 1 = Segunda ...)
        $diasemana_numero = date('w', strtotime($data));
        
        return $diasemana[$diasemana_numero];
    }

    #echo diaSemana();

    function diaFeriado($datado) {
        $url = 'https://api.calendario.com.br/?json=true&ano=2017&ibge=4203204&token=cm9nZXJ0dGlAcG0ubWUmaGFzaD0xNjc4MTY0MTk';
        $data = file_get_contents($url);
        $feriados = json_decode($data);

            foreach($feriados as $feriado) {
                if(($feriado->type_code == '1') or ($feriado->type_code == '3')) {
                    $dia = substr($feriado->date,0,2);
                    $mes = substr($feriado->date,3,2);
                    $feriado->date = $dia.'/'.$mes;
                    #echo $feriado->date.'<br>';

                        if($feriado->date == $datado) {
                            return true;
                        }
                }
            }
    }

    #echo diaFeriado('07/09');

    function nomeFeriado($datado) {
        $url = 'https://api.calendario.com.br/?json=true&ano=2017&ibge=4203204&token=cm9nZXJ0dGlAcG0ubWUmaGFzaD0xNjc4MTY0MTk';
        $data = file_get_contents($url);
        $feriados = json_decode($data);

            foreach($feriados as $feriado) {
                if(($feriado->type_code == '1') or ($feriado->type_code == '3')) {
                    $dia = substr($feriado->date,0,2);
                    $mes = substr($feriado->date,3,2);
                    $feriado->date = $dia.'/'.$mes;

                        if($feriado->date == $datado) {
                            return $feriado->name;
                        }
                } 
            }
    }

    #echo nomeFeriado('07/09');
?>