<?php

/**
 * Translate string from key
 *
 * @param string $key
 * @param array $params
 * @param string $locale, default is en (english)
 * @return string was translated
 */
if (!function_exists('translate')) {
    function translate($key, $params = [], $locale = 'en')
    {
        $filePath = resource_path('/lang/' . $locale . '.json');

        if (!is_readable($filePath)) {
            return false;
        }

        $fileJson = file_get_contents($filePath);
        $dataJson = json_decode($fileJson, true);
        $partKeys = explode('.', $key);

        foreach ($partKeys as $partKey) {
            if (!isset($dataJson[$partKey])) {
                return $key;
            }
            $dataJson = $dataJson[$partKey];
        }

        if ($params) {
            foreach ($params as $key => $value) {
                $dataJson = str_replace(':' . $key, $value, $dataJson);
            }
        }

        return $dataJson;
    }
}

/**
 * Create random array from other array
 *
 * @param array $array
 * @param int $amount
 * @return array
 */

if (!function_exists('array_random')) {
    function array_random($array, $amount = 1)
    {
        $keys = array_rand($array, $amount);

        if ($amount == 1) {
            return $array[$keys];
        }

        $results = [];

        foreach ($keys as $key) {
            $results[] = $array[$key];
        }

        return $results;
    }

}

/**
 * Convert csv to array
 *
 * @param string $fileName
 * @param string $delimiter
 * @return array
 */
if (!function_exists('csvToArray')) {
    function csvToArray($fileName = '', $delimiter = ',')
    {
        if (!file_exists($fileName) || !is_readable($fileName)) {
          return false;
        }

        $header = null;
        $data = [];

        if (($handle = fopen($fileName, 'r')) !== false) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false) {
                if (!$header) {
                  $header = $row;
                } else {
                  $data[] = array_combine($header, $row);
                }
            }

            fclose($handle);
        }

        return $data;
    }
}

/**
 * Format data response
 *
 * @param int $code
 * @param array $description
 * @return array
 */
if (!function_exists('formatResponse')) {
    function formatResponse($code, $description) {
        if (! $code || empty($description)) {
            return false;
        }

        return $dataResponse = [
            'code' => $code,
            'description' => is_array($description) ? $description : [$description],
        ];
    }
}

/**
 * Get distance from two location
 *
 * @param double $lat1
 * @param double $lng1
 * @param double $lat2
 * @param double $lng2
 * @return double
 */
if (!function_exists('distanceGeoPoints')) {
    function distanceGeoPoints ($lat1, $lng1, $lat2, $lng2)
    {
        return 6378.10 * ACOS(COS(deg2rad($lat1))
            * COS(deg2rad($lat2))
            * COS(deg2rad($lng1) - deg2rad($lng2))
            + SIN(deg2rad($lat1))
            * SIN(deg2rad($lat2)));
    }
}

/**
 * Update fields unnormalized
 *
 * @param string $string
 * @param string $value
 * @return string
 */

if (!function_exists('updateFieldsUnnormalized')) {
    function updateFieldsUnnormalized($string, $value)
    {
        if (!$string) {
            return $value;
        }

        $arr = explode(',', $string);

        if (in_array($value, $arr)) {
            $arr = array_diff($arr, [$value]);
        }

        $arr[count($arr) + 1] = $value;

        return implode(',', $arr);
    }
}

/**
 * remove item fields unnormalized
 *
 * @param string $string
 * @param string $value
 * @return string
 */

if (!function_exists('removeFieldsUnnormalized')) {
    function removeFieldsUnnormalized($string, $value)
    {
        $arr = explode(',', $string);

        if (in_array($value, $arr)) {
            $arr = array_diff($arr, [$value]);
        }

        return implode(',', $arr);
    }
}

/**
 * Remove country code in phone number
 *
 * @param string $phoneNumber
 * @return string
 */

if (!function_exists('removeCountryCodeInPhoneNumber')) {
    function removeCountryCodeInPhoneNumber($phoneNumber)
    {
        $countryCodes = array_column(config('countryCode'), 'code');

        foreach ($countryCodes as $countryCode) {
            $phoneNumberIgnoreCountryCode = preg_replace("/^\+{$countryCode}/", '0', $phoneNumber);

            if ($phoneNumberIgnoreCountryCode != $phoneNumber) {
                return $phoneNumberIgnoreCountryCode;
            }
        }

        return $phoneNumber;
    }
}
