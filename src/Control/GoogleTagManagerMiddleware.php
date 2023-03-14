<?php

namespace Bigfork\SilverStripeGoogleTagManager\Control;

use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\Control\Middleware\HTTPMiddleware;
use SilverStripe\SiteConfig\SiteConfig;
use SilverStripe\View\Requirements;

class GoogleTagManagerMiddleware implements HTTPMiddleware
{
    const PLACEHOLDER = '<!--%%GTM_PLACEHOLDER%%-->';

    public function process(HTTPRequest $request, callable $delegate)
    {
        /** @var HTTPResponse $response */
        $response = $delegate($request);
        $siteConfig = SiteConfig::current_site_config();

        // There's no point trying to insert tag manager code to redirects
        if (!$response->isRedirect() && $siteConfig->GTMContainerID) {
            $body = $response->getBody();

            // Response body could be quite large, and we don't want the code inserted in the admin area, so we do a
            // "dumb" search for a placeholder added earlier in the request before using regular expressions
            if (strpos($body ?? '', static::PLACEHOLDER) !== false) {
                $script = $siteConfig->renderWith('Bigfork\SilverStripeGoogleTagManager\GoogleTagManagerSnippet')
                    ->setProcessShortcodes(false)
                    ->forTemplate();
                $noScript = $siteConfig->renderWith('Bigfork\SilverStripeGoogleTagManager\GoogleTagManagerNoScript')
                    ->setProcessShortcodes(false)
                    ->forTemplate();

                // Remove the placeholders and inject the snippets
                // ...this isn't perfect, but it's far faster than DOMDocument
                $body = str_replace(static::PLACEHOLDER, '', $body);
                $body = preg_replace(
                    ['/(<head(\s?\>|\s[^>]*\>))/is', '/(<body(\s?\>|\s[^>]*\>))/is'],
                    ["$1\n{$script}", "$1\n{$noScript}"],
                    $body
                );

                $response->setBody($body);
            }
        }

        return $response;
    }
}
