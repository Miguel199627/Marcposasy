<?php
namespace App\Config;

class Auth {
  public static function signIn(array $data) {
    $time = time() + ( 3600 * ServicesContainer::getConfig()['session-time'] );
    setcookie(
        ServicesContainer::getConfig()['session-name'],
        Auth::encryptCookie( serialize($data) ),
        $time,
        '/'
    );
  }

  public static function destroy() {
    if(empty($_COOKIE[ServicesContainer::getConfig()['session-name']])) return;

    unset( $_COOKIE[ServicesContainer::getConfig()['session-name']] );
    setcookie(ServicesContainer::getConfig()['session-name'], null, -1, '/');
  }

  public static function getCurrentUser() : \stdClass {
    if(empty($_COOKIE[ServicesContainer::getConfig()['session-name']])) {
        throw new \Exception("Auth cookie is not defined");
    }

    $current = self::decryptCookie($_COOKIE[ServicesContainer::getConfig()['session-name']]);
    return (object)unserialize($current);
  }

  public static function isLoggedIn() : bool {
    if(empty($_COOKIE[ServicesContainer::getConfig()['session-name']])) return false;

    $current = self::decryptCookie($_COOKIE[ServicesContainer::getConfig()['session-name']]);
    return @unserialize($current) === false ? false : true;
  }

  private static function encryptCookie(string $value) : string {
    $key = self::aud();
    $iv_size = openssl_cipher_iv_length('AES-256-ECB');
    $iv = openssl_random_pseudo_bytes($iv_size);
    return openssl_encrypt($value, 'AES-256-ECB', $key, OPENSSL_RAW_DATA, $iv);
  }

  private static function decryptCookie(string $value) : string {
    $key = self::aud();
    $iv_size = openssl_cipher_iv_length('AES-256-ECB');
    $iv = openssl_random_pseudo_bytes($iv_size);
    return openssl_decrypt($value, 'AES-256-ECB', $key, OPENSSL_RAW_DATA, $iv);
  }

  private static function aud() : string {
    $aud = '';

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $aud = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $aud = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $aud = $_SERVER['REMOTE_ADDR'];
    }

    $aud .= @$_SERVER['HTTP_USER_AGENT'];
    $aud .= gethostname();

    return base64_encode(ServicesContainer::getConfig()['secret-key'] . $aud);
  }
}
