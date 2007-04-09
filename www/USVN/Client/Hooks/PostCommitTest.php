<?php
/**
 * PostCommit  hook use by subversion hook
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

// Call USVN_Client_Hooks_PostCommitTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "USVN_Client_Hooks_PostCommitTest::main");
}

require_once "PHPUnit/Framework/TestCase.php";
require_once "PHPUnit/Framework/TestSuite.php";

require_once 'www/USVN/autoload.php';

class PostCommit_For_Test extends USVN_Client_Hooks_PostCommit
{
	protected function svnLookRevision($command, $repository, $transaction)
	{
		switch($command) {
			case "author":
				return 'toto';
			break;

			case "log":
				return 'message de commit';
			break;

			case "changed":
				return "U tutu\nU tata\n";
			break;
		}
	}
}

/**
 * Test class for USVN_Client_Hooks_PostCommit.
 * Generated by PHPUnit_Util_Skeleton on 2007-03-10 at 18:45:32.
 */
class USVN_Client_Hooks_PostCommitTest extends USVN_Client_Hooks_HookTest {
    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main() {
        require_once "PHPUnit/TextUI/TestRunner.php";

        $suite  = new PHPUnit_Framework_TestSuite("USVN_Client_Hooks_PostCommitTest");
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    public function setUp()
    {
		parent::setUp();
        $this->hook = new PostCommit_For_Test('tests/tmp/testrepository', 1);
		$this->setHttp();
    }

    public function test_postCommit()
    {
		$this->setServerResponseTo(0);
		$this->hook->send();
        $request  = $this->hook->getLastRequest();
        $this->assertEquals('usvn.client.hooks.postCommit', $request->getMethod());
        $this->assertSame(array('love', '007' , 1, 'toto', 'message de commit', array(array('U', 'tutu'), array('U', 'tata'))), $request->getParams());
    }
}

// Call USVN_Client_Hooks_PostCommitTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "USVN_Client_Hooks_PostCommitTest::main") {
    USVN_Client_Hooks_PostCommitTest::main();
}
?>
