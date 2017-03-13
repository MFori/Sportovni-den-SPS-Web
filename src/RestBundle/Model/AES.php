<?php
/**
 * Created by PhpStorm.
 * User: Martin Forejt, forejt.martin97@gmail.com
 * Date: 09.01.2017
 * Time: 17:02
 */

namespace RestBundle\Model;

#PHP AES-128-CBC
#Developed by Takis Maletsas
class AES
{
    private $data, $key, $cipher, $mode, $IV;
    public function __construct($cipher = MCRYPT_RIJNDAEL_128, $mode = MCRYPT_MODE_CBC)
    {
        $this->key    = null;
        $this->cipher = $cipher;
        $this->mode   = $mode;
        $this->IV     = null;
    }
    public function encrypt()
    {
        $this->key = is_null($this->key) ? md5(substr(sha1(rand()), 2, 10)) : $this->key;
        $this->IV  = is_null($this->IV) ? mcrypt_create_iv(mcrypt_get_iv_size($this->cipher, $this->mode), MCRYPT_RAND) : $this->IV;
        return trim(base64_encode(str_rot13(mcrypt_encrypt($this->cipher, $this->key, $this->data, $this->mode, $this->IV))));
    }
    public function decrypt()
    {
        return trim(mcrypt_decrypt($this->cipher, $this->key, str_rot13(base64_decode($this->data)), $this->mode, $this->IV));
    }
    public function setData($data)
    {
        $this->data = $data;
    }
    public function setKey($key)
    {
        $this->key = $key;
    }
    public function setIV($IV)
    {
        $this->IV = $IV;
    }
    public function getKey()
    {
        return $this->key;
    }
    public function getIV()
    {
        return $this->IV;
    }
}