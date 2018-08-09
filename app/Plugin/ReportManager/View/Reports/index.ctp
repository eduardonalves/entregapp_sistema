<?php
/*
Copyright (c) 2012 Luis E. S. Dias - www.smartbyte.com.br

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
?>
<script type="text/javascript">
    firstLevel = "<?php echo Router::url('/'); ?>";
</script>
<?php

?>
<?php echo $this->Html->script(array('https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js')); ?>
<?php echo $this->Html->script('/ReportManager/js/index.js'); ?>
<?php echo $this->Html->css('/ReportManager/css/report_manager'); ?>

<style>
	.input-relatorio{
		height: 32px !important;
	}
	
	.span3{
		margin-left:0px;
	}
	div.controls{
		padding: 0;
		clear: both;
	}
	#ReportManagerOneToManyOptionSelect{
		padding: 0;
	}
	.button-excluir{
	  padding: 6px 12px !important;
	  position: relative;
	  top: 73px;
	  left: -200px;
	}
</style>

<div class="reportManager index">
    <h2><?php echo __('Relatórios');?></h2>
    <?php
        
        echo '<div id="repoManLeftCol">';
        echo $this->Form->create('ReportManager');
        echo '<fieldset>';
			echo '<legend>' . __('Novo Relatório') . '</legend>';        
			echo '<section class="span3">'; 
				echo $this->Form->input('model',array(
					'type'=>'select',            
					'class'=>'input-relatorio',
					'label'=>__('Tabela:'),
					'options'=>$models,
					'empty'=>__('--Selecionar--')
					));
			echo '</section>';        
			
			echo '<section class="span3">'; 
				echo '<div id="ReportManagerOneToManyOptionSelect">';
				echo $this->Form->input('one_to_many_option',array(
					'type'=>'select',
					'class'=>'input-relatorio',
					'label'=>__('Opção de um para muitos:'),
					'options'=>array(),
					'empty'=>__('<Nenhum>')
					));
				echo '</div>';
			echo '</section>';        
			
        echo '</fieldset>';
        echo $this->Form->submit(__('Novo'),array('name'=>'new','class'=>'btn btn-success'));
        echo '</div>';
        
        echo '<div id="repoManMiddleCol">';        
			echo $this->Html->tag('h2','OU');        
        echo '</div>';
        
        echo '<div id="repoManRightCol">';
        echo $this->Form->create('ReportManager');
        echo '<fieldset>';
        echo '<legend>' . __('Abrir Relatório') . '</legend>';        
        
			echo '<section class="span3">'; 
				echo '<div id="ReportManagerSavedReportOptionContainer">';
					echo $this->Form->input('saved_report_option',array(
						'type'=>'select',
						'class'=>'input-relatorio',
						'label'=>__('Relatórios Gravados:'),
						'options'=>$files,
						'empty'=>__('--Selecionar--')
						));
				echo '</div>';
			echo '</section>';
        echo '<button type="button" class="button-excluir btn btn-default deleteReport">Excluir</button>';
        echo '</fieldset>';
        echo $this->Form->submit(__('Abrir'),array('name'=>'load','class'=>'btn btn-success'));
        echo '</div>';
    ?>
</div>
