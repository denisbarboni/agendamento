<?php
/**
 * Created by PhpStorm.
 * User: denisbarboni
 * Date: 2021-02-27
 * Time: 21:07
 */

class Codecia_Agendamento_Model_Observer{


    public function catalog_product_save_before($observer){

        try{
            /** @var Codecia_Agendamento_Helper_Data $helper */
            $helper = Mage::helper('codecia_agendamento');
            if ($helper && $helper->isActive()){

                $typeProduct = Mage::app()->getRequest()->getPost('type_product');
                if ($typeProduct && $typeProduct == 'service' || true){

                    /** @var Mage_Catalog_Model_Product $product */
                    $product = $observer->getProduct();
                    if ($product){

                        $options = $product->getProductOptionsCollection();
                        if ($options){
                            $countAttrAgendamento = 0;

                            foreach ($options as $key => $option) {
                                if ($option['title'] == 'Data Agendamento') {
                                    $countAttrAgendamento++;
                                    if ($countAttrAgendamento > 1){
                                        unset($options[$key]);
                                    }
                                }
                            }

                            if (!$countAttrAgendamento){

                                $optionAgendamento = array(
                                    'title' => 'Data Agendamento',
                                    'type' => 'field',
                                    'is_require' => 1,
                                    'sort_order' => 1,
                                    'is_delete' => '',
                                    'previous_type' => '',
                                    'previous_group' => '',
                                    'price' => '0.00',
                                    'price_type' => 'fixed',
                                );

                                $product->getOptionInstance()->addOption($optionAgendamento);
                                $product->setCanSaveCustomOptions(true);
                                $product->setHasOptions(true);
                            }
                        }


                        //Salvar as informações do agendamento no atributo (cc_agendamento)
                        if (Mage::app()->getRequest()->isPost()){
                            $postData = Mage::app()->getRequest()->getPost();
                            if ($postData && isset($postData['agendamento'])){

                                $cc_agendamento = array(
                                    'days_week_available'   =>  array(),
                                    'hours_week_available'  =>  array(),
//                                    'reserved'  => array(
//                                        'day'   => array(
//                                            "hour"  =>  ""
//                                        ),
//                                    )
                                );

                                foreach ($postData['agendamento'] as $key => $value){
                                    //Dias da semana
                                    if ($key == 'days_week'){
                                        foreach ($postData['agendamento'][$key] as $nameDay => $valueDay){
                                            $cc_agendamento['days_week_available'][$nameDay] = $valueDay;
                                        }
                                    }
                                    //Horarios disponíveis
                                    if ($key == 'hours'){
                                        foreach ($postData['agendamento'][$key] as $valueTime){
                                            $cc_agendamento['hours_week_available'][] = $valueTime;
                                        }
                                    }
                                }

                               $product->setData('cc_agendamento', json_encode($cc_agendamento));
                            }
                        }
                    }
                }
            }
        }catch (Exception $e){}


        //throw new Exception('erro');


        return $this;
    }
}
