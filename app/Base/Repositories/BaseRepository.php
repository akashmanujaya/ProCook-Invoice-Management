<?php
/**
 * Base Repository
 *
 * @package    WineWOrld
 * @subpackage
 * @author     Dev2
 * Date: 8/13/2019
 * Time: 12:30 PM
 */

namespace App\Base\Repositories;

use App\Exceptions\BaseException;
use App\Log;
use Illuminate\Http\Request;
use Magento\Client\Xmlrpc\MagentoXmlrpcClient;

class BaseRepository
{
    private $client = null;

    private $sessionId = null;
    private $method = null;
    private $params = null;
    private $response = null;

    public function __construct()
    {
        $this->client = self::initClient();
    }

    public function call(string $methodName = "", array $params = []){

        $this->method = $methodName;
        $this->params = $params;

        $this->response = $this->client->call(
            $this->method,
            $this->params
        );

        // saving API logs
        $this->saveAPILog();

        return $this->response;
    }

    private function saveAPILog(){
        $log = new Log();
        $log->method_name = $this->method;
        $log->request_data = json_encode($this->params);
        $log->response_data = json_encode($this->response);
        $log->save();
    }

    private static function initClient()
    {
        try {

            $client = MagentoXmlrpcClient::factory(array(
                'base_url' => 'http://ww-sandbox.antyrasolutions.com',
                'api_user' => 'mobile-test',
                'api_key' => 'layoutindexapi123',
            ));

            return $client;

        } catch (\Exception $e) {
            throw new BaseException(
                1005
            );
        }
    }
}
