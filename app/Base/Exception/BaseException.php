<?php
/**
 * BaseException
 *
 * @package    Exceptions
 * @author     Dev4
 * @Date       3/8/2019
 * @Time       11:58 AM
 */

namespace App\Base\Exception;

use Illuminate\Http\Response as HTTPResponse;
use Throwable;

class BaseException extends \Exception
{
    /*
    |--------------------------------------------------------------------------
    | HTTP Status Code usage
    |--------------------------------------------------------------------------
    | Class: Illuminate\Http\Response as HTTPResponse
    |
    | Success, empty, created. updated
    | HTTPResponse::HTTP_OK - 200
    |
    | Access denied, unauthorized
    | HTTP_UNAUTHORIZED  - 401
    |
    | Object not found
    | HTTP_NOT_FOUND - 404
    |
    | Method (route) not found
    | HTTP_METHOD_NOT_ALLOWED - 405
    |
    | Validation fail
    | HTTP_NOT_ACCEPTABLE - 406
    |
    | Exceptions
    | HTTP_EXPECTATION_FAILED = 417
    |
    */

    protected $http_status_code = HTTPResponse::HTTP_EXPECTATION_FAILED;

    public function __construct($http_status_code = '', $data = [])
    {
        parent::__construct();
        $this->http_status_code = $http_status_code;
        $this->data = $data;
    }

    public function getHttpCode()
    {
        return (int) $this->http_status_code;
    }

    public function getData()
    {
        return (object) $this->data;
    }
}
