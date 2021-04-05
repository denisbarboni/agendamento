<?php

Mage::app()->cleanCache();
/** @var Mage_Eav_Model_Entity_Setup $installer */
$installer = $this;

$installer->startSetup();

$attr = Mage::getResourceModel('catalog/eav_attribute')->loadByCode('catalog_product', 'cc_agendamento');

if (!$attr || !$attr->getId()) {

    $installer->addAttribute('catalog_product', 'cc_agendamento', array(
        'group' => 'General',
        'input' => 'text',
        'type' => 'text',
        'label' => 'Dados Agendamento',
        'backend' => '',
        'visible' => 0,
        'required' => 0,
        'user_defined' => 1,
        'searchable' => 0,
        'filterable' => 0,
        'comparable' => 0,
        'visible_on_front' => 0,
        'visible_in_advanced_search' => 0,
        'is_html_allowed_on_front' => 0,
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    ));

    $installer->updateAttribute(
        Mage_Catalog_Model_Product::ENTITY,
        'seller_id',
        'used_in_product_listing',
        1
    );
}


$installer->endSetup();
