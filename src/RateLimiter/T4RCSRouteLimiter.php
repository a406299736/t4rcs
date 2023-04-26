<?php
namespace A406299736\T4rcs\RateLimiter;

use A406299736\T4rcs\Http;

/**
 * User: Jin's
 * Date: 2023/4/11 16:22
 * Mail: jin.aiyo@hotmail.com
 * Desc: 风控路由限流
 */
trait T4RCSRouteLimiter
{
    private $apiPath = '/inner/api/rate/route/passed';

    private $defaultRouteChart = '*';

    private $res;

    // 请求接口域名
    protected abstract function getDomain() :string;

    // 应用名称
    protected abstract function appName() :string;

    // 限流路由
    protected abstract function routePath() :string;

    // 请求IP
    protected abstract function ipStr() :string;

    // 用户ID
    protected abstract function userId() :int;

    // 设备ID
    protected abstract function deviceId() :string;

    // 是否允许
    protected function allowed() :bool
    {
        $url = $this->getDomain() . $this->apiPath;
        if (!$url) $this->thr('getRequestUrl()返回空字符');

        $this->res = Http::postBody($url, $this->params(), 0, $this->res);
        if (!$this->res) return true;

        return $this->res['passed'] ?? true;
    }

    protected function httpRes()
    {
        return $this->res;
    }

    private function params() :array
    {
        $appName = $this->appName();
        if (!$appName) $this->thr('appName()返回空字符');

        $route = $this->routePath();
        if (!$route) $route = $this->defaultRouteChart;

        return ['app_name' => $appName, 'route' => $route, 'uid' => $this->userId(), 'ip' => $this->ipStr(), 'device_id' => $this->deviceId()];
    }

    private function thr($msg)
    {
        throw new \Exception($msg);
    }
}