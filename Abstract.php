<?php

abstract class PhEchonest_Abstract
{
    protected static $_apiKey = 'XXQUZTRTJZWLMLINE';
    protected static $_format = 'json';
    
    protected static $baseUrl = 'http://developer.echonest.com/api/v4';
    
    public static function setApiKey($apiKey)
    {
        self::$_apiKey = $apiKey;
    }
    
    protected static function makeRequest($method, $query = array())
    {
        $url = self::buildRequestUrl($method, $query);
        $result = Zend_Json::decode(file_get_contents($url));
        
        if ($result['response']['status']['message'] != 'Success') {
            global $log;
            $log->debug($url);
            $log->debug($result);
            throw new Exception('API request failed');
        }
        unset($result['response']['status']);
        return $result['response'];
    }
    
    protected static function buildRequestUrl($method, $query)
    {
        $url = static::$baseUrl . '/'.static::$_resource.'/'.$method.'?api_key='.static::$_apiKey;
        foreach ($query as $key => $value) {
            $key = urlencode($key);
            $value = urlencode($value);
            $url .= "&{$key}={$value}";
        }
        //echo $url;
        return $url;
    }
    
}