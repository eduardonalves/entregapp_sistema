<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

	public $helpers = array(
		'Html' => array('className' => 'BootstrapHtml'),
		'Form' => array('className' => 'BootstrapForm'),
		'Paginator' => array('className' => 'BootstrapPaginator'),
		'Session',
		'FilterResults.Search',
	);
	public $components = array(
        'Session',
		'Auth' => array(
            'loginRedirect' => array('controller' => 'pages', 'action' => 'index'),
            'logoutRedirect' => array('controller' => 'users', 'action' => 'login')
		),
		'FilterResults.Filter' => array(
			'auto' => array(
				'paginate' => false,
				'explode'  => true,  // recommended
			),
			'explode' => array(
				'character'   => ' ',
				'concatenate' => 'AND',
			)
		)
    );

	public function beforeFilter() {
		if ($this->Session->check('Config.language')) {
			Configure::write('Config.language', 'por');
				Configure::write('Config.language', $this->Session->read('Config.language'));
		}
		if(in_array($this->params['controller'],array('rest_images','rest_cupons','rest_produtos', 'rest_clientes', 'rest_pedidos', 'rest_atendimentos', 'rest_mensagens','rest_filials', 'rest_categorias', 'rest_pagamentos'))){
			// For RESTful web service requests, we check the name of our contoller
			$this->Auth->allow();
			// this line should always be there to ensure that all rest calls are secure
			$this->Security->requireSecure();
			$this->Security->unlockedActions = array('cancelarpagseguro','redirecionarpagseguro','pagseguromobile','uploadimage','getnotifications','getSessionPag','formtrocasenha','recuperarsenha','getLocalidadePedidos','getPromoDia','calculafrete','statusloja','getsituacaocampainha','campainhamobile','posentregador','confirmaentregamobile','campaninhamobile','prodsmobile','loginmobile', 'addmobile','viewmobile','indexmobile', 'itensmobile','checaatendimento','pgtomoip', 'avalpedidomobile','catsmobile','prodsmobilebycat','pagamentosmobile');
			date_default_timezone_set("Brazil/East");
		}else{
			// setup out Auth
			$this->Auth->allow('cancelarpagseguro','redirecionarpagseguro','pagseguromobile','uploadimage','getnotifications','getSessionPag','formtrocasenha','recuperarsenha','getLocalidadePedidos','getPromoDia','calculafrete','statusloja','getsituacaocampainha','campainhamobile','posentregador','confirmaentregamobile','campaninhamobile','login','prodsmobile','loginmobile', 'addmobile','viewmobile','indexmobile', 'itensmobile','checaatendimento','pgtomoip', 'avalpedidomobile','catsmobile','prodsmobilebycat','pagamentosmobile');
			date_default_timezone_set("Brazil/East");
		}
	}

	function beforeRender () {
	       $this->_setErrorLayout();
	}

	function _setErrorLayout() {
	    if($this->name == 'CakeError') {
	        $this->layout = 'liso';
	    }
	}

}
