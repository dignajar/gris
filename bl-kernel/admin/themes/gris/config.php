<?php defined('BLUDIT') or die('Bludit CMS.');

// Disable all plugins
deactivateAllPlugin();

// Enable plugins
activatePlugin('pluginTwitterCards');
activatePlugin('pluginCanonical');
activatePlugin('pluginDisqus');
activatePlugin('pluginOpenGraph');
activatePlugin('pluginRobots');
activatePlugin('pluginRSS');
activatePlugin('pluginSitemap');

// Configure site
$site->set(array(
	'itemsPerPage'=>6
));

// Configure Disqus
$plugins['all']['pluginDisqus']->setField('shortname','<DISQUS_SHORTNAME>');
