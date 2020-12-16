<?php
/**
 * This file is part of Contao EstateManager.
 *
 * @link      https://www.contao-estatemanager.com/
 * @source    https://github.com/contao-estatemanager
 * @copyright Copyright (c) 2019  Oveleon GbR (https://www.oveleon.de)
 * @license   https://www.contao-estatemanager.com/lizenzbedingungen.html
 */

// ESTATEMANAGER
$GLOBALS['TL_ESTATEMANAGER_ADDONS'][] = array('ContaoEstateManager\GoogleGeodata', 'AddonManager');

if(ContaoEstateManager\GoogleGeodata\AddonManager::valid()) {
    // Hooks
    $GLOBALS['TL_HOOKS']['beforeRealEstateImport'][] = array('ContaoEstateManager\GoogleGeodata\GeoData', 'setGeoData');
}