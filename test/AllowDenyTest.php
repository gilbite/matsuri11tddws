<?php
namespace PHPMatsuri\AccessControl\Tests;

use PHPMatsuri\AccessControl\AccessControl;
use PHPMatsuri\AccessControl\AccessState;

class AllowDenyTest extends \PHPUnit_Framework_TestCase
{
    public $allowDeny = null;

    public function setUp()
    {
        $this->allowDeny = AccessControl::allowDeny();
    }

    public function test許可ルールにマッチせず拒否ルールにもマッチしない対象を拒否する()
    {
        $this->allowDeny->allow('/^192\.168\.0\.1$/');
        $this->allowDeny->deny('/^192\.168\.0\.2$/');

        $this->assertTrue($this->allowDeny->evaluate('192.168.0.4') === AccessState::DENY);
    }

    public function test許可ルールにマッチし拒否ルールにマッチしない対象を許可する()
    {
        $this->allowDeny->allow('/^192\.168\.0\.1$/');
        $this->allowDeny->deny('/^192\.168\.0\.2$/');

        $this->assertTrue($this->allowDeny->evaluate('192.168.0.1') === AccessState::ALLOW);
    }
    
    public function test許可ルールにマッチし拒否ルールにマッチする対象を拒否する()
    {
        $this->allowDeny->allow('/^192\.168\.0\.1\d$/');
        $this->allowDeny->deny('/^192\.168\.0\.15$/');

        $this->assertTrue($this->allowDeny->evaluate('192.168.0.15') === AccessState::DENY);
    }

    public function test許可ルールにマッチせず拒否ルールにマッチする対象を拒否する()
    {
        $this->allowDeny->allow('/^192\.168\.0\.1$/');
        $this->allowDeny->deny('/^192\.168\.0\.2$/');

        $this->assertTrue($this->allowDeny->evaluate('192.168.0.2') === AccessState::DENY);
    }

}

