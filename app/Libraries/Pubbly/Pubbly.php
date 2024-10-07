<?php
namespace App\Libraries\Pubbly;


use Illuminate\Support\Facades\Config;

class Pubbly
{
    /** @var string The Pubbly API key to be used for requests. */
    public $apiKey;

    /** @var string The Pubbly Secret key to be used for requests. */
    public $secretKey;

    /** @var string The base URL for the Pubbly API. */
    public $apiBase = 'https://payments.pabbly.com/api/v1/';

    const DEFAULT_TIMEOUT = 80;
    const DEFAULT_CONNECT_TIMEOUT = 30;

    private $timeout = self::DEFAULT_TIMEOUT;
    private $connectTimeout = self::DEFAULT_CONNECT_TIMEOUT;


    public function __construct()
    {
        $this->apiKey = Config::get('services.pubbly.api_key');
        $this->secretKey = Config::get('services.pubbly.secret_key');
    }

    public function request($method, $absUrl, $params=[])
    {
        $method = strtolower($method);
        if ('get' === $method) {
            $opts[CURLOPT_HTTPGET] = 1;
            if (count($params) > 0) {
                $encoded = self::encodeParameters($params);
                $absUrl = "{$absUrl}?{$encoded}";
            }
        } elseif ('post' === $method) {
            $opts[CURLOPT_POST] = 1;
            $opts[CURLOPT_POSTFIELDS] = self::encodeParameters($params);
        }elseif ('put' === $method) {
            $opts[CURLOPT_CUSTOMREQUEST] = 'PUT';
            $opts[CURLOPT_POSTFIELDS] = self::encodeParameters($params);
        } elseif ('delete' === $method) {
            $opts[CURLOPT_CUSTOMREQUEST] = 'DELETE';
            if (count($params) > 0) {
                $encoded = self::encodeParameters($params);
                $absUrl = "{$absUrl}?{$encoded}";
            }
        }

        $headers[] = 'Expect: ';

       // $absUrl = utf8($absUrl);
        $opts[CURLOPT_URL] = $absUrl;
        $opts[CURLOPT_RETURNTRANSFER] = true;
        $opts[CURLOPT_CONNECTTIMEOUT] = $this->connectTimeout;
        $opts[CURLOPT_TIMEOUT] = $this->timeout;
        $opts[CURLOPT_HTTPHEADER] = $headers;
        $opts[CURLOPT_SSL_VERIFYPEER] = false;

        return $this->execute($absUrl, $opts);

    }

    private function handleCurlError($url, $errno, $message)
    {
        switch ($errno) {
            case CURLE_COULDNT_CONNECT:
            case CURLE_COULDNT_RESOLVE_HOST:
            case CURLE_OPERATION_TIMEOUTED:
                $msg = "Could not connect to Pubbly ({$url}).  Please check your "
                    . 'internet connection and try again.';

                break;

            case CURLE_SSL_CACERT:
            case CURLE_SSL_PEER_CERTIFICATE:
                $msg = "Could not verify Pubbly's SSL certificate.  Please make sure "
                    . 'that your network is not intercepting certificates.  '
                    . "(Try going to {$url} in your browser.)  "
                    . 'If this problem persists,';

                break;

            default:
                $msg = 'Unexpected error communicating with Pubbly.  '
                    . 'If this problem persists,';
        }
        $msg .= ' let us know at admin@pabbly.com.';

        $msg .= "\n\n(Network error [errno {$errno}]: {$message})";

        throw new \Exception($msg);
    }


    private function execute($absUrl, $opts){
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        //curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, $this->apiKey . ":" . $this->secretKey);
        $rbody = curl_exec($ch);


        if (false === $rbody) {
            $errno = curl_errno($ch);
            $message = curl_error($ch);
        } else {
            $rcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        }
        curl_close($ch);
        if (false === $rbody) {
            try{
                $this->handleCurlError($absUrl, $errno, $message);
            }catch (\Exception $e){
                return ['success'=>0, 'error_msg'=>$e->getMessage()];
            }
        }
        $rbody = json_decode($rbody,true);
        return [$rbody, $rcode];

        /*$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml', $additionalHeaders));
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_USERPWD, $this->apiKey . ":" . $this->secretKey);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payloadName);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        $return = curl_exec($ch);
        curl_close($ch);*/
    }

    public static function encodeParameters($params)
    {
        $flattenedParams = self::flattenParams($params);
        $pieces = [];
        foreach ($flattenedParams as $param) {
            list($k, $v) = $param;
            $pieces[] = self::urlEncode($k) . '=' . self::urlEncode($v);
        }

        return implode('&', $pieces);
    }

    public static function flattenParams($params, $parentKey = null)
    {
        $result = [];

        foreach ($params as $key => $value) {
            $calculatedKey = $parentKey ? "{$parentKey}[{$key}]" : $key;

            if (self::isList($value)) {
                $result = array_merge($result, self::flattenParamsList($value, $calculatedKey));
            } elseif (is_array($value)) {
                $result = array_merge($result, self::flattenParams($value, $calculatedKey));
            } else {
                array_push($result, [$calculatedKey, $value]);
            }
        }

        return $result;
    }

    public static function isList($array)
    {
        if (!is_array($array)) {
            return false;
        }
        if ([] === $array) {
            return true;
        }
        if (array_keys($array) !== range(0, count($array) - 1)) {
            return false;
        }

        return true;
    }

    public static function flattenParamsList($value, $calculatedKey)
    {
        $result = [];

        foreach ($value as $i => $elem) {
            if (self::isList($elem)) {
                $result = array_merge($result, self::flattenParamsList($elem, $calculatedKey));
            } elseif (is_array($elem)) {
                $result = array_merge($result, self::flattenParams($elem, "{$calculatedKey}[{$i}]"));
            } else {
                array_push($result, ["{$calculatedKey}[{$i}]", $elem]);
            }
        }

        return $result;
    }

    public static function urlEncode($key)
    {
        $s = urlencode((string) $key);

        // Don't use strict form encoding by changing the square bracket control
        // characters back to their literals. This is fine by the server, and
        // makes these parameter strings easier to read.
        $s = str_replace('%5B', '[', $s);

        return str_replace('%5D', ']', $s);
    }

    private function hasHeader($headers, $name)
    {
        foreach ($headers as $header) {
            if (0 === strncasecmp($header, "{$name}: ", strlen($name) + 2)) {
                return true;
            }
        }

        return false;
    }

}