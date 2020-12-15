<?php
/**
 * This file is part of Contao EstateManager.
 *
 * @link      https://www.contao-estatemanager.com/
 * @source    https://github.com/contao-estatemanager
 * @copyright Copyright (c) 2019  Oveleon GbR (https://www.oveleon.de)
 * @license   https://www.contao-estatemanager.com/lizenzbedingungen.html
 */

if(ContaoEstateManager\GoogleGeodata\AddonManager::valid()) {
    $GLOBALS['TL_DCA']['tl_real_estate']['config']['onsubmit_callback'][] = array('tl_real_estate_google_geodata', 'storeGeoData');
}

/**
 * Provide miscellaneous methods that are used by the data configuration array.
 *
 * @author Fabian Ekert <https://github.com/eki89>
 */
class tl_real_estate_google_geodata extends Contao\Backend
{
    /**
     * @param Contao\DataContainer
     */
    public function storeGeoData($dc)
    {
        // Return if not allowed
        if (!Contao\Config::get('storeGeoData'))
        {
            return;
        }

        // Front end call
        if (!$dc instanceof Contao\DataContainer)
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

        if(($apiKey = Contao\Config::get('googleApiToken')) && $this->isAddressComplete($dc->activeRecord))
        {
            $strAddress = urlencode(sprintf('%s %s, %s %s', $dc->activeRecord->strasse, $dc->activeRecord->hausnummer, $dc->activeRecord->plz, $dc->activeRecord->ort));
            $strUrl = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $strAddress . '&key='.$apiKey;

            $arrContent = json_decode($this->getFileContent($strUrl));

            if ($arrContent && $arrContent->results && is_array($arrContent->results))
            {
                $breitengrad = $arrContent->results[0]->geometry->location->lat;
                $laengengrad = $arrContent->results[0]->geometry->location->lng;

                if (!is_numeric($breitengrad) || !is_numeric($laengengrad))
                {
                    return;
                }

                $this->Database->prepare("UPDATE tl_real_estate SET breitengrad=?, laengengrad=? WHERE id=?")
                    ->execute($breitengrad, $laengengrad, $dc->activeRecord->id);
            }
        }
    }

    /**
     * Check if all address information are given
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