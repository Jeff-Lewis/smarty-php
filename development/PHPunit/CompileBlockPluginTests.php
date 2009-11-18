<?php
/**
* Smarty PHPunit tests compilation of block plugins
* 
* @package PHPunit
* @author Uwe Tews 
*/


/**
* class for block plugin tests
*/
class CompileBlockPluginTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = SmartyTests::$smarty;
        SmartyTests::init();
        $this->smarty->force_compile = true;
    } 

    public static function isRunnable()
    {
        return true;
    } 

    /**
    * test block plugin tag
    */
    public function testBlockPluginNoAssign()
    {
        $tpl = $this->smarty->createTemplate("string:{textformat}hello world{/textformat}");
        $this->assertEquals("hello world", $this->smarty->fetch($tpl));
    } 
    /**
    * test block plugin tag with assign attribute
    */
    public function testBlockPluginAssign()
    {
        $tpl = $this->smarty->createTemplate("string:{textformat assign=foo}hello world{/textformat}{\$foo}");
        $this->assertEquals("hello world", $this->smarty->fetch($tpl));
    } 
    /**
    * test block plugin tag in template file
    */
    public function testBlockPluginFromTemplateFile()
    {
        $tpl = $this->smarty->createTemplate('blockplugintest.tpl');
        $this->assertEquals("abc", $this->smarty->fetch($tpl));
    } 
    /**
    * test block plugin tag in compiled template file
    */
    public function testBlockPluginFromCompiledTemplateFile()
    {
        $this->smarty->force_compile = false;
        $tpl = $this->smarty->createTemplate('blockplugintest.tpl');
        $this->assertEquals("abc", $this->smarty->fetch($tpl));
    } 
    /**
    * test block plugin tag in template file
    */
    public function testBlockPluginFromTemplateFileCache()
    {
        $this->smarty->force_compile = false;
        $this->smarty->caching = 1;
        $this->smarty->cache_lifetime = 10;
        $tpl = $this->smarty->createTemplate('blockplugintest.tpl');
        $this->assertEquals("abc", $this->smarty->fetch($tpl));
    } 
    /**
    * test block plugin function definition in script
    */
    public function testBlockPluginRegisteredFunction()
    {
        $this->smarty->register_block('blockplugintest', 'myblockplugintest');
        $tpl = $this->smarty->createTemplate('string:{blockplugintest}hello world{/blockplugintest}');
        $this->assertEquals('block test', $this->smarty->fetch($tpl));
    } 
    /**
    * test block plugin repeat function
    */
    public function testBlockPluginRepeat()
    {
        $this->smarty->plugins_dir[] = dirname(__FILE__)."/PHPunitplugins/";
        $this->assertEquals('12345', $this->smarty->fetch('string:{testblock}{/testblock}'));
    } 
} 
function myblockplugintest($params, $content, &$smarty_tpl, &$repeat)
{
    if (!$repeat) {
        $output = str_replace('hello world', 'block test', $content);
        return $output;
    } 
} 

?>
