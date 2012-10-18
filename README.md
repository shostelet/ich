ich
===

ICanHaz templates for Symfony 1.4


Installation
------------

You should first grab ICanHaz.js here: http://icanhazjs.com/

Install this ichPlugin in your plugins directory and enable the plugin in yout ProjectConfiguration class
 
Use
---

Create a ICH template, let's say in ``apps/frontend/modules/hello/templates/ich_hello_world.html``. It could also be a ``.php``. Make sure the content is wrapped in a tag (div or p). In one of your template, say ``helloSuccess.php``, add the line
    
    <?php ich::addTemplate('frontend/hello/hello_world') ?>
    
In your layout, render your templates in ``<head>``
    
    <?php echo ich::renderTemplates()?>
    
You're good, you should see your template with its id being the name of the template (``ich_hello_world``) so you can call it in your JS files with ich.ich_hello_world()
