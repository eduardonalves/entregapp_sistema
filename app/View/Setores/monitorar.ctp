<div class="monitorar-container" ng-app="EntregApp">
	<div class="" ng-controller="monitorarCrtl">


	<style>
		#modalBodySetore{height: 130px; overflow: hidden; padding-top: 73px; }
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
table{
	text-align: center;
}
	</style>

	<h2><?php echo __('Solicitações Para Fazer'); ?></h2>
		<!-- Modal -->
		<div class="modal fade modal-grande" id="responsive" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel"> Cadastrar Setor</h4>
					</div>
					<div class="modal-body">
					<div class="row-fluid">
					<div >
						<?php echo $this->Form->create('Setore',array('class' => 'form-inline centralizadoForm'));
							?>
						<div class="form-group  form-group-lg">
							<?php
							echo $this->Form->input('setor',array('class' => 'input-large','label' => array('text' => 'Setore', 'class' => 'labellarge')));
							//echo $this->Form->input('destaque',array('type'=>'checkbox','label' => array('text' => 'Destaque')));
							echo $this->Form->input('ativo',array('type'=>'checkbox','label' => array('text' => 'Ativo')));
							echo $this->Form->input('filial_id',array('type' => 'hidden'));
							?>
						</div>
					</div>
				</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
						<button type="submit" class="btn btn-default">Salvar</button>
						<?php echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
		</div>


		<div class="row-fluid">

			<?php
				echo $this->Search->create('Itensdepedido', array('class'=> 'form-inline'));
			?>



			<div class="form-group  form-group-lg">
				<?php


			echo $this->Search->input('minhaslojas', array('label' => 'Loja','class'=>'filtroPedido input-default ', 'required' =>'false'));

		?>
			</div>
			<div class="form-group  form-group-lg">
				<?php




			echo $this->Search->input('setor', array('label' => 'Setor','class'=>'filtroPedido input-default ', 'required' =>'false'));


			?>
			</div>
			<div class="form-group  form-group-lg">
				<?php






				echo $this->Search->input('mesa', array('label' => 'Mesa','class'=>'filtroPedido input-default ', 'required' =>'false'));
			?>
			</div>
			<!--<div class="form-group  form-group-lg">-->
				<?php

				//echo $this->Search->input('preparo', array('label' => 'Preparo','class'=>'filtroPedido input-default ', 'required' =>'false'));
			?>
			<!--</div>-->
			<div class="form-group  form-group-lg">
				<?php
				echo $this->Search->end(__('Filtrar', true));
				?>
			</div>
			<div class="area-tabela"  id="no-more-tables">
					<table class="table-action col-md-12 table-bordered table-striped table-condensed cf" >
						<thead class="cf">
							<tr>
								<th>Setor</th>
								<th>Destino</th>
								<th>Pedido</th>
								<th>Produto</th>
								<th>Qtde</th>
								<th>Obs</th>


								<th>Ações</th>
							</tr>
						</thead>
						<tbody >

							<tr ng-repeat="data in filtered = (list | filter:search | orderBy : predicate :reverse) | startFrom:(currentPage-1)*entryLimit | limitTo:entryLimit">
								<td data-title="Coluna">{{data.Produto.setor}}</td>
								<td data-title="Coluna">{{data.Pedido.mesa}}</td>
								<td data-title="Coluna">{{data.Pedido.id}}</td>
								<td data-title="Coluna" title="{{data.Produto.descricao}}">{{data.Produto.nome}}</td>
								<td data-title="Coluna">{{data.Itensdepedido.qtde}}</td>
								<td data-title="Coluna">{{data.Itensdepedido.composto_nomeum}} {{data.Itensdepedido.composto_nomedois}} {{strip(data.Itensdepedido.obs_sis)}} {{data.Pedido.obs}} </td>
								<td data-title="Coluna">
									<button type="button" name="button" ng-init="id=data.Itensdepedido.id;" data-id="{{data.Itensdepedido.id}}" class="btn btn-success" ng-click="confirmar(data.Itensdepedido.id);">Pronto</button> </td>
							</tr>

						</tbody>
					</table>

			</div>
		</div>
		<div class="col-md-12" ng-show="filteredItems == 0">
				<div class="col-md-12">
						<h4>Não encontramos solicitações</h4>
				</div>
		</div>
		<!--<div class="col-md-12" ng-show="filteredItems > 0">
				<div pagination="" page="currentPage" on-select-page="setPage(page)" boundary-links="true" total-items="filteredItems" items-per-page="entryLimit" class="pagination-small" previous-text="&laquo;" next-text="&raquo;"></div>


		</div>-->

		<div id="loadDivModal"></div>
	</div>
</div>
<div class="clear" style="clear:both;">

</div>
<br>
<br>
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
		urlInicio = (urlInicio=='localhost' ? urlInicio+'/entregapp_sistema': urlInicio);

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
			 console.log('aqio');
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
	 $('#SetoreFilialId').val(loja);
$("#clickmodal").click(function(){
$('#responsive').modal('show');
});
var urlInicio      = window.location.host;
urlInicio = (urlInicio=='localhost' ? urlInicio+'/entregapp_sistema': urlInicio);
//setInterval(function(){
	//alert();
//	location.reload();

//},30000);
$('body').on('click','.editModal', function(event){
		event.preventDefault();

		modalid = $(this).data('id');
		$(this).attr('src','/img/ajax-loader.gif');

	//	$('#loaderGif'+idpedido).show();
	//	$('#divActions'+idpedido).hide();
		$("#loadDivModal").load('http://'+urlInicio+'/setores/edit/'+modalid+'', function(){
			$('#modalLoaded').modal('show');
			 $('.editModal').attr('src','/img/tb-edit.png');
			/*$('#loaderGif'+idpedido).hide();
			$('#divActions'+idpedido).show();
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


	//	$('#loaderGif'+idpedido).show();
	//	$('#divActions'+idpedido).hide();
		$("#loadDivModal").load('http://'+urlInicio+'/setores/view/'+modalid+'', function(){
			$('#modalLoaded').modal('show');
			/*$('#loaderGif'+idpedido).hide();
			$('#divActions'+idpedido).show();
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
