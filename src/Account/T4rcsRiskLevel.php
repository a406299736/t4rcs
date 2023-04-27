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
    protected $defaultMode = 'offline';

    protected $onlineMode = 'online';

    private $riskLevelPath = '/inner/api/account/risk/level';

    private $httpRiskRes = '';

    // 获取账户风险等级对象
    public function risk($params = [])
    {
        if (!$params) $this->thr('参数不能为空');
        if (!isset($params['app_name'])) $this->thr('appName参数不能为空');
        if (!isset($params['mode'])) $params['mode'] = $this->defaultMode;
        if ($params['mode'] == $this->onlineMode) {
            if (!isset($params['since_timestamp'])) $this->thr('使用online模型时， since_timestamp时间戳参数不能为空');
            if (!is_numeric($params['since_timestamp']) || strlen($params['since_timestamp']) != 10) {
                $this->thr('since_timestamp参数是历史时间节点的时间戳格式');
            }
        }

        $domain = $this->domain();
        if (!$domain) $this->thr('domain抽象方法返回为空');

        if (!isset($params['uid']) && !isset($params['ip']) && !isset($params['device_id'])) {
            $this->thr('请调用相关with方法指定uid,ip,device_id参数,不能同时缺省');
        }

        $res = Http::postBody($domain.$this->riskLevelPath, $params, 0, $this->httpRiskRes);
        $obj = new RiskLevelData();
        if ($res) {
            $obj->uidRLevel = $res['risk_level']['uid_risk_level'] ?? 1;
            $obj->ipRLevel = $res['risk_level']['ip_risk_level'] ?? 1;
            $obj->deviceIdRLevel = $res['risk_level']['device_risk_level'] ?? 1;
            $obj->rLevel = $res['risk_level']['risk_level'] ?? 1;
            return $obj;
        }
        $obj->code = $res['code'] ?? 1;
        $obj->msg = $res['msg'] ?? $this->httpRiskRes;

        return $obj;
    }

    // 接口请求返回的原始数据
    public function httpRiskRes()
    {
        return $this->httpRiskRes;
    }

    // 风控系统域名
    protected abstract function domain();

    private function thr($msg)
    {
        throw new \Exception($msg);
    }
}