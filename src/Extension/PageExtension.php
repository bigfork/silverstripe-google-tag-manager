<?php

namespace Bigfork\SilverStripeGoogleTagManager\Extension;

use ContentController;
use Extension;
use Requirements;
use SiteConfig;

class PageExtension extends Extension
{
	/**
	 * @param ContentController $controller
	 */
	public function contentcontrollerInit(ContentController $controller)
	{
		$siteConfig = SiteConfig::current_site_config();
		if ($siteConfig->GTMContainerID) {
			Requirements::insertHeadTags($siteConfig->renderWith('GoogleTagManagerSnippet'));
		}
	}
}
