    <!-- Copyright (c) 2012 Luis E. S. Dias - www.smartbyte.com.br -->
    <fieldset>
        <legend><?php echo __('Estilo do Relatório'); ?></legend>
        <table class="reportManagerReportStyleSelector" cellpadding="0" cellspacing="0">
	<?php
        $styleOptions = array(
            'executive'=>'Executive',
            'ledger'=>'Ledger',
            'banded'=>'Banded',
            'presentation'=>'Presentation',
            'casual'=>'Casual'
            );
        $outputOptions = array(
            'html' => 'HTML',
            'xls' => 'Excel'
        );
        
            echo '<tr>';
            echo '<td>';
            echo $this->Form->input('ReportName',array('size'=>'80','maxlength'=>'80', 'label'=> 'Nome do Relatório'));            
            echo '</td>';
            echo '</tr>';
            
            echo '<tr>';
            echo '<td>';
            echo $this->Form->input('Style',array('label' => 'Estilo','type'=>'select','options'=>$styleOptions));            
            echo '</td>';             
            echo '</tr>';

            echo '<tr>';
            echo '<td>';
            echo $this->Form->input('Output',array('label'=> 'Saída','type'=>'select','options'=>$outputOptions));            
            echo '</td>';             
            echo '</tr>';
            
            echo '<tr>';
            echo '<td>';
            echo __('Exibir contador de registros');
            if (isset($this->data['Report']['ShowRecordCounter']))
                $showRecordCounter = $this->data['Report']['ShowRecordCounter'];
            else
                $showRecordCounter = true;
            echo $this->Form->checkbox('ShowRecordCounter',array('hiddenField' => true,'checked'=>$showRecordCounter));                     
            echo '</td>';             
            echo '</tr>';            
            
            if ($oneToManyOption != '') {
                echo '<tr>';
                echo '<td>';
                echo __('Exibir itens sem registros relacionados');
                if (isset($this->data['Report']['ShowNoRelated']))
                    $showNoRelated = $this->data['Report']['ShowNoRelated'];
                else
                    $showNoRelated = false;
                echo $this->Form->checkbox('ShowNoRelated',array('hiddenField' => true,'checked'=>$showNoRelated));
                echo '</td>';             
                echo '</tr>';
            }
            
            echo '<tr>';
            echo '<td>';
            echo __('Salvar relatório');
            if (isset($this->data['Report']['SaveReport']))
                $saveReport = $this->data['Report']['SaveReport'];
            else
                $saveReport = false;            
            echo $this->Form->checkbox('SaveReport',array('hiddenField' => true,'checked'=>$saveReport));                     
            echo '</td>';             
            echo '</tr>';            
      
        ?>
        </table>
    </fieldset>