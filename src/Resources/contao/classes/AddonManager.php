<?php
/**
 * This file is part of Contao EstateManager.
 *
 * @link      https://www.contao-estatemanager.com/
 * @source    https://github.com/contao-estatemanager/google-autocomplete
 * @copyright Copyright (c) 2019  Oveleon GbR (https://www.oveleon.de)
 * @license   https://www.contao-estatemanager.com/lizenzbedingungen.html
 */

namespace ContaoEstateManager\GoogleGeodata;

use Contao\Config;
use Contao\Environment;
use ContaoEstateManager\EstateManager;

class AddonManager
{
    /**
     * Bundle name
     * @var string
     */
    public static $bundle = 'EstateManagerGoogleGeodata';

    /**
     * Package
     * @var string
     */
    public static $package = 'contao-estatemanager/google-geodata';

    /**
     * Addon config key
     * @var string
     */
    public static $key  = 'addon_google_geodata_license';

    /**
     * Is initialized
     * @var boolean
     */
    public static $initialized  = false;

    /**
     * Is valid
     * @var boolean
     */
    public static $valid  = false;

    /**
     * Licenses
     * @var array
     */
    private static $licenses = [
        'adbadd4cc15df769b77ec19072b29aef',
        '8e487a10b8427605b3b396db85f82515',
        '984b42ebae9ca0a53995182e42449dd2',
        '2729fa2a951b24bbd5cdbd307e6f3ed5',
        'b66bc09905eab4941ddff5ef2e221a6b',
        'ee2e3cb78239ae8cfd7d0468b665959d',
        'da2c01d514e60f3b520cc1212b5a99d3',
        'fae45ae093ddd85ae306040578d3a5e5',
        'fc09ce47ef0bd8f0f29e3b32459d6bf0',
        'b9aeddd83b1e213a184f5349971b449a',
        'aaab0db24eee6f25be4730bb7dfabf4e',
        '224716e1371695779d79bc28d2d09ef7',
        '510c009119a0394828a233100d724b71',
        '8b7ddd4a96d029f3054c639e19224db6',
        '2e55a996a86b4af77e7d9264fbb1be81',
        'a5c67879e4f2f3bbce03da8308d2e7bb',
        '461aa1353848cf98192abd3ad560fb11',
        '4f821dff0b406c9afeea55d967124339',
        'af4278a3c83557a8d6702769f5be78e9',
        '7f82b9997be08dc34d838ce00a1f269b',
        'e2544317964f33a60da00e0d304d0285',
        'ca5775d6c8b5367e90760cfb5e50703d',
        '413049f022b9ae7a1782036dc2b494b8',
        '078619ce0702dff5fff1bf6f94969901',
        '0c62df128abdce4425fd0f2549819bc4',
        'a1845a07c14795ff19dd6c9381924bbd',
        'f3d7a7f58dc0378f0a06b7a47ae39308',
        'fec064f1eafcf068bc0e387f1f91c39b',
        '0515c9ad9fad1a8b1cfca2b7a4b3e840',
        '267773c5bd741050a0dc78a801ecef06',
        '3be4bd443994f0d3b50379a6e0c74b50',
        '52472016e9418a7e55ccb40f7232b58a',
        '9312ca4f8f89f8452e6d732e6174ad78',
        '7dc34895011300e55c456d7bb1419536',
        '4b2de62fc77a5a49f1d1a72134794347',
        '2368cc15ddbeaf44498fce353cd3eb39',
        '52849b8674f1d43fdc592e40a11d31d8',
        'd3a82f345ef1b61444998009e5c920e9',
        '10936dc3915d704cef7d96144a1abfd0',
        '755c772d46a8099be85460ec2b87831e',
        'bda6a621f7329336c3dc388decec25e2',
        '7ceb2e33e0cb8e2bb55bdd91fa473751',
        'c16d33c7ea7991dced62117d09763c5d',
        'fbcc1949f928319d8c41b6b038ee200f',
        '3a2e3afaa7d79926da5688bf53c45959',
        'b8252cf3159b44f1418c11a55688149b',
        '68d9240a92f93e59e7964daff95fb716',
        'd37afd4c39d80b58b473ba5c750727eb',
        '1945c7b34e07566d6cffa0aa613ee578',
        'ffcb4c97e494fcfed973912d51db5bdb'
    ];

    public static function getLicenses()
    {
        return static::$licenses;
    }

    public static function valid()
    {
        if(strpos(Environment::get('requestUri'), '/contao/install') !== false)
        {
            return true;
        }

        if (static::$initialized === false)
        {
            static::$valid = EstateManager::checkLicenses(Config::get(static::$key), static::$licenses, static::$key);
            static::$initialized = true;
        }

        return static::$valid;
    }

}
