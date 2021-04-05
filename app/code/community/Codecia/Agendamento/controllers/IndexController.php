<?php

class Codecia_Agendamento_IndexController extends Mage_Core_Controller_Front_Action{

    public function hoursAction(){

        $response = array(
            'status' => false,
            'html'  => ""
        );

        if ($this->getRequest()->isPost()){
//            $postData = $this->getRequest()->getPost();
//            if ($postData && isset($postData['date'])){
//                $date = $postData['date'];

                $response['status'] = true;

                $hoursAvalaible = array('13:00', '14:00', '15:00');
                foreach ($hoursAvalaible as $hourAvalailable){
                    $time = explode(':', $hourAvalailable);
                    $hour = $time[0];
                    $minute = $time[1];
                    $response['html'] .= "<div class=\"xdsoft_time\" data-hour=\"{$hour}\" data-minute=\"{$minute}\">{$hourAvalailable}</div>";
                }

//            }
        }
        return $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($response));
    }
}