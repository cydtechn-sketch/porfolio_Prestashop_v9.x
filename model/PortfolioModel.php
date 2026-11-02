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
class Portfolios extends ObjectModel
{
    public $id_portfolio;

    public $active;

    public $heading;

    public $subheading;

    public $description;

    public $features;

    public $link;

    public static $definition = [
        'table' => 'portfolio',
        'primary' => 'id_portfolio',
        'multilang' => true,
        'fields' => [
            'heading' => ['type' => self::TYPE_STRING, 'validate' => 'isString', 'lang' => true, 'required' => true],
            'subheading' => ['type' => self::TYPE_STRING, 'validate' => 'isString', 'lang' => true],
            'active' => ['type' => self::TYPE_BOOL],
            'description' => ['type' => self::TYPE_HTML, 'lang' => true],
            'features' => ['type' => self::TYPE_HTML, 'lang' => true],
            'link' => ['type' => self::TYPE_STRING, 'validate' => 'isString', 'lang' => false],
        ],
    ];

    public function getImgPath($id)
    {
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
        SELECT `img`
        FROM `' . _DB_PREFIX_ . 'portfolio`
        WHERE `id_portfolio` = ' . (int) $id);
        return isset($result['img']) ? $result['img'] : '';
    }

    public static function getPortfolio($limit, $offset, $idLang)
    {
        $order = ((int) Configuration::get('PORT_ORDER') === 1) ? 'DESC' : 'ASC';
        $limit = max(1, (int) $limit);
        $offset = max(0, (int) $offset);
        $sql = Db::getInstance()->executeS('
        SELECT p.*, pl.*
        FROM `' . _DB_PREFIX_ . 'portfolio` p
        LEFT JOIN `' . _DB_PREFIX_ . 'portfolio_lang` pl ON (p.`id_portfolio` = pl.`id_portfolio` AND pl.`id_lang` = ' . (int) $idLang . ')
        WHERE p.`active` = 1
        ORDER BY p.`id_portfolio` ' . $order . ' LIMIT ' . $limit . ' OFFSET ' . $offset);

        return $sql;
    }

    public static function getPortfolioCount()
    {
        return (int) Db::getInstance()->getValue('SELECT COUNT(*) FROM `' . _DB_PREFIX_ . 'portfolio` WHERE `active` = 1');
    }

    public static function changeStatus($id, $action)
    {
        $sql = 'UPDATE `' . _DB_PREFIX_ . 'portfolio`
        SET `active` = ' . (int) $action . '
        WHERE `id_portfolio` = ' . (int) $id;

        return Db::getInstance()->execute($sql);
    }
}
