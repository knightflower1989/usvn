<?php
/**
 * Post unlock hook use by subversion hook
 *
 * @author Team USVN <contact@usvn.info>
 * @link http://www.usvn.info
 * @license http://www.cecill.info/licences/Licence_CeCILL_V2-en.txt CeCILL V2
 * @copyright Copyright 2007, Team USVN
 * @since 0.5
 * @package client
 * @subpackage hook
 *
 * This software has been written at EPITECH <http://www.epitech.net>
 * EPITECH, European Institute of Technology, Paris - FRANCE -
 * This project has been realised as part of
 * end of studies project.
 *
 * $Id$
 */

// Call USVN_Client_Hooks_PostUnlockTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "USVN_Client_Hooks_PostUnlockTest::main");
}

require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

require_once 'www/USVN/autoload.php';
/**
 * Test class for USVN_Client_Hooks_PostUnlock.
 * Generated by PHPUnit_Util_Skeleton on 2007-03-10 at 18:54:08.
 */
class USVN_Client_Hooks_PostUnlockTest extends USVN_Client_Hooks_HookTest {
    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main() {
        require_once "PHPUnit/TextUI/TestRunner.php";

        $suite  = new PHPUnit_Framework_TestSuite("USVN_Client_Hooks_PostUnlockTest");
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

	public function setUp()
    {
        parent::setUp();
        $this->hook = new USVN_Client_Hooks_PostUnlock('tests/tmp/testrepository', 'titi', 'test');
		$this->setHttp();
    }

    public function test_postUnlock()
    {
		$this->setServerResponseTo(0);
		$this->hook->send();
        $request  = $this->hook->getLastRequest();
        $this->assertEquals('usvn.client.hooks.postUnlock', $request->getMethod());
        $this->assertSame(array('love', '007', 'titi', 'test'), $request->getParams());
    }
}

// Call USVN_Client_Hooks_PostUnlockTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "USVN_Client_Hooks_PostUnlockTest::main") {
    USVN_Client_Hooks_PostUnlockTest::main();
}
?>
