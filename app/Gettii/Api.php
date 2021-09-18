<?php

namespace App\Gettii;

use Log;

class Api
{

    /**
     * セブンイレブン決済
     * セブンイレブンに決済要求を行います。
     */
    public static function paySejSend($parameters)
    {
         return self::_callApi('paysej.send.api', $parameters);
    }

    /**
     * セブンイレブン決済変更
     * セブンイレブンの決済要求の内容を変更します。
     */
    public static function paySejUpdate($parameters)
    {
         return self::_callApi('paysej.update.api', $parameters);
    }

    /**
     * セブンイレブン決済キャンセル
     * セブンイレブンの決済キャンセルします。
     */
    public static function paySejCancel($parameters)
    {
         return self::_callApi('paycs.cancel.api', $parameters);
    }
    
    /**
     * セブンイレブン決済ステータス取得
     * セブンイレブン決済のステータスを取得します。
     */
    public static function paySejStatus($parameters)
    {
         return self::_callApi('paysej.status.api', $parameters);
    }

    /**
     * カード決済確定
     * 仮売上状態のカード決済を実売上にします。
     */
    public static function payCardCommit($parameters)
    {
         return self::_callApi('paycard.commit.api', $parameters);
    }
        
    /**
     * カード決済金額変更
     * 実売上状態のカード決済の決済金額を変更します。
     */
    public static function payCardUpdate($parameters)
    {
         return self::_callApi('paycard.update.api', $parameters);
    }

    /**
     * カード決済キャンセル
     * カード決済を取消します。
     */
    public static function payCardCancel($parameters)
    {
         return self::_callApi('paycard.cancel.api', $parameters);
    }

    /**
     * カード決済状態確認
     * 決済取引の状態を確認します。
     */
    public static function payCardStatus($parameters)
    {
         return self::_callApi('paycard.status.api', $parameters);
    }

    private static function _callApi($path, $parameters, $keepAlive = false)
    {
        $fields['aid'] = config('gettii.aid');
        $fields['reqdata'] = json_encode($parameters);

        return self::_post($path, $fields, $keepAlive);
    }

    private static function _post($path, $fields, $keepAlive = false)
    {
        $time = \DateTime::createFromFormat('U.u', sprintf('%.04f', \microtime(true)));
        $time->setTimezone(new \DateTimeZone('Asia/Tokyo'));
        //$carbon = \Carbon::instance($time);
        //$fields['xtime'] = sprintf('%s.%03d', $carbon->format('Y/m/d H:i:s'), ($carbon->micro / 1000));
        $fields['xtime'] = sprintf('%s.%03d', $time->format('Y/m/d H:i:s'), ($time->format('u') / 1000));
        $fields['xcd'] = hash_hmac('sha256', self::_build_query($fields), config('gettii.xcd'));

        $timeout = $keepAlive ? 0 : 10;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, sprintf('%s/%s', config('gettii.url'), $path));
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_USERPWD, sprintf('%s:%s', config('gettii.basic_id'),config('gettii.basic_pass')));
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
