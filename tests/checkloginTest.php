<?php
use PHPUnit\Framework\TestCase;

/**
 * Class checkloginTest
 * @author Jakub Kwak
 */

final class checkloginTest extends TestCase
{
    protected function setUp(): void {
        require("..\src\www\campustreks\checklogin.php");
    }

    /**
     * Test whether checkLogin gives false with no session data
     */
    public function testNoLogin(){
        $this->assertFalse(CheckLogin());
    }
    //Cannot test positive login as PHPunit does not support session variable creation
}