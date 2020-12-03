<?php
//verifica se o cep esta setado
$cep = isset($_POST['cep']) ? str_replace(".", "", str_replace("-", "", $_POST['cep'])) : '';
$dados = Array("erro" => "CEP não encontrado.");
//verifica se o CEP é numerico
if(is_numeric($cep)){

	//abre a conexão
	$ch = curl_init();
	//seta a url
	curl_setopt($ch,CURLOPT_URL, 'https://viacep.com.br/ws/'.$cep.'/json/unicode/');
	// Disable SSL verification
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	//seta o retrno como string
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	//executa o post
	$result = curl_exec($ch);
	//fecha a conexão
	curl_close($ch);

	if($result)
		unset($dados['erro']);

	$dados = json_decode($result);
	$dados->cidade = $dados->localidade;

}

//retorna os dados em formato json
echo json_encode($dados);
?>