<div class="monitorar-container" ng-app="EntregApp">
	<div class="" ng-controller="monitorarCrtl">


	<style>
		#modalBodyentrega{height: 130px; overflow: hidden; padding-top: 73px; }
		.close{color:#fff;}
		th{
			background-color:#E87C00;
			color:#FFFFFF;

		}
	table {margin-top: 10px; font-size:80%;}



	.table-striped tbody tr:nth-child(odd) td {
		 background-color: #E8E8E8;
	}

	.table-striped tbody tr:nth-child(even) td {
		 background-color: #F9F9F9;
	}

	</style>

	<!--<h2><?php //echo __('Solicitações Feitas'); ?></h2>-->
		<!-- Modal -->



		<div class="row-fluid">

			<?php
				echo $this->Search->create('Itensdeentrega', array('class'=> 'form-inline'));
			?>



			<div class="form-group  form-group-lg">
				<?php


			echo $this->Search->input('minhaslojas', array('label' => 'Loja','class'=>'filtroentrega input-default ', 'required' =>'false'));

		?>
			</div>
			<div class="form-group  form-group-lg">
				<?php




			echo $this->Search->input('setor', array('label' => 'Setor','class'=>'filtroentrega input-default ', 'required' =>'false'));


			?>
			</div>
			<div class="form-group  form-group-lg">
				<?php






				echo $this->Search->input('entrega', array('label' => 'Entrega','class'=>'filtroentrega input-default ', 'required' =>'false'));
			?>
			</div>
			<!--<div class="form-group  form-group-lg">-->
				<?php

				//echo $this->Search->input('preparo', array('label' => 'Preparo','class'=>'filtroentrega input-default ', 'required' =>'false'));
			?>
			<!--</div>-->
			<div class="form-group  form-group-lg">
				<?php
				echo $this->Search->end(__('Filtrar', true));
				?>
			</div>
			<h2 class="h1entrega">Entrega</h2>
			<div class="quadradoEntrega" ng-repeat="data in filtered = (list | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
				<h4 class="entregaIdentificacao">{{data.Entrega.identificacao}}</h4>
				<div class="circuloExterno entrega{{data.Entrega.status_pedido}}Externa">
					<div class="circuloInterno entrega{{data.Entrega.status_pedido}}Interna">
						<div class="pedidosEntrega">
							<span>{{data.Entrega.status_pedido}}</span>

						</div>
					</div>
				</div>
				<div class="holderButtonEntrega">
					<!--<p>
						Valor total: R$ {{data.Entrega.total_aberto}}
					</p>
					<p>
						Valor Pago: R$ 00,00
					</p>-->
					<button type="button" name="button" class="btn btn-default editModal" data-id="{{data.Entrega.id}}">Ações</button>
					<!--<button type="button" name="button" class="btn btn-warning">Trocar</button>
					<button type="button" name="button" class="btn btn-danger">Fechar</button>
				</div>-->

			</div>
		<!--	<div class="quadradoEntrega">
				<h4 class="entregaIdentificacao">Identificação da Entrega</h4>
				<div class="circuloExterno entregaOcupadaExterna">
					<div class="circuloInterno entregaOcupadaInterna">
						<div class="pedidosEntrega">
							<span>O</span>
						</div>
					</div>
				</div>
			</div>
			<div class="quadradoEntrega">
				<h4 class="entregaIdentificacao">Identificação da Entrega</h4>
				<div class="circuloExterno entregaAtencaoExterna">
					<div class="circuloInterno entregaAtencaoInterna">
						<div class="pedidosEntrega">
							<span>O</span>
						</div>
					</div>
				</div>
			</div>-->

		</div>
		<div class="col-md-12" ng-show="filteredItems == 0">
				<div class="col-md-12">
						<h4>Não encontramos solicitações</h4>
				</div>
		</div>
	<!--	<div class="col-md-12" ng-show="filteredItems > 0">
				<div pagination="" page="currentPage" on-select-page="setPage(page)" boundary-links="true" total-items="filteredItems" items-per-page="entryLimit" class="pagination-small" previous-text="&laquo;" next-text="&raquo;"></div>


		</div>-->

		<div id="loadDivModal"></div>
	</div>
</div>

<script>
console.log(window.location.href );
var app = angular.module('EntregApp', ['ui.bootstrap']);

app.filter('startFrom', function() {
    return function(input, start) {
        if(input) {
            start = +start; //parse to int
            return input.slice(start);
        }
        return [];
    }
});
app.controller('monitorarCrtl', function ($scope, $http, $timeout) {
		$http.get(window.location.href+'/?json=true').success(function(data){
			console.log(data);
			$scope.strip(data);
			$scope.list = data;
				$scope.currentPage = 1; //current page
				$scope.entryLimit = 1000; //max no of items to display in a page
				$scope.filteredItems = $scope.list.length; //Initially for no filter
				$scope.totalItems = $scope.list.length;
		});
		$scope.countUp = function(){
			$http.get(window.location.href+'/?json=true').success(function(data){
				console.log(data);
				$scope.strip(data);
				$scope.list = data;
					$scope.currentPage = 1; //current page
					$scope.entryLimit = 1000; //max no of items to display in a page
					$scope.filteredItems = $scope.list.length; //Initially for no filter
					$scope.totalItems = $scope.list.length;
					//console.log(data);
			});
		}
		setInterval(function (){
			$scope.countUp();
		},30000);
		var urlInicio      = window.location.host;
		urlInicio = (urlInicio=='localhost' ? urlInicio+'/entregapp': urlInicio);

		$scope.confirmar = function(id){
			var txt;
	    var r = confirm("Deseja mesmo executar esta ação ?");
	    if (r == true) {
				var data = $.param({
								 id:id

						 });
				var config = {
						 headers : {
								 'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8;'
						 }
				 }
				 console.log(id);
				$http.post(window.location.href, data, config)
				.then(
					 function(response){
						 // success callback
						 console.log(response);
						 location.reload();
					 },
					 function(response){
						 // failure callback
						 console.log(response);
					 }
				);
	    } else {

	    }

		};
		$scope.strip = function (html)
		{
		   var tmp = document.createElement("DIV");
		   tmp.innerHTML = html;
			 
		   return tmp.textContent || tmp.innerText || "";
		}
    $scope.setPage = function(pageNo) {
        $scope.currentPage = pageNo;
    };
    $scope.filter = function() {
        $timeout(function() {
            $scope.filteredItems = $scope.filtered.length;
        }, 10);
    };
    $scope.sort_by = function(predicate) {
        $scope.predicate = predicate;
        $scope.reverse = !$scope.reverse;
    };
});


$(document).ready(function() {

	$('#myModal').on('shown.bs.modal', function () {
	  $('#myInput').focus()
	})
loja = $('#filterMinhaslojas').val();
	 $('#entregaFilialId').val(loja);
$("#clickmodal").click(function(){
$('#responsive').modal('show');
});
var urlInicio      = window.location.host;
urlInicio = (urlInicio=='localhost' ? urlInicio+'/entregapp': urlInicio);
//setInterval(function(){
	//alert();
//	location.reload();

//},30000);
$('body').on('click','.editModal', function(event){
		event.preventDefault();

		modalid = $(this).data('id');
		$(this).attr('src','/img/ajax-loader.gif');

	//	$('#loaderGif'+identrega).show();
	//	$('#divActions'+identrega).hide();
		$("#loadDivModal").load('http://'+urlInicio+'/entregas/acoes/'+modalid+'', function(){
			$('#modalLoaded').modal('show');
			 $('.editModal').attr('src','/img/tb-edit.png');
			/*$('#loaderGif'+identrega).hide();
			$('#divActions'+identrega).show();
			 $('#counter').countdown({
	          image: 'http://'+urlInicio+'/img/digits2.png',
	          startTime: '00:10:00',
			  digitWidth: 34,
			    digitHeight: 45,
			    format: 'hh:mm:ss',
			});*/

		});
	});
	$('body').on('click','.viewModal', function(event){
		event.preventDefault();

		modalid = $(this).data('id');


	//	$('#loaderGif'+identrega).show();
	//	$('#divActions'+identrega).hide();
		$("#loadDivModal").load('http://'+urlInicio+'/entregas/view/'+modalid+'', function(){
			$('#modalLoaded').modal('show');
			/*$('#loaderGif'+identrega).hide();
			$('#divActions'+identrega).show();
			 $('#counter').countdown({
	          image: 'http://'+urlInicio+'/img/digits2.png',
	          startTime: '00:10:00',
			  digitWidth: 34,
			    digitHeight: 45,
			    format: 'hh:mm:ss',
			});*/

		});
	});

/*$("#responsive").on("show", function () {
  $("body").addClass("modal-open");
}).on("hidden", function () {
  $("body").removeClass("modal-open")
});*/
});


</script>
