<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

function upgrade_module_5_0_0($module)
{
    $db = Db::getInstance();
    $table = _DB_PREFIX_ . 'portfolio';

    $schemaUpdated = $db->execute('ALTER TABLE `' . bqSQL($table) . '` MODIFY `active` int(10) NOT NULL DEFAULT 0')
        && $db->execute('ALTER TABLE `' . bqSQL($table) . '` MODIFY `img` varchar(255) NOT NULL DEFAULT \'\'')
        && $db->execute('ALTER TABLE `' . bqSQL($table) . '` MODIFY `link` varchar(255) NOT NULL DEFAULT \'\'');
    if (!$schemaUpdated) {
        return false;
    }

    $module->unregisterHook('header');

    return $module->registerHook('ModuleRoutes');
}
