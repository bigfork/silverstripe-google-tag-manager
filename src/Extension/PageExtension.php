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
            $snippet = $siteConfig->renderWith('Bigfork\SilverStripeGoogleTagManager\GoogleTagManagerSnippet');
            Requirements::insertHeadTags($snippet->forTemplate());
            Requirements::insertHeadTags(GoogleTagManagerMiddleware::NOSCRIPT_PLACEHOLDER);
        }
    }
}
