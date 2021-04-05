<?php
/**
 * Created by PhpStorm.
 * User: denisbarboni
 * Date: 2021-02-27
 * Time: 20:32
 */

class Codecia_Agendamento_Block_Createproduct extends Mage_Core_Block_Template{

    public function __construct(array $args = array())
    {
        parent::__construct($args);

        //Verificar se Tipo de Produto é Serviço.
        $typeProduct = $this->getRequest()->getParam('type_product');
        if ($typeProduct && $typeProduct == 'service'){
            $this->setTemplate('codecia_agendamento/create_product.phtml');
        }
    }

}