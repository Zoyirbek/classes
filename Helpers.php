<?php

namespace RzPack;

class Helpers
{
	public static function pr($val=null, $die=0)
	{
	    echo '<pre>';
	    print_r($val);
	    echo '</pre>';
	    if ($die) die;
	}

	public static function dump($val=null, $die=0) 
	{
	    echo "<pre>";
	    var_dump($val);
	    echo "</pre>";
	    if($die) die;
	}

	public static function dd($data)
	{
	    echo "<pre>";
	    var_dump($val);
	    echo "</pre>";
	    die;
	}

	public static function method(string $get=null)
	{
	    $method = $_SERVER['REQUEST_METHOD'];
	    if(!is_null($get)) return ($method === mb_strtoupper($get));
	    return $method;
	}

	public static function load(array $fillable = [])
	{
	    if(empty($fillable) || !isset($_POST)) return null;
	    $data = [];
	    foreach ($_POST as $key => $value) {
	        if (in_array($key, $fillable)) {
	            $data[$key] = htmlentities(trim($value));
	        }     
	    }
	    return $data;
	}

	public static function old(string $fieldname)
	{
	    return isset($_POST[$fieldname]) ? $_POST[$fieldname] : '';
	}

	public static function json($value=null, int $code=200, $die=0)
	{
	    http_response_code($code);
	    echo json_encode($value);
	    if($die) die;
	}

	public static function jdie($value=null, int $code=200)
	{
	    http_response_code($code);
	    echo json_encode($value);
	    die;
	}

	public static function generateStr(int $number = 20)
	{
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $randomString = ''; 
	    for ($i = 0; $i < $number; $i++) {
	        $index = rand(0, strlen($characters) - 1);
	        $randomString .= $characters[$index];
	    }
	    return $randomString;
	}

	public static function generateToken(int $number = 20)
	{
	    $string = generateStr($number);
	    return md5($string . time());
	}

	public static function getAuthorizationHeader()
	{
	    $headers = null;
	    if (isset($_SERVER['Authorization'])) {
	        $headers = trim($_SERVER["Authorization"]);
	    }
	    else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
	        $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
	    } elseif (function_exists('apache_request_headers')) {
	        $requestHeaders = apache_request_headers();
	        $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
	        if (isset($requestHeaders['Authorization'])) {
	            $headers = trim($requestHeaders['Authorization']);
	        }
	    }
	    return $headers;
	}

	public static function getBearerToken()
	{
	    $headers = getAuthorizationHeader();
	    if (!empty($headers)) {
	        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
	            return $matches[1];
	        }
	    }
	    return false;
	}

	public static function redirect($url = '/')
	{
	    header('Location: ' . $url);
	}

	public static function back($url = null)
	{
	    if (getenv("HTTP_REFERER")) {
	        header('Location: '.getenv("HTTP_REFERER"));
	    } elseif (!is_null($url)) {
	        return header('Location: '.$url);
	    }
	    return null;
	}

	public static function special(string $value = null) 
	{
	    if(!is_null($value)) return htmlentities(trim($value));
	    return null;
	}

	public static function de_special(string $value = null)
	{
	    if(!is_null($value)) return html_entity_decode($value);
	    return null;
	}

	public static function hash_make($value = null) 
	{
	    if (!is_null($value)) return password_hash($value, PASSWORD_DEFAULT);
	    return null; 
	}

	public static function hash_check($value = null, $value_hash = null) 
	{
	    if (!is_null($value) && !is_null($value_hash)) return password_verify($value, $value_hash);
	    return null;
	    // hash_check('hello', hash_make('hello'))
	}
}