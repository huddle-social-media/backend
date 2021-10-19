<?php

namespace Services;

require_once __DIR__."/../../../helpers/JWT/JWT.php";

class Authorization
{
    // The secret key
    private const SECRET_KEY = "secret_key";

    /**
     * Check the authorization header value for bearer or not
     * 
     * @param string httpAuth The authorization value
     * 
     * @return boolean Is valid type or not
     */
    public function isTypeValid($httpAuth)
    {
        return preg_match('/Bearer\s(\S+)/', $httpAuth);
    }

    /**
     * Extracting the access token from the header value
     * 
     * @param string httpAuth The authorization value
     * 
     * @return string access token
     */
    public function extractAccessToken($httpAuth)
    {
        if(!preg_match('/Bearer\s(\S+)/', $httpAuth, $matches) || !$matches[1])
            return '';
        return $matches[1];
    }

    /**
     * Check the access token validity against secret key
     * 
     * @param string httpAuth The authorization value
     * @param string secretKey The secret key
     * 
     * @return boolean Valid or not
     */
    public function isAccessTokenVerified($httpAuth)
    {
        $segments = explode('.', $this->extractAccessToken($httpAuth));
        if(count($segments) !== 3)
            return false;
        $header = json_decode(\Helpers\JWT::urlSafeBase64Decode($segments[0]));
        $payload = json_decode(\Helpers\JWT::urlSafeBase64Decode($segments[1]));

        // it is necessary to contain alg and the exp
        if(!$header || !$payload || !property_exists($header, 'alg') || !property_exists($payload, 'exp') || !property_exists($payload, 'exp'))
            return false;
        $alg = $header->alg;
        $exp = $payload->exp;
        if(!is_numeric($exp) || strlen($alg) > 1 || !\Helpers\JWT::verify($segments[0].'.'.$segments[1], $segments[2], self::SECRET_KEY, $alg))
            return false;
        return true;
    } 

    /**
     * Getting the payload from access token
     * 
     * @param string accessToken The access token
     * 
     * @return array The payload
     */
    public function getPayload($accessToken)
    {
        return \Helpers\JWT::decode($accessToken, self::SECRET_KEY);
    }

    /**
     * Getting the payload from access token
     * 
     * @return string The secret key
     */
    public function getSecretKey()
    {
        return self::SECRET_KEY;
    }

}