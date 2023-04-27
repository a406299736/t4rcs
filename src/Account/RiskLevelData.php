<?php
/**
 * User: Jin's
 * Date: 2023/4/27 14:47
 * Mail: jin.aiyo@hotmail.com
 * Desc: TODO
 */

namespace A406299736\T4rcs\Account;

class RiskLevelData
{
    public $uidRLevel = 1;
    public $ipRLevel = 1;
    public $deviceIdRLevel = 1;
    public $rLevel;
    public $code = 0;
    public $msg='success';

    // 综合风险等级
    public function isRisk() :bool
    {
        return $this->rLevel > 3;
    }
}