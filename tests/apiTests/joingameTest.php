<?php
use PHPUnit\Framework\TestCase;

/**
 * Class joingameTest
 * @author Jakub Kwak
 */

final class joingameTest extends TestCase
{
    /**
     * Tests whether running the script with no form data gives an empty form error
     */
    public function testNoFormError(){
        require("..\..\src\www\campustreks\api\join_game.php");
        $this->expectOutputString("form-error");
    }

    /**
     *  Test whether findGame returns true with a correct game pin
     */
    public function testGameExists(){
        $this->assertTrue(findGame("0000"));
    }

    /**
     * Tests whether findGame returns false with an incorrect game pin
     */
    public function testGameDoesntExist(){
        $this->assertFalse(findGame("9999"));
    }
}