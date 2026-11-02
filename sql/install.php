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
$return = true;
$return &= Db::getInstance()->execute(' CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'portfolio` (
	`id_portfolio` int(10) NOT NULL auto_increment,
	`active` int(10) NOT NULL DEFAULT 0,
	`img` varchar(255) NOT NULL DEFAULT \'\',
	`link` varchar(255) NOT NULL DEFAULT \'\',
	PRIMARY KEY (`id_portfolio`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4;');
$return &= Db::getInstance()->execute(' CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'portfolio_lang` (
	`id_portfolio` int(10) NOT NULL,
	`id_lang` int(10) NOT NULL,
	`heading` varchar(255) NOT NULL,
	`subheading` varchar(255) NOT NULL,
	`description` text,
	`features` text,
	PRIMARY KEY (`id_portfolio`, `id_lang`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4;');

return $return;
