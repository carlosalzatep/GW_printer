<?php

/**
 * 2007-2021 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2021 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

//use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Presenter\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;

class Grwcompatibleproduct extends Module
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'grwcompatibleproduct';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Gradiweb';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Compatible products');
        $this->description = $this->l('Compatible products list according to IceCat product IDs');

        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        Configuration::updateValue('GRWCOMPATIBLEPRODUCT_LIVE_MODE', false);
        Configuration::updateValue('GRWCOMPATIBLEPRODUCT_MODULE_TITLE', 'PRODUITS COMPATIBLES');

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('displayFooterProduct');
    }

    public function uninstall()
    {
        Configuration::deleteByName('GRWCOMPATIBLEPRODUCT_LIVE_MODE');
        Configuration::deleteByName('GRWCOMPATIBLEPRODUCT_MODULE_TITLE');

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submitGrwcompatibleproductModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path . 'views/templates/admin/configure.tpl');

        return $output . $this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {
        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitGrwcompatibleproductModule';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false)
            . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFormValues(), /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigForm()));
    }

    /**
     * Create the structure of your form.
     */
    protected function getConfigForm()
    {
        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Paramètres'),
                    'icon' => 'icon-cogs',

                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('En ligne'),
                        'name' => 'GRWCOMPATIBLEPRODUCT_LIVE_MODE',
                        'is_bool' => true,
                        //'desc' => $this->l('Use this module in live mode'),
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('Disabled'),
                                'required' => true
                            )
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Titre'),
                        'name' => 'GRWCOMPATIBLEPRODUCT_MODULE_TITLE',
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                ),
            ),
        );
    }

    /**
     * Set values for the inputs.
     */
    protected function getConfigFormValues()
    {
        return array(
            'GRWCOMPATIBLEPRODUCT_LIVE_MODE' => Configuration::get('GRWCOMPATIBLEPRODUCT_LIVE_MODE', true),
            'GRWCOMPATIBLEPRODUCT_MODULE_TITLE' => Configuration::get('GRWCOMPATIBLEPRODUCT_MODULE_TITLE', 'PRODUITS COMPATIBLES'),
            //'GRWCOMPATIBLEPRODUCT_ACCOUNT_PASSWORD' => Configuration::get('GRWCOMPATIBLEPRODUCT_ACCOUNT_PASSWORD', null),
        );
    }

    /**
     * Save form data.
     */
    protected function postProcess()
    {
        $form_values = $this->getConfigFormValues();

        foreach (array_keys($form_values) as $key) {
            Configuration::updateValue($key, Tools::getValue($key));
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be loaded in the BO.
     */
    public function hookBackOfficeHeader()
    {
        /*if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }*/
    }

    public function hookHeader()
    {
        $form_values = $this->getConfigFormValues();
        if ($this->context->controller->php_self == 'product' && $form_values['GRWCOMPATIBLEPRODUCT_LIVE_MODE']) {

            $this->context->controller->addJS($this->_path . 'views/js/front.js');
            $this->context->controller->addCSS($this->_path . 'views/css/front.css');
        }
    }


    public function hookDisplayFooterProduct($params)
    {
        $form_values = $this->getConfigFormValues();

        $id_product = (int)Tools::getValue('id_product');

        if ($form_values['GRWCOMPATIBLEPRODUCT_LIVE_MODE'] && $id_product && Validate::isLoadedObject($product = new Product((int) $id_product))) {
            if ($compatiblesProductsId = $product->getCompatibleProducts($product->id)) {
                //Validate if includes a , (comma) at the string End
                if (substr($compatiblesProductsId, -1) == ',')
                    $compatiblesProductsId = substr($compatiblesProductsId, 0, -1);

                $compatiblesProductsId = explode(',', $compatiblesProductsId);

                $PorductsListArray = array();

                //Prepare Product Presenter
                $assembler = new ProductAssembler($this->context);

                $presenterFactory = new ProductPresenterFactory($this->context);
                $presentationSettings = $presenterFactory->getPresentationSettings();
                $presenter = new ProductListingPresenter(
                    new ImageRetriever(
                        $this->context->link
                    ),
                    $this->context->link,
                    new PriceFormatter(),
                    new ProductColorsRetriever(),
                    $this->context->getTranslator()
                );

                $productsForTpl = [];
                $listNumber = 0;

                foreach ($compatiblesProductsId as $ItemPosProd) {

                    $TMPproduct = new Product($ItemPosProd, true, $this->context->language->id);
                    if ($TMPproduct->id && $TMPproduct->active) {

                        $TMPProd = array(
                            'id_product' => $TMPproduct->id
                        );

                        $productsForTpl[] = $presenter->present(
                            $presentationSettings,
                            $assembler->assembleProduct($TMPProd),
                            $this->context->language
                        );

                        $listNumber++;
                    }
                }

                $this->context->smarty->assign(
                    array(
                        'products' => $productsForTpl,
                        'module_title' => $form_values['GRWCOMPATIBLEPRODUCT_MODULE_TITLE'],
                        'list_number' => $listNumber,
                    )
                );

                $output = $this->context->smarty->fetch($this->local_path . 'views/templates/front/list.tpl');

                return $output;
            }
        }
    }
}
