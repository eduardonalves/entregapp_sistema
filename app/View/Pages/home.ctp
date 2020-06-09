<style>
body{
	height: 80% !important;
}

.logo-home{
	max-width: 30%;
	margin: 0 auto;
	display: block;
}

</style>
<?php
if($verRelatorio){
?>
<div class="row">
	<div class="col-md-6" >
		<h3>Vendas Mensais</h3>
		<div style="background-color: white; padding: 20px;">
			
			<canvas id="myChart"></canvas>	
		</div>
	</div>
	<div class="col-md-6" >
		<h3>Produtos Mais Vendidos</h3>
		<div style="background-color: white; padding: 20px;">
			<canvas id="myChart2"></canvas>	
		</div>
	</div>
</div>
<div class="row">
	<div class="col-md-6" >
		<h3>Vendas por Semana</h3>
		<div style="background-color: white; padding: 20px;">
			
			<canvas id="myChart3"></canvas>	
		</div>
	</div>
	<div class="col-md-6" >
		<h3>Vendas por Dia</h3>
		<div style="background-color: white; padding: 20px;">
			
			<canvas id="myChart4"></canvas>	
		</div>
	</div>
</div>

<script>
$(document).ready(function() {
	var urlInicio      = window.location.host;
	
	if(urlInicio=="localhost" ){
		urlInicio = "entregapp_sistema";	
	} 
	//console.log(urlInicio);
	url=  'Pedidos/getchartsales.json';
	
	$.ajax({
				type: "GET",
				url: url,
				dataType: 'json',

				success: function(data){
					console.log(data.resultados);
					var ctx = document.getElementById('myChart');
					
					var myChart = new Chart(ctx,data.resultados);
				},error: function(data){

				}
	});

	url2=  'Pedidos/getchartproducts.json';
	$.ajax({
				type: "GET",
				url: url2,
				dataType: 'json',

				success: function(data){
					console.log(data.resultados);
					var ctx = document.getElementById('myChart2');
					
					var myChart = new Chart(ctx,data.resultados);
				},error: function(data){

				}
	});

	url3=  'Pedidos/getchartsalesbyweek.json';
	$.ajax({
				type: "GET",
				url: url3,
				dataType: 'json',

				success: function(data){
					console.log(data.resultados);
					var ctx = document.getElementById('myChart3');
					
					var myChart = new Chart(ctx,data.resultados);
				},error: function(data){

				}
	});

	url4=  'Pedidos/getchartsalesbyday.json';
	$.ajax({
				type: "GET",
				url: url4,
				dataType: 'json',

				success: function(data){
					console.log(data.resultados);
					var ctx = document.getElementById('myChart4');
					
					var myChart = new Chart(ctx,data.resultados);
				},error: function(data){

				}
	});
});

</script>
<?php
}else{
	echo $this->html->image('entregap-home.png',array('class'=>'logo-home'));
}	 
	
	

?>
