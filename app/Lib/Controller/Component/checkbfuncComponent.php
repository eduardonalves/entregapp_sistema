<?php

App::uses('Component', 'Controller');

class checkbfuncComponent extends Component {

	public function converteMoedaToView(&$valorMoeda){
		$valorMoedaAux = explode('.' , $valorMoeda);
		if(isset ($valorMoedaAux[1])){
			$valorMoeda= $valorMoedaAux[0].','.$valorMoedaAux[1];
		}else{
			$valorMoeda = $valorMoedaAux[0].','.'00';
		}
		return $valorMoeda;
	}

	public function converterMoedaToBD(&$valorMoeda){
		$valorMoedaAux = explode('.' , $valorMoeda);
		if(isset ($valorMoedaAux[1])){
			$i = 0;

			$convertido='';
			foreach($valorMoedaAux as $valor){
				$convertido =$convertido.$valor;
				$i++;

			}
		}else{
			$convertido = $valorMoedaAux[0];
		}


		$decimal = explode(',' , $convertido);


		if(isset($decimal[1])){
			$valorFinal=$decimal[0].'.'.$decimal[1];
		}else{
			$valorFinal=$decimal[0];
		}
		$valorMoeda=$valorFinal;

		return $valorFinal;
	}


	private function validarData($data)
		{

			$retVal = true;

			if(strlen($data)!=10) { $retVal = false; }

			if (! preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $data)) { $retVal = false; }

			return $retVal;
		}


		public function formatDateToBD(&$data)
		{

			if($this->validarData($data))
			{

				$temp = substr($data, -4);
				$temp .= "-" . substr($data, 3, 2);
				$temp .= "-" . substr($data, 0, 2);

				$data = $temp;

				return true;

			}else{

				return false;
			}

		}

		public function formatDateToView(&$data){
			$dataAux = explode('-', $data);
			if(isset($dataAux['2'])){
				if(isset($dataAux['1'])){
					if(isset($dataAux['0'])){
						$data = $dataAux['2']."/".$dataAux['1']."/".$dataAux['0'];
					}
				}
			}else{
				$data= " / / ";
			}
			return $data;
		}
public function somaHora(&$hora1,&$hora2){


 	if($hora1 == ""){
		$hora1 == "00:00:00";
	}
	if($hora2==""){
		$hora2="00:00:00";
	}
 	$h1 = explode(":",$hora1);
 	$h2 = explode(":",$hora2);

 	$segundo1 = $h1[2] ;
 	$minuto1  = $h1[1];
 	$horas1   = $h1[0];

 	$segundo2 = $h2[2] ;
 	$minuto2  = $h2[1];
 	$horas2   = $h2[0];



	$novo_horario = mktime($horas1 + $horas2, $minuto1 + $minuto2, $segundo1 + $segundo2);

	// Imprime o novo horário no formato HH:MM
	$resultado = date("H:i:s", $novo_horario);

	return $resultado;


}

public function subtraHora(&$hora1,&$hora2){
 	if($hora1 ==""){
		$hora1 == "00:00:00";
	}
	if($hora2==""){
		$hora2="00:00:00";
	}
 	$h1 = explode(":",$hora1);
 	$h2 = explode(":",$hora2);

 	$segundo1 = $h1[2] ;
 	$minuto1  = $h1[1];
 	$horas1   = $h1[0];

 	$segundo2 = $h2[2] ;
 	$minuto2  = $h2[1];
 	$horas2   = $h2[0];

	$novo_horario = mktime($horas1 - $horas2, $minuto1 - $minuto2, $segundo1 - $segundo2);
	// Imprime o novo horário no formato HH:MM
	$result = date("H:i:s", $novo_horario);
 	return $result;
}
public function removeDetritos(&$variavel)
{


	$variavel_mudada= $this->trataTxt(utf8_encode($variavel)); // funciona corretamente
	//$variavel_mudada=preg_replace("/[\s_]/", "", $variavel_mudada);
	$variavel_mudada = strtolower( ereg_replace("[^a-zA-Z0-9-]", "", strtr(utf8_decode(trim($variavel)), utf8_decode("áàãâéêíóôõúüñçÁÀÃÂÉÊÍÓÔÕÚÜÑÇ"),"aaaaeeiooouuncAAAAEEIOOOUUNC-")) );

	return $variavel_mudada;


}





function trataTxt($var) {

	$var = strtolower($var);

	$var = ereg_replace("[áàâãª]","a",$var);
	$var = ereg_replace("[éèê]","e",$var);
	$var = ereg_replace("[óòôõº]","o",$var);
	$var = ereg_replace("[úùû]","u",$var);
	$var = str_replace("ç","c",$var);

	return $var;
}

}
?>
