<?php

namespace App\Helpers;

use Exception;
use Log;
use app;
use IntlBreakIterator;

class GLHelpers
{
  public static function SJISCheck($s)
  {
    if (strlen($s) !== strlen(mb_convert_encoding(mb_convert_encoding($s, 'SJIS', 'UTF-8'), 'UTF-8', 'SJIS')))
      return true;
    if (mb_strpos($s, '〜') !== false)
      return true;
    // if(mb_strpos($s,'‐') !== false)
    //   return true;
    if (mb_strpos($s, '<') !== false)
      return true;
    if (mb_strpos($s, '>') !== false)
      return true;

    return false;
  }

  public static function SJISCheckHasTag($s)
  {
    if (strlen(strip_tags($s)) !== strlen(mb_convert_encoding(mb_convert_encoding(strip_tags($s), 'SJIS', 'UTF-8'), 'UTF-8', 'SJIS')))
      return true;
    return false;
  }

  public static function unescape($str)
  {
    $ret = '';
    $len = strlen($str);
    for ($i = 0; $i < $len; $i++) {
      if ($str[$i] == '%' && $str[$i + 1] == 'u') {
        $ret .= '\\'.$str[$i+1].$str[$i+2].$str[$i+3].$str[$i+4].$str[$i+5];
        $i += 5;
      } else if ($str[$i] == '%') {
        $ret .= urldecode(substr($str, $i, 3));
        $i += 2;
      } else
        $ret .= $str[$i];
    }
    $json = '{"str":"' . $ret . '"}';
    $arr = json_decode($json, true);
    // dump($arr);
    if (empty($arr)) 
      return '';
    return $arr['str'];
  }

  // 2021.03.12 James : 原方法不支援雙unicode字碼以上的文字
  public static function unescape2($str)
  {
    $ret = '';
    $len = strlen($str);
    for ($i = 0; $i < $len; $i++) {
      if ($str[$i] == '%' && $str[$i + 1] == 'u') {
        $val = hexdec(substr($str, $i + 2, 4));
        if ($val < 0x7f)
          $ret .= chr($val);
        else if ($val < 0x800)
          $ret .= chr(0xc0 | ($val >> 6)) .
            chr(0x80 | ($val & 0x3f));
        else
          $ret .= chr(0xe0 | ($val >> 12)) .
            chr(0x80 | (($val >> 6) & 0x3f)) .
            chr(0x80 | ($val & 0x3f));
        $i += 5;
      } else if ($str[$i] == '%') {
        $ret .= urldecode(substr($str, $i, 3));
        $i += 2;
      } else
        $ret .= $str[$i];
    }
    return $ret;
  }

  /**
   * 取得使用者手續費
   * @param string $glid
   * @return int $fee
   */
  public static function getTransFee($sum, $trans_fee_j = null):int
  {
    try{
      $fee = 0;
      $fee_rule = \Config::get('constant.trans_fee');

      if($trans_fee_j){
        $trans_fee_a = json_decode($trans_fee_j, true);
       
        if($trans_fee_a){
          $fee_rule = $trans_fee_a;
        }else{
          throw new Exception('資料格式錯誤 - 不是 json');
        }
      }
      foreach ($fee_rule as $key => $value) 
      {
        if($key == 'MAX' && is_int($value))
        {
          continue;
        }else if(is_numeric($key) && is_int($value)){
          continue;
        }else{
          throw new Exception('資料格式錯誤 - 規格不符合');
        }
      }
     
      ksort($fee_rule, SORT_NATURAL);
     
      foreach ($fee_rule as $key => $value) 
      {
        if($key == 'MAX')
        {
          $fee = $value;
          break;
        }else if($sum <= intval($key)){
          $fee = $value;
          break;
        }
      }
    }catch (Exception $e) {
      Log::info('helper getTransFee :'.$e->getMessage());
      $fee = -1;
    }finally {
      return $fee;
    }
   
  }

  // 2021/04/09 LS-Itabashi
  /**
   * MS932 & No Shift-JIS拡張文字 & No サロゲートペア & No 結合文字
   */
  public static function isInvalidateCharacterContains($string) {
    if (empty($string)) return false;
    return self::isExUnicodeContains($string) || self::isBasicShiftjis($string) === false;
  }
  
  /**
   * 特殊な文字が含まれているか
   */
  public static function isExUnicodeContains($string) {
    return self::isSurrogatepairContains($string) || self::isCombiningcharContains($string);
  }
  
  /**
   * 基本的なShift-JISだけで構成されているか
   */
  public static function isBasicShiftjis($string) {
    return self::isMs932($string) && self::isExShiftjisContains($string) === false;
  }
  
  /**
   * サロゲートペアが含まれているか
   */
  public static function isSurrogatepairContains($string) {
    $chrArray = preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY);
    foreach ($chrArray as $char) {
      if (strlen($char) === 4)
        return true;
    }
    return false;
  }
  
  /**
   * 結合文字が含まれているか
   */
  public static function isCombiningcharContains($string) {
    return mb_strlen($string, 'UTF-8') !== self::fuzzyLength($string);
  }

  /**
   * システムが合字化できない結合文字を構文解析して認識文字数を算出する
 * サロゲートペアも1文字として数える
   */
  public static function fuzzyLength($string) {
    $fuzzy_length = 0;
    $bi = IntlBreakIterator::createCharacterInstance('ja');
    $bi->setText($string);
    while($bi->next() != IntlBreakIterator::DONE) {
      $fuzzy_length++;
    }
    return $fuzzy_length;
  }
  
  /**
   * MS932で表現可能な文字だけで構成されているか
   */
  public static function isMs932($string) {
    $sjis_win_string = mb_convert_encoding($string, 'SJIS-WIN', 'UTF-8');
    $utf8_convert_back = mb_convert_encoding($sjis_win_string, 'UTF-8', 'SJIS-WIN');
    return $string === $utf8_convert_back;
  }
  
  /**
   * Shift-JIS拡張文字が含まれているか
   */
  public static function isExShiftjisContains($string) {
    $charArray = preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY);
    foreach ($charArray as $char) {
      if (self::isMs932($char) === false) {
        continue;
      }
      $sjisWinChar = mb_convert_encoding($char, 'SJIS-WIN', 'UTF-8');
      if (strlen($sjisWinChar) !== 2) {
        continue;
      }
      $charInt = (ord($sjisWinChar[0]) << 8) | ord($sjisWinChar[1]);
      if ($charInt >= 0x8740 && $charInt <= 0x879E) {
        return true;
      }
      else if ($charInt >= 0xED40 && $charInt <= 0xEDFC) {
        return true;
      }
      else if ($charInt >= 0xEE40 && $charInt <= 0xEE9E) {
        return true;
      }
      else if ($charInt >= 0xEE9F && $charInt <= 0xEEFC) {
        return true;
      }
      else if ($charInt >= 0xFA40 && $charInt <= 0xFC4B) {
        return true;
      }
    }
    return false;
  }
  /**
   * 將部分資料轉換成隱碼
   * 0:閲覧可 1:閲覧不可
   * @param string $data
   * @return string
   */
  public static function hideInformation($data, $type = null)
  {
      $personal_info_flg = session('personal_info_flg');
      
      if($personal_info_flg == 1 && strlen($data) > 0){
          switch($type){
              case 'email':
                  $email    = explode("@", $data);
                  $mailHide = preg_replace('/(.)./u', '$1*', $email[0]);
                  $result   = $mailHide.'@'.$email[1];
                  break;
              default:
                  $result =  preg_replace('/(.)./u', '$1*', $data);
          }
          
      }else{
          $result = $data;
      }

      return $result;
  }
}
