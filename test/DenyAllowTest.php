<?php
namespace PHPMatsuri\AccessControl\Tests;

use PHPMatsuri\AccessControl\AccessControl;
use PHPMatsuri\AccessControl\AccessState;

class DenyAllowTest extends \PHPUnit_Framework_TestCase
{
    public $denyAllow = null;

    public function setUp()
    {
        $this->denyAllow = AccessControl::denyAllow();
    }

    public function test拒否ルールにマッチせず許可ルールにもマッチしない対象を許可する()
    {
        $this->denyAllow->deny('/^192\.168\.0\.1$/');
        $this->denyAllow->allow('/^192\.168\.0\.2$/');

        $this->assertTrue($this->denyAllow->evaluate('192.168.0.4') === AccessState::ALLOW);
    }
    
    public function test拒否ルールにマッチし許可ルールにマッチしない対象を拒否する()
    {
        $this->denyAllow->deny('/^192\.168\.0\.1$/');
        $this->denyAllow->allow('/^192\.168\.0\.2$/');

        $this->assertTrue($this->denyAllow->evaluate('192.168.0.1') === AccessState::DENY);
    }

    public function test拒否ルールにマッチし許可ルールにマッチする対象を許可する()
    {
        $this->denyAllow->deny('/^192\.168\.0\.1\d$/');
        $this->denyAllow->allow('/^192\.168\.0\.15$/');

        $this->assertTrue($this->denyAllow->evaluate('192.168.0.15') === AccessState::ALLOW);
    }

    public function test拒否ルールにマッチせず許可ルールにマッチする対象を許可する()
    {
        $this->denyAllow->deny('/^192\.168\.0\.1/');
        $this->denyAllow->allow('/^192\.168\.0\.2$/');

        $this->assertTrue($this->denyAllow->evaluate('192.168.0.2') === AccessState::ALLOW);
    }
}

