<?php
/**
* Smarty PHPunit tests of modifier
* 
* @package PHPunit
* @author Uwe Tews 
*/

/**
* class for modifier tests
*/
class ModifierTests extends PHPUnit_Framework_TestCase {
    public function setUp()
    {
        $this->smarty = SmartyTests::$smarty;
        SmartyTests::init();
        $this->smarty->security = false;
    } 

    public static function isRunnable()
    {
        return true;
    } 

    /**
    * test PHP function as modifier
    */
    public function testPHPFunctionModifier()
    {
        $this->smarty->security_policy->modifiers = array('strlen');
        $tpl = $this->smarty->createTemplate('string:{"hello world"|strlen}');
        $this->assertEquals("11", $this->smarty->fetch($tpl));
    } 
    public function testPHPFunctionModifier2()
    {
        $this->smarty->security_policy->modifiers = array('strlen');
        $tpl = $this->smarty->createTemplate('string:{assign var=foo value="hello world"}{$foo|strlen}');
        $this->assertEquals("11", $this->smarty->fetch($tpl));
    } 
    /**
    * test plugin as modifier
    */
    public function testPluginModifier()
    {
        $tpl = $this->smarty->createTemplate('string:{"hello world"|truncate:6}');
        $this->assertEquals("hel...", $this->smarty->fetch($tpl));
    } 
    /**
    * test plugin as modifier with variable
    */
    public function testPluginModifierVar()
    {
        $tpl = $this->smarty->createTemplate('string:{"hello world"|truncate:$foo}');
        $tpl->assign('foo', 6);
        $this->assertEquals("hel...", $this->smarty->fetch($tpl));
    } 
    public function testPluginModifierVar2()
    {
        $tpl = $this->smarty->createTemplate('string:{"hello world"|truncate:$foo:"   "}');
        $tpl->assign('foo', 6);
        $this->assertEquals("hel   ", $this->smarty->fetch($tpl));
    } 
    public function testPluginModifierVar3()
    {
        $tpl = $this->smarty->createTemplate('string:{"hello world"|truncate:$foo:$bar}');
        $tpl->assign('foo', 6);
        $tpl->assign('bar', '   ');
        $this->assertEquals("hel   ", $this->smarty->fetch($tpl));
    } 
    /**
    * test modifier chaining
    */
    public function testModifierChaining()
    {
        $this->smarty->security_policy->modifiers = array('strlen');
        $tpl = $this->smarty->createTemplate('string:{"hello world"|truncate:6|strlen}');
        $this->assertEquals("6", $this->smarty->fetch($tpl));
    } 
   /**
    * test modifier in {if}
    */
    public function testModifierInsideIf()
    {
        $this->smarty->security_policy->modifiers = array('strlen');
        $tpl = $this->smarty->createTemplate('string:{if "hello world"|truncate:6|strlen == 6}okay{/if}');
        $this->assertEquals("okay", $this->smarty->fetch($tpl));
    } 
   /**
    * test modifier in expressions
    */
    public function testModifierInsideExpression()
    {
        $this->smarty->security_policy->modifiers = array('strlen');
        $tpl = $this->smarty->createTemplate('string:{"hello world"|truncate:6|strlen + "hello world"|truncate:8|strlen}');
        $this->assertEquals("14", $this->smarty->fetch($tpl));
    } 
    /**
    * test modifier at plugin result
    */
    public function testModifierAtPluginResult()
    {
        $tpl = $this->smarty->createTemplate('string:{counter|truncate:5 start=100000}');
        $this->assertEquals("10...", $this->smarty->fetch($tpl));
    } 
    /**
    * test unqouted string as modifier parameter
    */
    public function testModifierUnqoutedString()
    {
        $tpl = $this->smarty->createTemplate('string:{"hello world"|replace:hello:xxxxx}');
        $this->assertEquals("xxxxx world", $this->smarty->fetch($tpl));
    } 
    /**
    * test registered modifier function
    */
    public function testModifierRegisteredFunction()
    {
        $this->smarty->register->modifier('testmodifier','testmodifier');
        $tpl = $this->smarty->createTemplate('string:{$foo|testmodifier}');
        $tpl->assign('foo',2);
        $this->assertEquals("mymodifier function 2", $this->smarty->fetch($tpl));
    } 
    /**
    * test registered modifier static class
    */
    public function testModifierRegisteredStaticClass()
    {
        $this->smarty->register->modifier('testmodifier',array('testmodifierclass','staticcall'));
        $tpl = $this->smarty->createTemplate('string:{$foo|testmodifier}');
        $tpl->assign('foo',1);
        $this->assertEquals("mymodifier static 1", $this->smarty->fetch($tpl));
    } 
    /**
    * test registered modifier methode call
    */
    public function testModifierRegisteredMethodCall()
    {
        $obj= new testmodifierclass();
        $this->smarty->register->modifier('testmodifier',array($obj,'method'));
        $tpl = $this->smarty->createTemplate('string:{$foo|testmodifier}');
        $tpl->assign('foo',3);
        $this->assertEquals("mymodifier method 3", $this->smarty->fetch($tpl));
    } 
    /**
    * test unknown modifier error
    */
    public function testUnknownModifier()
    {
        try {
            $this->smarty->fetch('string:{"hello world"|unknown}');
        } 
        catch (Exception $e) {
            $this->assertContains('unknown modifier "unknown"', $e->getMessage());
            return;
        } 
        $this->fail('Exception for unknown modifier has not been raised.');
    } 
} 
function testmodifier($value)
{
    return "mymodifier function $value";
} 
class testmodifierclass {
    static function staticcall($value)
    {
        return "mymodifier static $value";
    } 
    public function method($value)
    {
        return "mymodifier method $value";
    } 
} 

?>