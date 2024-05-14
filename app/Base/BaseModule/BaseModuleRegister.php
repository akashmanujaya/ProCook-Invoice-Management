<?php
/**
 *
 *
 * @package
 * @subpackage
 * @author     Dev2
 * Date: 1/17/2020
 * Time: 5:29 PM
 */

namespace App\Base\BaseModule;


interface BaseModuleRegister
{
    public function getVersion();

    public function getVersionPath();

    public function getModuleName();

    public function getDescription();

    public function getModuleSlug();

    public function getClassPath();

    public function getFilePath();

    public function getViewsPath();
}
