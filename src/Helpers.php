<?php
namespace spinshield\spinclient;

use GuzzleHttp\Client;
use Exception;

/**
 * Class Helpers
 * A collection of helper functions.
 */
class Helpers
{
    /**
     * Checks if the provided key is valid.
     *
     * @param string $key The key to validate.
     * @param int $timestamp The timestamp.
     * @param string $salt The salt.
     * @return bool Returns true if the key is valid, false otherwise.
     */
    public function isValidKey($key, $timestamp, $salt)
    {
        if ($signature === (md5($timestamp.$salt))) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Generates a JSON-encoded balance response.
     *
     * @param int $balance The balance value.
     * @return string The JSON-encoded balance response.
     */
    public function balanceResponse(int $balance)
    {
        $data = array(
            "error" => 0,
            "balance" => $balance,
        );
        return json_encode($data);
    }

    /**
     * Checks if the response has an error.
     *
     * @param mixed $input The input to check.
     * @return bool Returns true if the response has an error, false otherwise.
     */
    public function responseHasError($input)
    {
        if (!is_array($input)) {
            $input = json_decode($input, true);
        }
        if (isset($input['error'])) {
            if (!is_int($input['error'])) {
                return true;
            }
            if ($input['error'] > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    /**
     * Converts a JSON string to an associative array.
     *
     * @param string $json The JSON string to convert.
     * @return array The associative array representation of the JSON string.
     */
    public function morphJsonToArray($json)
    {
        return json_decode($json, true);
    }

    /**
     * Generates a JSON-encoded processing error response.
     *
     * @param int $balance The balance value.
     * @return string The JSON-encoded processing error response.
     */
    public function processingError(int $balance = 0)
    {
        $data = array(
            "error" => 2,
            "balance" => $balance,
        );
        return json_encode($data);
    }

    /**
     * Formats a number with the specified precision and separator.
     *
     * @param float $number The number to format.
     * @param int $precision The number of decimal places.
     * @param string $separator The decimal separator.
     * @return string The formatted number.
     */
    public function numberFormatPrecision($number, $precision = 2, $separator = '.')
    {
        $numberParts = explode($separator, $number);
        $response = $numberParts[0];
        if (count($numberParts) > 1 && $precision > 0) {
            $response .= $separator;
            $response .= substr($numberParts[1], 0, $precision);
        }
        return $response;
    }

    /**
     * Converts a float value to an integer.
     *
     * @param float $floatValue The float value to convert.
     * @return int The converted integer value.
     */
    public function floatToIntHelper(float $floatValue): int
    {
        $floatValue = $this->numberFormatPrecision($floatValue);
        return (number_format(($floatValue * 100), 0, '', ''));
    }

    /**
     * Converts an integer value to a float.
     *
     * @param int $intValue The integer value to convert.
     * @param int $precision The number of decimal places.
     * @return string The converted float value.
     */
    public function intToFloatHelper(int $intValue, int $precision = 2)
    {
        return $this->numberFormatPrecision(($intValue / 100), $precision);
    }

    /**
     * Generates a JSON-encoded insufficient balance error response.
     *
     * @param int $balance The balance value.
     * @return string The JSON-encoded insufficient balance error response.
     */
    public function insufficientBalance(int $balance = 0)
    {
        $data = array(
            "error" => 1,
            "balance" => $balance,
        );
        return json_encode($data);
    }
}