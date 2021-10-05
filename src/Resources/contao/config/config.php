<?php

declare(strict_types=1);

/*
 * This file is part of Contao EstateManager.
 *
 * @see        https://www.contao-estatemanager.com/
 * @source     https://github.com/contao-estatemanager/google-geodata
 * @copyright  Copyright (c) 2021 Oveleon GbR (https://www.oveleon.de)
 * @license    https://www.contao-estatemanager.com/lizenzbedingungen.html
 */

// ESTATEMANAGER
$GLOBALS['TL_ESTATEMANAGER_ADDONS'][] = ['ContaoEstateManager\GoogleGeodata', 'AddonManager'];

use ContaoEstateManager\GoogleGeodata\AddonManager;

if (AddonManager::valid())
{
    // Hooks
    $GLOBALS['TL_HOOKS']['beforeRealEstateImport'][] = ['ContaoEstateManager\GoogleGeodata\GeoData', 'setGeoData'];
}
