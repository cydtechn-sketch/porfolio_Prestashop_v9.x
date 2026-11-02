<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_5_1_3($module)
{
    foreach (['header', 'actionFrontControllerSetMedia', 'actionObjectPortfoliosAddAfter', 'actionObjectPortfoliosUpdateAfter', 'actionObjectPortfoliosDeleteAfter'] as $hook) {
        $module->unregisterHook($hook);
    }

    if (!$module->installPortfolioTab()) {
        return false;
    }

    $legacyParentTabId = (int) Tab::getIdFromClassName('AdminPortfolioParent');
    if ($legacyParentTabId > 0) {
        (new Tab($legacyParentTabId))->delete();
    }

    return $module->registerHook('displayHome')
        && $module->registerHook('displayBackOfficeHeader')
        && $module->registerHook('ModuleRoutes');
}
