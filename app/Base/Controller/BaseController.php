<?php
/**
 *
 *
 * @package
 * @subpackage
 * @author     Dev2
 * Date: 1/20/2020
 * Time: 5:27 PM
 */

namespace App\Base\Controller;


use App\Base\BaseModule\BaseModuleRegister;
use App\Http\Controllers\Controller;

class BaseController extends Controller
{
    var $module = null;
    var $modulePath = "";
    var $data = [];

    /**
     * BaseController constructor.
     * @param null $module
     */
    public function __construct($module = null)
    {
        if($module instanceof BaseModuleRegister){
            $this->module = $module;
            $this->data['moduleVersion'] = $this->module->getVersion();
            $this->data['slug'] = $this->module->getModuleSlug();
            $this->data['versionPath'] = $this->module->getVersionPath();
            $this->data['hostname'] = '';
        }
    }

}
