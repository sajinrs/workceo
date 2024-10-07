<?php
namespace App\Libraries\ZohoSubscriptions;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

class ZohoSubscription
{
    /** @var string The Zoho API client ID to be used for refresh token. */
    public $clientId;

    /** @var string The Zoho API client secret to be used refresh token. */
    public $clientSecret;

    /** @var string The Zoho API redirect URI to be used refresh token. */
    public $redirectUri;

    /** @var string The Zoho API access token to be used for requests. */
    public $accessToken;

    /** @var string The Zoho API refresh token to be used for requests. */
    public $refreshToken;

    /** @var string The Zoho API token Expire Time. */
    public $tokenExpireTime = 3300;

    /** @var string The Zoho Organization Id to be used for requests. */
    public $organizationId;

    /** @var string The base URL for the Pubbly API. */
    public $apiBase = 'https://subscriptions.zoho.com/api/v1/';

    const DEFAULT_TIMEOUT = 80;
    const DEFAULT_CONNECT_TIMEOUT = 30;

    private $timeout = self::DEFAULT_TIMEOUT;
    private $connectTimeout = self::DEFAULT_CONNECT_TIMEOUT;


    public function __construct()
    {
        $this->clientId = Config::get('services.zoho.zoho_client_id');
        $this->clientSecret = Config::get('services.zoho.zoho_client_secret');
        $this->redirectUri = Config::get('services.zoho.zoho_redirect_uri');
        $this->refreshToken = Config::get('services.zoho.zoho_refresh_token');
        $this->organizationId = Config::get('services.zoho.zoho_org_id');

        $this->accessToken = $this->getToken();
    }

    public function request($method, $absUrl, $params=[])
    {
        $method = strtolower($method);
        $headers = array('X-com-zoho-subscriptions-organizationid:'.$this->organizationId,
            'Authorization:Zoho-oauthtoken '.$this->accessToken,
            );

        if ('get' === $method) {
            $opts[CURLOPT_HTTPGET] = 1;
            if (count($params) > 0) {
                $encoded = self::encodeParameters($params);
                $absUrl = "{$absUrl}?{$encoded}";
            }
        } elseif ('post' === $method) {
            $opts[CURLOPT_POST] = 1;
            $opts[CURLOPT_POSTFIELDS] = (is_array($params))? self::encodeParameters($params):$params;
            if(!is_array($params)){
                $headers[] = 'Content-Type:application/json';
            }
        }elseif ('put' === $method) {
            $opts[CURLOPT_CUSTOMREQUEST] = 'PUT';
            $opts[CURLOPT_POSTFIELDS] = (is_array($params))? self::encodeParameters($params):$params;
            if(!is_array($params)){
                $headers[] = 'Content-Type:application/json';
            }
        } elseif ('delete' === $method) {
            $opts[CURLOPT_CUSTOMREQUEST] = 'DELETE';
            if (count($params) > 0) {
                $encoded = self::encodeParameters($params);
                $absUrl = "{$absUrl}?{$encoded}";
            }
        }



        //$absUrl = utf8($absUrl);
        $opts[CURLOPT_URL] = $absUrl;
        $opts[CURLOPT_RETURNTRANSFER] = true;
        $opts[CURLOPT_CONNECTTIMEOUT] = $this->connectTimeout;
        $opts[CURLOPT_TIMEOUT] = $this->timeout;
        $opts[CURLOPT_HTTPHEADER] = $headers;
        $opts[CURLOPT_SSL_VERIFYPEER] = false;
        //$opts[CURLOPT_HEADER] = 1;

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

    private function getToken(){
        if(!$this->isTokenExist()){
            $this->refreshToken();
        }
        return Session::get('ZOHO_ACCESS_TOKEN');
    }

    private function isTokenExist(){

        if(Session::has('ZOHO_TOKEN_CREATE_TIME') && (time() - Session::get('ZOHO_TOKEN_CREATE_TIME') < $this->tokenExpireTime)){
           return true;
        }

        return false;
    }

    private function refreshToken(){
        $url  = 'https://accounts.zoho.com/oauth/v2/token';
        $params['refresh_token'] = $this->refreshToken;
        $params['client_id'] = $this->clientId;
        $params['client_secret'] = $this->clientSecret;
        $params['redirect_uri'] = $this->redirectUri;
        $params['grant_type'] = 'refresh_token';

        $resp = $this->request('post', $url,$params);
        if(isset($resp[0]['access_token'])){
            Session::put('ZOHO_ACCESS_TOKEN',$resp[0]['access_token']);
            Session::put('ZOHO_TOKEN_CREATE_TIME',time());
            Session::save();
        }

        return true;
    }

}