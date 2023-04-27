<?php
/**
 * User: Jin's
 * Date: 2023/4/27 14:12
 * Mail: jin.aiyo@hotmail.com
 * Desc: TODO
 */

namespace A406299736\T4rcs\Account;

use A406299736\T4rcs\Http;

trait T4rcsRiskLevel
{
    private $reqParams = [];

    protected $defaultMode = 'offline';

    protected $onlineMode = 'online';

    private $apiPath = '/inner/api/account/risk/level';

    private $httpRiskRes = '';

    // 获取账户风险等级对象
    public function risk($appName)
    {
        if (!$appName) $this->thr('appName参数不能为空');
        $this->reqParams['app_name'] = $appName;

        if (!isset($this->reqParams['mode'])) $this->reqParams['mode'] = $this->defaultMode;

        $domain = $this->domain();
        if (!$domain) $this->thr('domain抽象方法返回为空');

        if (!isset($this->reqParams['uid']) && !isset($this->reqParams['ip']) && !isset($this->reqParams['device_id'])) {
            $this->thr('请调用相关with方法指定uid,ip,device_id参数,不能同时缺省');
        }

        $res = Http::postBody($domain.$this->apiPath, $this->reqParams, 0, $this->httpRiskRes);
        $this->reqParams = []; // 释放请求参数为初始值
        if ($res) {
            $obj = new RiskLevelData();
            $obj->uidRLevel = $res['risk_level']['uid_risk_level'] ?? 1;
            $obj->ipRLevel = $res['risk_level']['ip_risk_level'] ?? 1;
            $obj->deviceIdRLevel = $res['risk_level']['device_risk_level'] ?? 1;
            $obj->rLevel = $res['risk_level']['risk_level'] ?? 1;
            return $obj;
        }

        return null;
    }

    // 接口请求返回的原始数据
    public function httpRiskRes()
    {
        return $this->httpRiskRes;
    }

    // 用户UID：计算用户风险等级
    public function withUid($uid)
    {
        $this->reqParams['uid'] = $uid;
        return $this;
    }

    // 网络IP：计算IP风险等级
    public function withIp($ip)
    {
        $this->reqParams['ip'] = $ip;
        return $this;
    }

    // 设备ID：计算设备风险等级
    public function withDeviceId($deviceId)
    {
        $this->reqParams['device_id'] = $deviceId;
        return $this;
    }

    // 使用实时计算模型
    public function withOnlineMode($since)
    {
        if (!$since) $this->thr('使用online模型时， since时间戳参数不能为空');
        if (!is_numeric($since) || strlen($since) != 10) $this->thr('since参数是历史时间节点的时间戳格式');
        $this->reqParams['mode'] = $this->onlineMode;
        $this->reqParams['since_timestamp'] = $since;
        return $this;
    }

    // 风控系统域名
    protected abstract function domain();

    private function thr($msg)
    {
        throw new \Exception($msg);
    }
}