<?php
/**
 * JWT
 *
 * User: hihone
 * Date: 2019/3/1
 * Time: 10:04
 * Description:
 */

namespace hihone\jwt;

class JWT
{
    private static $headers = [
        'alg' => 'HS256',
        'typ' => 'JWT',
    ];

    private static $payload = [];

    private static $key = '!^LC!cTa&%HJ^APe$69FNd8z!0doKHOu';

    /**
     * 生成 token
     *
     * @param array $data
     *
     * @return string
     */
    public static function getToken(array $data = [])
    {
        self::payload($data);
        $base64Header            = self::base64UrlEncode(json_encode(self::$headers, JSON_UNESCAPED_UNICODE));
        $base64Payload           = self::base64UrlEncode(json_encode(self::$payload, JSON_UNESCAPED_UNICODE));
        $banse64HeaderAndPayload = $base64Header . '.' . $base64Payload;
        $signature               = self::signature($banse64HeaderAndPayload);

        return $banse64HeaderAndPayload . '.' . $signature;
    }

    /**
     * 可以自定其他的参数
     *
     * @param array $data
     */
    public static function payload(array $data = [])
    {
        $now_time = time();
        $payloads = [
            'iss' => config('jwt.iss'),#签发人
            'exp' => $now_time + 2 * 60 * 60,#过期时间
            'sub' => config('jwt.sub'),#主题
            'aud' => config('jwt.aud'),#受众
            'nbf' => $now_time + 30,#生效时间
            'iat' => $now_time,#签发时间
            'jti' => uniqid('JWT' . $now_time),#编号
        ];
        self::$payload = array_merge($payloads, $data);
    }

    /**
     * token 验证
     *
     * @param string $token
     *
     * @return bool
     * @throws JWTException
     */
    public static function verify(string $token)
    {
        if (empty($token)) return false;

        $tokens = explode('.', $token);
        if (count($tokens) != 3) return false;

        list($base64Header, $base64Payload, $sign) = $tokens;

        #JWT 算法
        $base64DecodeHeader = json_decode(self::base64UrlDecode($base64Header), JSON_OBJECT_AS_ARRAY);
        if (empty($base64DecodeHeader) || empty($base64DecodeHeader['alg'])) throw new JWTException('jwt 缺少 header！');

        #签名验证
        if (self::signature($base64Header . '.' . $base64Payload) !== $sign) throw new JWTException('签名错误！');

        #payload
        $payload = json_decode(self::base64UrlDecode($base64Payload), JSON_OBJECT_AS_ARRAY);
        $now_time = time();
        #签发时间验证
        if (isset($payload['iat']) && $payload['iat'] > $now_time) throw new JWTException('签发时间错误！');
        #有效期验证
        if (isset($payload['exp']) && $payload['exp'] < $now_time) throw new JWTException('token 已失效！');
        #计算 nbf 时间是否生效，当前时间小于 nbf 时间则不处理
        if (isset($payload['nbf']) && $payload['nbf'] > $now_time) throw new JWTException('操作过于频繁，请稍后再试。');

        return $payload;
    }

    /**
     * 生成签名
     *
     * @param $base64HeaderAndPayload
     *
     * @return mixed
     */
    private static function signature(string $base64HeaderAndPayload)
    {
        $data = $base64HeaderAndPayload . '.' . self::$key;
        return self::base64UrlEncode(hash_hmac('sha256', $data, self::$key, true));
    }

    /**
     * @param $string
     *
     * @return mixed
     */
    private static function base64UrlEncode(string $string)
    {
        return str_replace(['=', '+', '/'], ['', '-', '_'], base64_encode($string));
    }

    /**
     * @param $string
     *
     * @return bool|string
     */
    private static function base64UrlDecode(string $string)
    {
        $remainder = strlen($string) % 4;
        #不满足长度，则表示有‘=’号，差多少，补充多少个‘=’号
        if ($remainder) {
            $addlen = 4 - $remainder;
            $string .= str_repeat('=', $addlen);
        }

        return base64_decode(str_replace(['-', '_'], ['+', '/'], $string));
    }


}