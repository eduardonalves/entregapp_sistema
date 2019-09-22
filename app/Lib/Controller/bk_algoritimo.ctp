/*$pedidoExistente= $this->Pedido->find('first', array('conditions' => array('Pedido.atendimento_id' => $atendimento['Atendimento']['id'])));
				$this->request->data['Pedido']['atendimento_id'] = $atendimento['Atendimento']['id'];
				
				
				$tempoEstimado="00:00:00";
				if(!empty($pedidoExistente)){
					if($pedidoExistente['Pedido']['statuspreparo']==1){
						$tempoEstimado = $this->checkbfunc->somaHora($tempoEstimado, $pedidoExistente['Pedido']['tempo_estimado']);  
					}else{
						$tempoEstimado = "00:00:00";
					}
					$this->request->data['Pedido']['valor'] = $total + $pedidoExistente['Pedido']['valor'];	
					
				}else{
					$this->request->data['Pedido']['valor'] = $total;
					$tempoEstimado = "00:00:00";
				}
					
					
					
				$pedidosAguardandos= $this->Pedido->find('all', array('recursive' => -1,'conditions' => array('Pedido.statuspreparo' => 1)));
		
				$tempoFila="00:00:00";
				$contadorTempo="00:00:00";
				if(!empty($pedidosAguardandos)){
					foreach($pedidosAguardandos as $pedidosAguardando){
						$horaAtual = date("H:i:s");  
						$difHora="00:00:00";
						$horaAtendimento=$pedidosAguardando['Pedido']['hora_atendimento'];

						$tempoTotalFila = $pedidosAguardando['Pedido']['tempo_fila'];
						
						$esperaHora = $this->checkbfunc->somaHora($horaAtendimento, $tempoTotalFila);
						//Tempo adicionado a fila caso tenha algum pedido atrasado, implementar ou não
						$tolerancia =  $empresa['Empresa']['tolerancia_atraso'];

						
						if($horaAtual >= $horaAtendimento){
							if($horaAtual < $esperaHora){
								$difHora= $this->checkbfunc->subtraHora($esperaHora,$horaAtual);	
							}else{
								$difHora= $tolerancia;
							}
							
							//$difHora=date('H:i:s', $difHora);
							
							
							$contadorTempo=$this->checkbfunc->somaHora($contadorTempo,$difHora);
							
						}else{

							if($horaAtual < $esperaHora){
								$horaAux1 = "23:59:59";
								$horazero = "00:00:01";
								
								$horaAux2 = $this->checkbfunc->subtraHora($horaAux1,$horaAtendimento);

								$horaAux3 = $this->checkbfunc->subtraHora($esperaHora,$horazero);
								
								$difhora = $this->checkbfunc->somaHora($horaAux2, $horaAux3);
								$difhoraAux= $difhora;
								
								$contadorTempo = $this->checkbfunc->somaHora($contadorTempo,$difhoraAux);
							}else{
								$contadorTempo = $this->checkbfunc->somaHora($contadorTempo,$tolerancia);
							}
							
							


						}



						
					}
					$tempoFila = $contadorTempo;
					
				}else{
					
					$tempoFila = "00:00:00";
				}*/