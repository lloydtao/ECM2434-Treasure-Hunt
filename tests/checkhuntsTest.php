<?php
use PHPUnit\Framework\TestCase;

/**
 * Class checkhuntsTest
 * @author Jakub Kwak
 */

final class checkhuntsTest extends TestCase
{
    /**
     * Tests whether checkHunts finds and returns a valid hunt for the username
     */
    public function testCheckHunts(){
        require("..\src\www\campustreks\checkhunts.php");
        $this->assertEquals("0000",checkHunts("test"));
        $this->assertEquals("1234",checkHunts("master"));
    }

    /**
     * Tests whether checkHunts returns null when no hunt was found
     */
    public function testNoHunts() {
        $this->assertEquals(null,checkHunts("test-2"));
    }
}