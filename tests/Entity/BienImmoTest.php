<?php


namespace App\Tests\Entity;


use App\Entity\BienImmo;
use PHPUnit\Framework\TestCase;

class BienImmoTest extends TestCase
{
    public function testDfault()
    {
        $bienImmo = new BienImmo();
        $this->assertSame('1',$bienImmo->getFirstDay());
    }
}