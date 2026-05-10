<?php

namespace App\Services;

class PhoneNormalizerService
{

    public function normalize($phone)
    {

        if (!$phone) {
            return null;
        }

        // Remove spaces and special characters
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Remove leading +
        $phone = ltrim($phone, '+');

        // If number starts with 0 → replace with 94
        if (substr($phone, 0, 1) === '0') {
            $phone = '94' . substr($phone, 1);
        }

        // If number starts with 7 (missing country code)
        if (substr($phone, 0, 1) === '7') {
            $phone = '94' . $phone;
        }

        return $phone;
    }

}