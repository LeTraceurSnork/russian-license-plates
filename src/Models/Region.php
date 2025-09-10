<?php

declare(strict_types=1);

namespace LeTraceurSnork\RussianLicensePlates\Models;

class Region
{
    /**
     * ISO 3166-2 code for this region
     *
     * @var string
     *
     * @link https://ru.wikipedia.org/wiki/ISO_3166-2
     * @link https://en.wikipedia.org/wiki/ISO_3166-2
     */
    private $iso_code;

    /**
     * Name of this region in russian
     *
     * @var string
     */
    private $title;

    /**
     * Array of {`01` => true, `02` => false`} format, where `true` means that this GIBDD code belongs to this region.
     *
     * @var array{string, bool}
     */
    private $gibdd_codes;

    /**
     * @param string $title
     * @param string $iso_code
     */
    public function __construct(string $title, string $iso_code)
    {
        $this->title    = $title;
        $this->iso_code = $iso_code;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getIsoCode(): string
    {
        return $this->iso_code;
    }

    /**
     * @return string[]
     */
    public function getGibddCodes(): array
    {
        return array_filter(array_keys($this->gibdd_codes), function ($gibdd_code) {
            return $this->gibdd_codes[$gibdd_code] === true;
        });
    }

    /**
     * @param string[] $gibdd_codes
     *
     * @return $this
     */
    public function setGibddCodes(array $gibdd_codes): Region
    {
        foreach ($gibdd_codes as $gibdd_code) {
            if (!is_scalar($gibdd_code)) {
                continue;
            }

            $this->gibdd_codes[$gibdd_code] = true;
        }

        return $this;
    }

    /**
     * Returns true if specified $gibdd_code belongs to this region
     *
     * @param string $gibdd_code
     *
     * @return bool
     */
    public function hasCode(string $gibdd_code): bool
    {
        return (bool)($this->gibdd_codes[$gibdd_code] ?? false);
    }
}
