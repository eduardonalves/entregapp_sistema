
<?php
	$this->response->header('Access-Control-Allow-Origin','*');
	$this->response->header('Access-Control-Allow-Methods','POST');
	$this->response->header('Access-Control-Allow-Headers','X-Requested-With');
	$this->response->header('Access-Control-Max-Age','172800');
	
	echo json_encode($itensdepedidos);
?>
