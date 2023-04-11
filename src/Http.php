<?php

/**
 * User: Jin's
 * Date: 2023/4/11 16:46
 * Mail: jin.aiyo@hotmail.com
 * Desc: TODO
 */
class Http
{
    public static function postBody($url, $data, $successCode = 0)
    {
        if (is_array($data)) $data = json_encode($data, 64|256);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($data))
        );
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $data = curl_exec($ch);
        curl_close($ch);
        if (!$data) return false;

        $data = json_decode($data, true);
        if (isset($data['code']) && $data['code'] == $successCode) {
            return $data['data'] ?? [];
        }

        return [];
    }
}