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

use Contao\CoreBundle\DataContainer\PaletteManipulator;
use ContaoEstateManager\GoogleGeodata\AddonManager;

if (AddonManager::valid())
{
    // Add field
    $GLOBALS['TL_DCA']['tl_real_estate_config']['fields']['storeGeoData'] = [
        'label' => &$GLOBALS['TL_LANG']['tl_real_estate_config']['storeGeoData'],
        'inputType' => 'checkbox',
        'eval' => ['tl_class' => 'w50 m12'],
    ];

    // Extend default palette
    PaletteManipulator::create()
        ->addField(['storeGeoData'], 'google_legend', PaletteManipulator::POSITION_APPEND)
        ->applyToPalette('default', 'tl_real_estate_config')
    ;
}
