<?php

/**
 * Decrypt data from a CryptoJS json encoding string
 *
 * @param mixed $passphrase
 * @param mixed $jsonString
 * @return mixed
 */
function cryptoJsAesDecrypt($passphrase, $jsonString)
{
    $jsonData = json_decode($jsonString, true);
    try {
        $salt = hex2bin($jsonData["s"]);
        $iv = hex2bin($jsonData["iv"]);
    } catch (Exception $e) {
        return null;
    }
    $ct = base64_decode($jsonData["ct"]);
    $concatPassphrase = $passphrase . $salt;
    $md5 = array();
    $md5[0] = md5($concatPassphrase, true);
    $result = $md5[0];

    for ($i = 1; $i < 3; $i++) {
        $md5[$i] = md5($md5[$i - 1] . $concatPassphrase, true);
        $result .= $md5[$i];
    }

    $key = substr($result, 0, 32);
    $data = openssl_decrypt($ct, 'aes-256-cbc', $key, true, $iv);

    return json_decode($data, true);
}

/**
 * Encrypt value to a CryptoJS compatible json encoding string
 *
 * @param mixed $passphrase
 * @param mixed $value
 * @return string
 */
function cryptoJsAesEncrypt($passphrase, $value)
{
    $salt = openssl_random_pseudo_bytes(8);
    $salted = '';
    $dx = '';

    while (strlen($salted) < 48) {
        $dx = md5($dx . $passphrase . $salt, true);
        $salted .= $dx;
    }

    $key = substr($salted, 0, 32);
    $iv = substr($salted, 32, 16);
    $encrypted_data = openssl_encrypt(json_encode($value), 'aes-256-cbc', $key, true, $iv);
    $data = array("ct" => base64_encode($encrypted_data), "iv" => bin2hex($iv), "s" => bin2hex($salt));
    return json_encode($data);
}