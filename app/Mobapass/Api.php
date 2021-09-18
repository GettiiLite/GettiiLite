<?php

namespace App\Mobapass;

use Carbon\Carbon;
use Log;

class Api
{
    public static function callApi($path, $reqdata, $keepAlive = false)
    {
        $fields['aid'] = config('mobapass.aid');
        $fields['reqdata'] = $reqdata;

        $time = \DateTime::createFromFormat('U.u', sprintf('%.04f', \microtime(true)));
        $time->setTimezone(new \DateTimeZone('Asia/Taipei'));
        $carbon = Carbon::instance($time);
        $fields['xtime'] = sprintf('%s.%03d', $carbon->format('Y/m/d H:i:s'), ($carbon->micro / 1000));
        $fields['xcd'] = hash_hmac('sha256', self::_build_query($fields), config('mobapass.xcd'));

        $timeout = $keepAlive ? 0 : 10;
        Log::debug(sprintf('%s.%s', config('fields'), print_r($fields,true)));
        Log::debug(sprintf('%s.%s', config('mobapass.url'), $path));
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, sprintf('%s.%s', config('mobapass.url'), $path));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        //curl_setopt($curl, CURLOPT_USERPWD, sprintf('%s:%s', config('gettii.basic_id'), config('gettii.basic_pass')));
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($fields));

        $result = curl_exec($curl);
        Log::debug('00api=' . $path . ' result=' . print_r($result, true));

        $header = curl_getinfo($curl);
        if (!curl_errno($curl)) {
            $result = json_decode($result);
            if (is_object($result)) {
                $result->statusCode = $header['http_code'];
            } else {
                $result = $header['http_code'];
            }
        } else {
            // ステータスコードを返す
            $result = $header['http_code'];
        }

        Log::debug('api=' . $path . ' result=' . print_r($result, true));
        
        curl_close($curl);
        return $result;
    }    
    private static function _build_query($parameters)
    {
        $query = '';
        foreach ($parameters as $key => $value) {
            $query .= sprintf('%s%s=%s', $query == '' ? '' : '&', $key, $value);
        }

        return $query;
    }
}
