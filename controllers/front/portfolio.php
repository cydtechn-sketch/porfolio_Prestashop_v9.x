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
class PortfolioPortfolioModuleFrontController extends ModuleFrontController
{
    public function setMedia()
    {
        parent::setMedia();
        $this->registerStylesheet(
            'module-portfolio-front',
            'modules/' . $this->module->name . '/views/css/portfolio.css',
            ['media' => 'all', 'priority' => 150]
        );
    }

    public function init()
    {
        parent::init();
        $this->context = Context::getContext();
    }

    public function initContent()
    {
        parent::initContent();
        $id_lang = (int) $this->context->language->id;
        $default_ipp = (int) Configuration::get('PORT_IPP');
        $default_ipp = ($default_ipp > 0) ? $default_ipp : 10;
        $default_ipp = min($default_ipp, 100);
        $totalItems = Portfolios::getPortfolioCount();
        $totalPages = (int) ceil($totalItems / $default_ipp);
        $currentPage = (int) Tools::getValue('page', 1);
        $currentPage = max(1, min($currentPage, max(1, $totalPages)));
        $paginatedData = Portfolios::getPortfolio($default_ipp, ($currentPage - 1) * $default_ipp, $id_lang);
        if (!is_array($paginatedData)) {
            $paginatedData = [];
        }
        foreach ($paginatedData as &$portfolioItem) {
            $portfolioItem['description'] = Tools::purifyHTML((string) $portfolioItem['description']);
            $portfolioItem['features'] = Tools::purifyHTML((string) $portfolioItem['features']);
            $urlParts = parse_url((string) $portfolioItem['link']);
            if (!is_array($urlParts) || empty($urlParts['scheme']) || !in_array(Tools::strtolower($urlParts['scheme']), ['http', 'https'], true)) {
                $portfolioItem['link'] = '';
            }
            $imageName = basename((string) $portfolioItem['img']);
            $portfolioItem['img'] = $imageName !== '' ? 'portfolio/' . (int) $portfolioItem['id_portfolio'] . '/' . $imageName : '';
        }
        unset($portfolioItem);
        $pagesToShow = min($totalPages, 3);
        $startPage = max(1, min($currentPage - 1, $totalPages - $pagesToShow + 1));
        $endPage = $startPage + $pagesToShow - 1;
        $pages = [];
        for ($i = $startPage; $i <= $endPage; ++$i) {
            $pages[] = [
                'number' => $i,
                'current' => ($i == $currentPage),
                'url' => $this->context->link->getModuleLink('portfolio', 'portfolio', ['page' => $i]),
            ];
        }
        $this->context->smarty->assign([
            'portfolio' => $paginatedData,
            'img_url' => (Configuration::get('PS_SSL_ENABLED') ? _PS_BASE_URL_SSL_ : _PS_BASE_URL_) . __PS_BASE_URI__ . 'img/',
            'PORT_HEAD' => Configuration::get('PORT_HEADING', $id_lang),
            'totalItems' => $totalItems,
            'totalPages' => $totalPages,
            'currentPage' => $currentPage,
            'pagination' => $pages,
            'previousPageUrl' => $currentPage > 1 ? $this->context->link->getModuleLink('portfolio', 'portfolio', ['page' => $currentPage - 1]) : '',
            'nextPageUrl' => $currentPage < $totalPages ? $this->context->link->getModuleLink('portfolio', 'portfolio', ['page' => $currentPage + 1]) : '',
        ]);
        $this->setTemplate('module:portfolio/views/templates/front/list.tpl');
    }
}
