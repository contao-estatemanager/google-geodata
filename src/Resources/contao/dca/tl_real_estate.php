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

use Contao\Backend;
use Contao\DataContainer;
use ContaoEstateManager\GoogleGeodata\AddonManager;
use ContaoEstateManager\GoogleGeodata\GeoData;

if (AddonManager::valid())
{
    $GLOBALS['TL_DCA']['tl_real_estate']['config']['onsubmit_callback'][] = ['tl_real_estate_google_geodata', 'storeGeoData'];
}

/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Fabian Ekert <https://github.com/eki89>
 */
class tl_real_estate_google_geodata extends Backend
{
    /**
     * @param DataContainer $dc
     */
    public function storeGeoData($dc): void
    {
        // Front end call
        if (!$dc instanceof DataContainer)
        {
            return;
        }

        // Return if there is no active record (override all)
        if (!$dc->activeRecord)
        {
            return;
        }

        if (!empty($dc->activeRecord->laengengrad) || !empty($dc->activeRecord->breitengrad))
        {
            return;
        }

        $objGeoData = new GeoData();

        if (($geoData = $objGeoData->determineGeoData($dc->activeRecord->strasse, $dc->activeRecord->hausnummer, $dc->activeRecord->plz, $dc->activeRecord->ort)) !== false)
        {
            $this->Database->prepare('UPDATE tl_real_estate SET breitengrad=?, laengengrad=? WHERE id=?')
                ->execute($geoData['breitengrad'], $geoData['laengengrad'], $dc->activeRecord->id)
            ;
        }
    }

    /**
     * Check if all address information are given.
     *
     * @param $activeRecord
     *
     * @return bool
     */
    protected function isAddressComplete($activeRecord)
    {
        if ($activeRecord->strasse && $activeRecord->hausnummer && $activeRecord->plz && $activeRecord->ort)
        {
            return true;
        }

        return false;
    }

    /**
     * @param $url
     *
     * @return bool|string
     */
    protected function getFileContent($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $content = curl_exec($ch);
        curl_close($ch);

        return $content;
    }
}
