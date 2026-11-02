<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_5_1_0($module)
{
    $table = bqSQL(_DB_PREFIX_ . 'portfolio');
    $db = Db::getInstance();

    $schemaUpdated = $db->execute('ALTER TABLE `' . $table . '` MODIFY `active` int(10) NOT NULL DEFAULT 0')
        && $db->execute('ALTER TABLE `' . $table . '` MODIFY `img` varchar(255) NOT NULL DEFAULT \'\'')
        && $db->execute('ALTER TABLE `' . $table . '` MODIFY `link` varchar(255) NOT NULL DEFAULT \'\'');
    if (!$schemaUpdated) {
        return false;
    }

    // Remove hooks used by earlier PS9 builds and retain the hooks used here.
    foreach (['header', 'actionFrontControllerSetMedia', 'actionObjectPortfoliosAddAfter', 'actionObjectPortfoliosUpdateAfter', 'actionObjectPortfoliosDeleteAfter'] as $hook) {
        $module->unregisterHook($hook);
    }

    $portfolioTabId = (int) Tab::getIdFromClassName('AdminPortfolio');
    $improveTabId = (int) Tab::getIdFromClassName('IMPROVE');
    if ($portfolioTabId > 0 && $improveTabId > 0) {
        $portfolioTab = new Tab($portfolioTabId);
        if ((int) $portfolioTab->id_parent !== $improveTabId) {
            $portfolioTab->id_parent = $improveTabId;
            if (!$portfolioTab->update()) {
                return false;
            }
        }
    }
    $legacyParentTabId = (int) Tab::getIdFromClassName('AdminPortfolioParent');
    if ($legacyParentTabId > 0) {
        (new Tab($legacyParentTabId))->delete();
    }

    return $module->registerHook('displayHome')
        && $module->registerHook('displayBackOfficeHeader')
        && $module->registerHook('ModuleRoutes');
}
