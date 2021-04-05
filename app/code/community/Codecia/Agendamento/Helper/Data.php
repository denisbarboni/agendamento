<?php
/**
 * Created by PhpStorm.
 * User: Denis
 * Date: 2021-02-27
 * Time: 20:00
 */

class Codecia_Agendamento_Helper_Data extends Mage_Core_Helper_Abstract {

    public function isActive(){

        if ($this->isModuleEnabled('Codecia_Agendamento') && Mage::getStoreConfig('codecia_agendamento/settings/active')){
            return true;
        }

        return false;
    }

    public function getConfig($key){
        return Mage::getStoreConfig('codecia_agendamento/settings/'.$key);
    }
}