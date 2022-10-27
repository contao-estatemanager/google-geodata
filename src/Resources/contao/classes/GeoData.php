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

namespace ContaoEstateManager\GoogleGeodata;

use Contao\Config;

class GeoData
{
    public function setGeoData(&$objRealEstate, $context): void
    {
        if (!empty($objRealEstate->laengengrad) || !empty($objRealEstate->breitengrad))
        {
            return;
        }

        if (($geoData = $this->determineGeoData($objRealEstate->strasse, $objRealEstate->hausnummer, $objRealEstate->plz, $objRealEstate->ort)) !== false)
        {
            $objRealEstate->breitengrad = $geoData['breitengrad'];
            $objRealEstate->laengengrad = $geoData['laengengrad'];
        }
    }

    public function determineGeoData($strasse, $hausnummer, $plz, $ort)
    {
        // Return if not possible or allowed
        if (!Config::get('googleApiToken') || !Config::get('storeGeoData'))
        {
            return false;
        }

        if (!$this->isAddressComplete($strasse, $hausnummer, $plz, $ort))
        {
            return false;
        }

        $strAddress = urlencode(sprintf('%s %s, %s %s', $strasse, $hausnummer, $plz, $ort));
        $strUrl = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$strAddress.'&key='.Config::get('googleApiToken');

        $arrContent = json_decode($this->getFileContent($strUrl));

        if ($arrContent && $arrContent->results && \is_array($arrContent->results))
        {
            $breitengrad = $arrContent->results[0]->geometry->location->lat;
            $laengengrad = $arrContent->results[0]->geometry->location->lng;

            if (!is_numeric($breitengrad) || !is_numeric($laengengrad))
            {
                return false;
            }

            return [
                'breitengrad' => $breitengrad,
                'laengengrad' => $laengengrad,
            ];
        }

        return false;
    }

    /**
     * Check if all address information are given.
     *
     * @param $strasse
     *
     * @return bool
     */
    protected function isAddressComplete($strasse, $hausnummer, $plz, $ort)
    {
        if ($strasse && $hausnummer && $plz && $ort)
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
