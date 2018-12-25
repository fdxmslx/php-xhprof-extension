--TEST--
Tideways: Spans Twig+Smarty Support
--FILE--
<?php

include_once dirname(__FILE__).'/common.php';

class Smarty
{
    public function fetch($template)
    {
    }
}

class Smarty_Internal_TemplateBase
{
    public function fetch($template)
    {
    }
}

class Smarty3 extends Smarty_Internal_TemplateBase
{
}

class Smarty_Internal_Template extends Smarty_Internal_TemplateBase
{
    protected $template_resource;

    public function __construct($template_resource = 'foo.tpl')
    {
        $this->template_resource = $template_resource;
    }
}

class Twig_Template
{
    public function getTemplateName()
    {
        return 'test.twig';
    }

    public function display($variables)
    {
    }
}

tideways_enable();

$template = new Twig_Template();
$template->display(array('foo' => 'bar'));

$smarty3 = new Smarty_Internal_Template();
$smarty3->fetch("bar.tpl");
$smarty3->fetch(NULL);

$smarty3 = new Smarty3();
$smarty3->fetch(new Smarty_Internal_Template('baz.tpl'));

$smarty2 = new Smarty();
$smarty2->fetch("foo.tpl");
$smarty2->fetch(NULL);

print_spans(tideways_get_spans());
tideways_disable()
?>
--EXPECT--
app: 1 timers - 
view: 1 timers - title=test.twig
view: 1 timers - title=bar.tpl
view: 2 timers - title=foo.tpl
view: 1 timers - title=baz.tpl
