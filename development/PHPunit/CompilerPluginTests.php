<?php
/**
* Smarty PHPunit tests compiler plugin
* 
* @package PHPunit
* @author Uwe Tews 
*/

/**
* class for compiler plugin tests
*/
class CompilerPluginTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = SmartyTests::$smarty;
        SmartyTests::init();
    } 

    public static function isRunnable()
    {
        return true;
    } 


    /**
    * test compiler plugin
    */
    public function testCompilerPlugin()
    {
        $this->smarty->plugins_dir[] = dirname(__FILE__)."/PHPunitplugins/";
		$this->assertEquals('test output', $this->smarty->fetch('string:{test data="test output"}{/test}'));
    } 

} 
?>
