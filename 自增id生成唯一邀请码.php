<?php


echo eyz_encode_open_id(200010, 6) . "<br>";
/**
* $user_id 用户id
* $len 生成邀请码的位数
*/
function eyz_encode_open_id($user_id, $len) {
    static $source_string = 'E5FCDG3HQA4B1NPJ2RSTUV67MWX89KLYZ';
    //先转成33进制
    $user_id = $user_id;
    $code = '';
    while ( $user_id > 0) {
        $mod = $user_id % strlen($source_string);
        $user_id = ($user_id - $mod) / strlen($source_string);
        $code = $source_string[$mod].$code;
    }
    // 生成6位邀请码,不够 用0补全
    if(empty($code[$len-1]))
        $code = str_pad($code, $len, '0', STR_PAD_LEFT);
    // return $code;
    //加密
    $lastChar = substr($code, -1);
    $step = strpos($source_string,$lastChar) - ($len - 3);
    $strLen = strlen($code);
    for ($i=0;$i<$strLen-1;$i++){
        // var_dump($code[$i]);
        $b = strpos($source_string, $code[$i]);
        if($step%2)
            $local = $b+$step-$i;
        else
            $local = $b+$step+$i;

        if ($local < 0)
            $local = strlen($source_string) + $local;

        if($local >= strlen($source_string) ){
            $local = $local - strlen($source_string);
        }
        if (isset($source_string[$local])) {
            $code[$i]= $source_string[$local];
        }
    }
    return $code;
}
echo eyz_decode_open_id("8XLABL", 6) . "<br>";
function eyz_decode_open_id($code, $len) {
    // static $source_string = 'E5FCDG3HQA4B1NOPIJ2RSTUV67MWX89KLYZ';
    static $source_string = 'E5FCDG3HQA4B1NPJ2RSTUV67MWX89KLYZ';
    //解密
    $lastChar = substr($code, -1);
    $step = strpos($source_string,$lastChar) - ($len - 3) ;
    $strLen = strlen($code);

    for ($i=0;$i<$strLen-1;$i++){
        if($step%2)
            $local = strpos($source_string,$code[$i])-$step+$i;
        else
            $local = strpos($source_string,$code[$i])-$step-$i;

        if ($local < 0)
            $local = strlen($source_string) + $local;

        if($local >= strlen($source_string)){
            $local = $local - strlen($source_string);
        }

        $code[$i] = $source_string[$local];
    }
    //进制转换为10进制
    if (strrpos($code, '0') !== false)
        $code = substr($code, strrpos($code, '0')+1);
    $len = strlen($code);
    $code = strrev($code);
    $num = 0;
    for ($i=0; $i < $len; $i++) {
        $num += strpos($source_string, $code[$i]) * pow(strlen($source_string), $i);
    }
    return $num;
}