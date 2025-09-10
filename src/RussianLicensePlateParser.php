<?php

declare(strict_types=1);

namespace LeTraceurSnork\RussianLicensePlates;

use LeTraceurSnork\RussianLicensePlates\Utils\RegionCodesParser;

class RussianLicensePlateParser
{
    // ГОСТ-овые константы типов
    const GROUP_1_TYPE_1  = 'group_1_type_1';   // А123ВС77 (легковой, основной)
    const GROUP_1_TYPE_1A = 'group_1_type_1a';  // А123ВС77rus (с флагом)
    const GROUP_1_TYPE_1B = 'group_1_type_1b';  // АА123477
    const GROUP_1_TYPE_2  = 'group_1_type_2';   // АА123477
    const GROUP_1_TYPE_3  = 'group_1_type_3';   // 1234АА77
    const GROUP_1_TYPE_4  = 'group_1_type_4';   // 1234АА77rus
    const GROUP_1_TYPE_4A = 'group_1_type_4a';  // АА123477rus
    const GROUP_1_TYPE_4B = 'group_1_type_4b';  // АА12АА177

    // ... (при необходимости расширяем список)

    protected static $patterns = array(
        self::GROUP_1_TYPE_1 => array(
            // 1 буква, 3 цифры, 2 буквы, 2-3 цифры (регион)
            'pattern' => '/^([АВЕКМНОРСТУХABEKMHOPCTYX])(\d{3})([АВЕКМНОРСТУХABEKMHOPCTYX]{2})(\d{2,3})$/u',
            'description' => 'Группа 1, тип 1 (основной легковой)',
        ),
        self::GROUP_1_TYPE_1A => array(
            // с флагом
            'pattern' => '/^([АВЕКМНОРСТУХABEKMHOPCTYX])(\d{3})([АВЕКМНОРСТУХABEKMHOPCTYX]{2})(\d{2,3})RUS?$/u',
            'description' => 'Группа 1, тип 1А (легковой с флагом RUS)',
        ),
        self::GROUP_1_TYPE_1B => array(
            // две буквы, 3 цифры, регион
            'pattern' => '/^([АВЕКМНОРСТУХABEKMHOPCTYX]{2})(\d{3})(\d{2,3})$/u',
            'description' => 'Группа 1, тип 1B',
        ),
        self::GROUP_1_TYPE_2 => array(
            // две буквы, 4 цифры, регион
            'pattern' => '/^([АВЕКМНОРСТУХABEKMHOPCTYX]{2})(\d{4})(\d{2,3})$/u',
            'description' => 'Группа 1, тип 2',
        ),
        self::GROUP_1_TYPE_3 => array(
            // 4 цифры, 2 буквы, регион
            'pattern' => '/^(\d{4})([АВЕКМНОРСТУХABEKMHOPCTYX]{2})(\d{2,3})$/u',
            'description' => 'Группа 1, тип 3',
        ),
        self::GROUP_1_TYPE_4B => array(
            // две буквы, две цифры, две буквы, регион
            'pattern' => '/^([АВЕКМНОРСТУХABEKMHOPCTYX]{2})(\d{2})([АВЕКМНОРСТУХABEKMHOPCTYX]{2})(\d{2,3})$/u',
            'description' => 'Группа 1, тип 4Б',
        ),
    );

    /**
     * Парсит номер согласно ГОСТ'у, возвращает информацию о совпавшем формате и данные
     */
    public static function parse($plate_number)
    {
        $plate_number = preg_replace('/[\s\-]/u', '', (string) $plate_number);
        $plate_number = mb_strtoupper($plate_number, 'UTF-8');

        foreach (self::$patterns as $type => $info) {
            if (preg_match($info['pattern'], $plate_number, $matches)) {
                // разбираем matches по шаблону: имя полей зависит от типа!
                switch ($type) {
                    case self::GROUP_1_TYPE_1:
                    case self::GROUP_1_TYPE_1A:
                        return array(
                            'type'          => $type,
                            'first_letter'  => $matches[1],
                            'digits'        => $matches[2],
                            'last_letters'  => $matches[3],
                            'region'        => $matches[4],
                        );
                    case self::GROUP_1_TYPE_1B:
                        return array(
                            'type'          => $type,
                            'letters'       => $matches[1],
                            'digits'        => $matches[2],
                            'region'        => $matches[3],
                        );
                    case self::GROUP_1_TYPE_2:
                        return array(
                            'type'          => $type,
                            'letters'       => $matches[1],
                            'digits'        => $matches[2],
                            'region'        => $matches[3],
                        );
                    case self::GROUP_1_TYPE_3:
                        return array(
                            'type'          => $type,
                            'digits'        => $matches[1],
                            'letters'       => $matches[2],
                            'region'        => $matches[3],
                        );
                    case self::GROUP_1_TYPE_4B:
                        return array(
                            'type'          => $type,
                            'letters1'      => $matches[1],
                            'digits'        => $matches[2],
                            'letters2'      => $matches[3],
                            'region'        => $matches[4],
                        );
                }
            }
        }

        return null;
    }
}
