<?php

namespace App\LSPayment;

use Log;

class Api
{

    /**
     * セブンイレブン引換券番号
     * セブンイレブン引換券番号を取得します。
     */
    public static function getSEJPickupNum($orderId)
    {
        $parameters = ['order' => $orderId];
        $retValue = self::_callApi('sendSEJTicket', $parameters, true);

        if($retValue->http_code != 201)
            return null;
        
        if($retValue->statusCode != 0)
            return null;

        if(!isset($retValue->info))
            return null;

        if(!isset($retValue->info->pickupNum))
            return null;

        return $retValue->info->pickupNum;
    }

    public static function cencelOrder($data)
    {
        $parameters = $data;
        $retValue = self::_callApi('cancelOrder', $parameters, true);
        if($retValue->http_code != 201)
            return null;
        
        // if($retValue->statusCode != 0)
        //     return null;

        return $retValue;
    }


    private static function _callApi($path, $parameters, $keepAlive = false)
    {
        
        $fields['reqdata'] = json_encode($parameters);
        return self::_post($path, $fields, $keepAlive);
    }

    private static function _post($path, $fields, $keepAlive = false)
    {
        // $time = \DateTime::createFromFormat('U.u', sprintf('%.04f', \microtime(true)));
        // $time->setTimezone(new \DateTimeZone('Asia/Tokyo'));
        //$carbon = \Carbon::instance($time);
        //$fields['xtime'] = sprintf('%s.%03d', $carbon->format('Y/m/d H:i:s'), ($carbon->micro / 1000));
        // $fields['xtime'] = sprintf('%s.%03d', $time->format('Y/m/d H:i:s'), ($time->format('u') / 1000));
        // $fields['xcd'] = hash_hmac('sha256', self::_build_query($fields), config('gettii.xcd'));

        $timeout = $keepAlive ? 0 : 10;
        // $host = 'http://192.168.0.182:8002/api';
        $host =  config('lspaymodule.url');

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, sprintf('%s/%s', $host, $path));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        // curl_setopt($curl, CURLOPT_USERPWD, sprintf('%s:%s', config('gettii.basic_id'),config('gettii.basic_pass')));
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($fields));
        
        $result = curl_exec($curl);

        $header = curl_getinfo($curl);
        if (!curl_errno($curl)) {
            $result = json_decode($result);
            if (is_object($result)) {
                $result->http_code = $header['http_code'];
            } else {
                $result = json_decode('{}');
                $result->http_code = $header['http_code'];
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
