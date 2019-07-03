<?php

namespace Bigfork\SilverStripeGoogleTagManager\Extension;

use Bigfork\SilverStripeGoogleTagManager\Control\GoogleTagManagerMiddleware;
use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\Core\Extension;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\View\Requirements;

class PageExtension extends Extension
{
    /**
     * @param ContentController $controller
     */
    public function contentcontrollerInit(ContentController $controller)
    {
        $siteConfig = SiteConfig::current_site_config();
        if ($siteConfig->GTMContainerID) {
            Requirements::insertHeadTags(GoogleTagManagerMiddleware::PLACEHOLDER);
        }
    }
}
