<?php

declare(strict_types=1);

namespace LeTraceurSnork\RussianLicensePlates\Utils;

use AvtoDev\StaticReferencesData\StaticReferencesData;
use LeTraceurSnork\RussianLicensePlates\Models\Region;

class RegionCodesParser
{
    /**
     * @var array[]
     */
    protected static $parsed_regions;

    /**
     * @var Region[]
     */
    protected static $region_models;

    /**
     * @param string $gibdd_code
     *
     * @return Region|null
     */
    public static function getRegionsByGibddCode(string $gibdd_code): ?Region
    {
        if (!isset(static::$parsed_regions)) {
            static::parseRegions();
        }

        foreach (static::$region_models as $region_model) {
            if ($region_model->hasCode($gibdd_code)) {
                return $region_model;
            }
        }

        return null;
    }

    /**
     * Parse regions from a JSON file
     *
     * @return void
     */
    private static function parseRegions(): void
    {
        static::$parsed_regions = StaticReferencesData::subjectCodes()->getData($as_array = true);

        $regions = [];
        foreach (static::$parsed_regions as $region) {
            $title       = $region['title'] ?? null;
            $iso_code    = $region['code_iso_31662'] ?? null;
            $gibdd_codes = $region['gibdd'] ?? null;
            if (!isset($title, $iso_code, $gibdd_codes)) {
                continue;
            }
            $gibdd_codes = array_map(function ($gibdd_code) {
                return sprintf('%02s', $gibdd_code);
            }, $gibdd_codes);
            $regions[] = (new Region($title, $iso_code))
                ->setGibddCodes($gibdd_codes);
        }

        static::$region_models = $regions;
    }
}
