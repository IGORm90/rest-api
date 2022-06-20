<?php

namespace App\Service;

use App\Models\EquipmentType;

class MaskService
{

    /**
     * Validate Serial Number by mask.
     *
     * @param  string  $mask
     * @param  string  $serialNumber
     * @return bool $isValid
     */
    public function validateSerialNumber(string $equipmentTypeId, string $serialNumber): bool {

        $mask = EquipmentType::find($equipmentTypeId)->serial_number_mask;

        $maskToRegExp = [
            'N' => '[0-9]',
            'A' => '[A-Z]',
            'a' => '[a-z]',
            'X' => '[a-z0-9]',
            'Z' => '[-_@]',
        ];
        $pregString = '/';
        $maskArray = str_split($mask);
        foreach($maskArray as $ma){
            if(isset($maskToRegExp[$ma])){
                $pregString .= $maskToRegExp[$ma];
            } else {
                return false;
            }
        }
        $pregString .= '/';
        return boolval(preg_match($pregString, $serialNumber));
    }

}