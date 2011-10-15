<?php

namespace PHPMatsuri\AccessControl;

class AccessControl
{
    protected $isOrderDenyAllow = false;
    protected $allowList = array();
    protected $denyList  = array();

    public static function denyAllow()
    {
        return new self(true);
    }

    public static function allowDeny()
    {
        return new self(false);
    }

    public function __construct($isOrderDenyAllow)
    {
        $this->isOrderDenyAllow = $isOrderDenyAllow;
    }


    public function deny($regex)
    {
        $this->denyList[] = $regex;
    }

    public function allow($regex)
    {
        $this->allowList[] = $regex;
    }

    public function evaluate($ip)
    {
        return $this->isOrderDenyAllow ? $this->evaluateDenyAllow($ip) : $this->evaluateAllowDeny($ip);
    }

    public function evaluateDenyAllow($ip)
    {
        foreach($this->allowList as $allowPattern){
            if (preg_match($allowPattern, $ip)) {
                return AccessState::ALLOW;
            }
        }

        foreach($this->denyList as $denyPattern){
            if (preg_match($denyPattern, $ip)) {
                return AccessState::DENY;
            }
        }

        return AccessState::ALLOW;
    }

    public function evaluateAllowDeny($ip)
    {
        foreach($this->denyList as $denyPattern){
            if (preg_match($denyPattern, $ip)) {
                return AccessState::DENY;
            }
        }

        foreach($this->allowList as $allowPattern){
            if (preg_match($allowPattern, $ip)) {
                return AccessState::ALLOW;
            }
        }

        return AccessState::DENY;
    }
}


