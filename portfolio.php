<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */
if (!defined('_PS_VERSION_')) {
    exit;
}
include_once dirname(__FILE__) . '/model/PortfolioModel.php';
class Portfolio extends Module
{
    public function __construct()
    {
        $this->name = 'portfolio';
        $this->tab = 'front_office_features';
        $this->version = '5.1.3';
        $this->author = 'FMM Modules';
        $this->bootstrap = true;
        parent::__construct();
        $this->displayName = $this->l('Portfolio');
        $this->description = $this->l('Add portfolio to your webstore.');
        $this->ps_versions_compliancy = [
            'min' => '9.0.0',
            'max' => '9.99.99',
        ];
        $this->tabs = [
            [
                'name' => 'Portfolio',
                'class_name' => 'AdminPortfolio',
                'parent_class_name' => 'IMPROVE',
                'visible' => true,
                'icon' => 'photo_library',
            ],
        ];
    }

    public function install()
    {
        if (!parent::install()) {
            return false;
        }
        if (!include dirname(__FILE__) . '/sql/install.php') {
            return false;
        }
        if (!$this->installPortfolioTab()) {
            return false;
        }
        return $this->registerHook('displayHome')
            && $this->registerHook('displayBackOfficeHeader')
            && $this->registerHook('ModuleRoutes');
    }

    public function uninstall()
    {
        Configuration::deleteByName('PORT_HEADING');
        Configuration::deleteByName('PORT_IPP');
        Configuration::deleteByName('PORT_ORDER');

        // Keep portfolio entries and uploaded images if the module is uninstalled.
        return parent::uninstall() && $this->uninstallPortfolioTab();
    }

    /** Ensure the portfolio management page has a visible PS9 back-office menu entry. */
    public function installPortfolioTab()
    {
        $tabId = (int) Tab::getIdFromClassName('AdminPortfolio');
        $tab = $tabId > 0 ? new Tab($tabId) : new Tab();
        $improveId = (int) Tab::getIdFromClassName('IMPROVE');
        $tab->active = 1;
        $tab->class_name = 'AdminPortfolio';
        $tab->id_parent = $improveId;
        $tab->module = $this->name;
        $tab->name = [];
        foreach (Language::getLanguages(false) as $language) {
            $tab->name[(int) $language['id_lang']] = $this->l('Portfolio');
        }

        return (bool) $tab->save();
    }

    /** Remove the explicit tab when the module is uninstalled. */
    public function uninstallPortfolioTab()
    {
        $tabId = (int) Tab::getIdFromClassName('AdminPortfolio');
        if ($tabId < 1) {
            return true;
        }

        return (bool) (new Tab($tabId))->delete();
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitPort')) {
            $languages = Language::getLanguages(false);
            $values = [];
            foreach ($languages as $lang) {
                $value = trim((string) Tools::getValue('PORT_HEADING_' . $lang['id_lang'], ''));
                if (Tools::strlen($value) > 255) {
                    return $this->displayError($this->l('The page heading must be 255 characters or fewer.')) . $this->renderForm();
                }
                $values['PORT_HEADING'][$lang['id_lang']] = $value;
            }

            $itemsPerPage = (int) Tools::getValue('PORT_IPP');
            $order = (int) Tools::getValue('PORT_ORDER');
            if ($itemsPerPage < 1 || $itemsPerPage > 100 || !in_array($order, [0, 1], true)) {
                return $this->displayError($this->l('Use between 1 and 100 items per page and select a valid order.')) . $this->renderForm();
            }
            Configuration::updateValue('PORT_HEADING', $values['PORT_HEADING']);
            Configuration::updateValue('PORT_IPP', $itemsPerPage);
            Configuration::updateValue('PORT_ORDER', $order);
            return $this->displayConfirmation($this->l('The settings have been updated.'));
        }

        return '';
    }

    public function getContent()
    {
        $this->context->smarty->assign([
            'portfolio_admin_url' => $this->context->link->getAdminLink('AdminPortfolio'),
        ]);

        return $this->postProcess() . $this->display(__FILE__, 'views/templates/hook/info.tpl') . $this->renderForm();
    }

    public function renderForm()
    {
        $fields_form = [
            'form' => [
                'legend' => [
                    'title' => $this->l('Settings'),
                    'icon' => 'icon-cogs',
                ],
                'input' => [
                    [
                        'type' => 'text',
                        'lang' => true,
                        'label' => $this->l('Page Heading'),
                        'name' => 'PORT_HEADING',
                        'desc' => $this->l('Leave empty for default heading'),
                    ],
                    [
                        'type' => 'text',
                        'lang' => false,
                        'label' => $this->l('Show Items per Page'),
                        'name' => 'PORT_IPP',
                    ],
                    [
                        'type' => 'select',
                        'label' => $this->l('Order'),
                        'name' => 'PORT_ORDER',
                        'default_value' => 0,
                        'options' => [
                            'query' => [
                                ['id' => 0, 'name' => $this->l('ASC')],
                                ['id' => 1, 'name' => $this->l('DESC')],
                            ],
                            'id' => 'id',
                            'name' => 'name',
                        ],
                    ],
                    [
                        'type' => 'html',
                        'name' => 'portfolio_link',
                        'html_content' => $this->context->link->getModuleLink($this->name, 'portfolio'),
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                ],
            ],
        ];
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->module = $this;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitPort';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = [
            'uri' => $this->getPathUri(),
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        ];

        return $helper->generateForm([$fields_form]);
    }

    public function getConfigFieldsValues()
    {
        $languages = Language::getLanguages(false);
        $fields = [];
        foreach ($languages as $lang) {
            $fields['PORT_HEADING'][$lang['id_lang']] = Tools::getValue('PORT_HEADING_' . $lang['id_lang'], Configuration::get('PORT_HEADING', $lang['id_lang']));
        }
        $itemsPerPage = (int) Configuration::get('PORT_IPP');
        $fields['PORT_IPP'] = Tools::getValue('PORT_IPP', $itemsPerPage > 0 ? $itemsPerPage : 10);
        $fields['PORT_ORDER'] = Tools::getValue('PORT_ORDER', (int) Configuration::get('PORT_ORDER'));
        return $fields;
    }

    public function hookDisplayBackOfficeHeader(array $params = [])
    {
        $this->context->controller->addCSS($this->_path . 'views/css/admin.css');
    }

    public function hookDisplayHome(array $params = [])
    {
        return true;
    }

    public function hookModuleRoutes(array $params = [])
    {
        return [
            'module-portfolio-portfolio' => [
                'controller' => 'portfolio',
                'rule' => 'portfolio',
                'keywords' => [],
                'params' => [
                    'fc' => 'module',
                    'module' => 'portfolio',
                ],
            ],
        ];
    }
}
