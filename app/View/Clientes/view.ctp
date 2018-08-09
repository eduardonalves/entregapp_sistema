<!-- Modal -->
<div class="modal fade modal-grande modal-viewCliente" id="modalLoaded" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"> Cadastrar Clientes</h4>
      </div>
      <div class="modal-body">

				<span class="loadingCep" id="loadingCep"></span>
				<div style="clear:both;"></div>
				<!--<div class="circulodivGrande">
					<?php
						if(isset($cliente['Cliente']['foto']) && $cliente['Cliente']['foto'] != ''){
					?>
							<img src=<?php echo $cliente['Cliente']['foto'];?> alt=<?php echo $cliente['Cliente']['foto']; ?> title=<?php echo $cliente['Cliente']['foto']; ?>width="100px" height="100px"/>
					<?php
						}else{
							echo $this->html->image('fotoico.png', array('alt'=> 'Foto', 'width'=>'100px','height'=>'100px'));

						}
					?>
				</div>-->
				<?php echo $this->Form->create('Cliente',array('class' => 'form-inline centralizadoForm','enctype'=>'multipart/form-data'));	?>

					<?php echo $this->Form->input('id',array('readonly' => 'readonly','default'=> $cliente['Cliente']['id'],'class' => 'input-large idView','id'=>'idView','label' => array('text' => 'Código: ')));?>

				<div class="form-group  form-group-lg">
					<?php echo $this->Form->input('nome',array('readonly' => 'readonly','default'=> $cliente['Cliente']['nome'],'class' => 'input-default nomeView','id'=>'nomeView','label' => array('text' => 'Nome: ')));?>
				</div>

				<div class="form-group  form-group-lg">
					<?php echo $this->Form->input('telefone',array('readonly' => 'readonly','default'=> $cliente['Cliente']['telefone'],'class' => 'input-default telefoneView','id'=>'telefoneView','label' => array('text' => 'Telefone*: ')));?>
				</div>
				<div class="form-group  form-group-lg">
					<?php echo $this->Form->input('celular',array('readonly' => 'readonly','default'=> $cliente['Cliente']['celular'],'class' => 'input-default celularView','id'=>'celularView','label' => array('text' => 'Celular: ')));?>
				</div>
				<?php
					if(isset($cliente['Cliente']['nasc'])){
						$dataNasc=explode('-', $cliente['Cliente']['nasc']) ;
						$nasc = $dataNasc['2'].'/'.$dataNasc['1'].'/'.$dataNasc['0'];
					}else{
						$nasc ='';
					}


				?>
				<div class="form-group  form-group-lg">
					<?php echo $this->Form->input('nasc',array('readonly' => 'readonly','default'=> $nasc,'type' => 'text','class' => 'input-default nascView','id'=>'nascView','label' => array('text' => 'Dt. Nasc: ')));?>
				</div>
				<div class="form-group  form-group-lg">
					<?php echo $this->Form->input('cep',array('default'=> $cliente['Cliente']['cep'],'readonly' => 'readonly','class' => 'input-default cepView','id'=>'cepView','label' => array('text' => 'CEP: ')));?>
				</div>
				<div class="form-group  form-group-lg">
					<?php echo $this->Form->input('logradouro',array('readonly' => 'readonly','default'=> $cliente['Cliente']['logradouro'],'class' => 'input-default logradouroView','id'=>'logradouroView','label' => array('text' => 'Logradouro*: ')));?>
				</div>
				<div class="form-group  form-group-lg">
					<?php echo $this->Form->input('numero',array('readonly' => 'readonly','default'=> $cliente['Cliente']['numero'],'class' => 'input-default numeroView','id'=>'numeroView','label' => array('text' => 'Numero*: ')));?>
				</div>
				<div class="form-group  form-group-lg">
					<?php echo $this->Form->input('complemento',array('readonly' => 'readonly','default'=> $cliente['Cliente']['complemento'],'class' => 'input-default complementoView','id'=>'complementoView','label' => array('text' => 'Complemento: ')));?>
				</div>
				<div class="form-group  form-group-lg">
					<?php echo $this->Form->input('bairro',array('readonly' => 'readonly','default'=> $cliente['Cliente']['bairro'],'class' => 'input-default bairroView','id'=>'bairroView','label' => array('text' => 'Bairro* : ')));?>
				</div>
				<div class="form-group  form-group-lg">
					<?php echo $this->Form->input('cidade',array('readonly' => 'readonly','default'=> $cliente['Cliente']['cidade'],'class' => 'input-default cidadeView','id'=>'cidadeView','label' => array('text' => 'Cidade*: ')));?>
				</div>
				<div class="form-group  form-group-lg">
					<?php echo $this->Form->input('uf',array('readonly' => 'readonly','default'=> $cliente['Cliente']['uf'],'class' => 'input-default ufView','id'=>'ufView','label' => array('text' => 'UF: ')));?>
				</div>
				<div class="form-group  form-group-lg">
					<?php echo $this->Form->input('p_referencia',array('readonly' => 'readonly','default'=> $cliente['Cliente']['p_referencia'],'class' => 'input-default p_referenciaView','id'=>'p_referenciaView','label' => array('text' => 'Ponto de Referência: ')));?>
				</div>
				<div class="form-group  form-group-lg">
					<?php echo $this->Form->input('email',array('readonly' => 'readonly','default'=> $cliente['Cliente']['email'],'class' => 'input-default emailView','id'=>'emailView','label' => array('text' => 'Email: ')));?>
				</div>




			<?php echo $this->Form->input('lat',array('default'=> $cliente['Cliente']['lat'],'class' => 'input-default latView','id'=>'latView','type' => 'hidden'));?>

					<?php echo $this->Form->input('lng',array('default'=> $cliente['Cliente']['lng'],'class' => 'input-default lngView','id'=>'lngView','type' => 'hidden'));?>


				<?php echo $this->Form->input('distancia',array('default'=> $cliente['Cliente']['distancia'],'class' => 'input-default distanciaView','id'=>'distanciaView','type' => 'hidden'));?>
				<?php echo $this->Form->input('duracao',array('default'=> $cliente['Cliente']['duracao'],'class' => 'input-default duracaoView','id'=>'duracaoView','type' => 'hidden'));?>

		<div id="mapaView"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>

      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function() {

	setTimeout(function(){

		initialize();
	}, 2000);

	function initialize() {
	 	var latEdit = $('.latView').val();
	 	var lngEdit = $('.lngView').val();

	    var latlng = new google.maps.LatLng(latEdit, lngEdit);

	    var options = {
	        zoom: 13,
	        center: latlng,
			scrollwheel:true,
	        mapTypeId: google.maps.MapTypeId.ROADMAP
	    };

	    map = new google.maps.Map(document.getElementById("mapaView"), options);
	    position = new google.maps.LatLng(latEdit,lngEdit);

	 	marker = new google.maps.Marker({
	        position: position,
	        map: map,
	       // draggable: true
	    });
	}
});
