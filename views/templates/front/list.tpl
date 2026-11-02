{*
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
*}
{extends file='page.tpl'}
{block name="page_content"}
<div id="fmmPorfolio">
    <h4 class="page-subheading">{if empty($PORT_HEAD)}{l s="Portfolio" mod="portfolio"}{else}{$PORT_HEAD|escape:'htmlall':'UTF-8'}{/if}</h4>
    {if isset($portfolio) && $portfolio}
    <ol>
        {foreach from=$portfolio item=port name=portfolio}
        <li class="portfolio_item">
            <div class="fmm_left_cube">
                <h5>{$port.heading|escape:'htmlall':'UTF-8'}</h5>
                <h6>{$port.subheading|escape:'htmlall':'UTF-8'}</h6>
                <div class="description">{$port.description nofilter}</div>
                <div class="features">
                    {$port.features nofilter}
                </div>
                {if !empty($port.link)}
                    <a href="{$port.link|escape:'htmlall':'UTF-8'}" title="{l s='Launch' mod='portfolio'}" target="_blank" rel="noopener noreferrer" class="fmm_launch_link">{l s='Launch' mod='portfolio'}</a>
                {/if}
            </div>
            <div class="fmm_right_cube">
                {if !empty($port.img)}<img src="{$img_url|escape:'htmlall':'UTF-8'}{$port.img|escape:'htmlall':'UTF-8'}" alt="{$port.heading|escape:'htmlall':'UTF-8'}" />{/if}
            </div>
        </li>
        {/foreach}
    </ol>
    <ul class="pagination">
         {if $currentPage > 1}
            <li>
                <a href="{$previousPageUrl|escape:'htmlall':'UTF-8'}" rel="prev">{l s='Previous' mod='portfolio'}</a>
            </li>
        {/if}
        {foreach from=$pagination item=page}
            <li{if $page.current} class="active" aria-current="page"{/if}>
                <a href="{$page.url|escape:'htmlall':'UTF-8'}">{$page.number|escape:'htmlall':'UTF-8'}</a>
             </li>
        {/foreach}
        {if $currentPage < $totalPages}
            <li>
                <a href="{$nextPageUrl|escape:'htmlall':'UTF-8'}" rel="next">{l s='Next' mod='portfolio'}</a>
            </li>
        {/if}
    </ul>
    {else}
        <p>{l s='No items in Portfolio' mod='portfolio'}</p>
    {/if}
</div>
{/block}
