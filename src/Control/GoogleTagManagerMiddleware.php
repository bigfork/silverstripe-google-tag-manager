<?php

namespace Bigfork\SilverStripeGoogleTagManager\Control;

use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\HTTPResponse;
use SilverStripe\Control\Middleware\HTTPMiddleware;
use SilverStripe\SiteConfig\SiteConfig;

class GoogleTagManagerMiddleware implements HTTPMiddleware
{
    const NOSCRIPT_PLACEHOLDER = '%%NOSCRIPT_PLACEHOLDER%%';

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
            if (strpos($body, static::NOSCRIPT_PLACEHOLDER) !== false) {
                $script = $siteConfig->renderWith('Bigfork\SilverStripeGoogleTagManager\GoogleTagManagerNoScript')
                    ->setProcessShortcodes(false)
                    ->forTemplate();

                // Remove the placeholder and inject the noscript code
                // ...this isn't perfect, but it's far faster than DOMDocument
                $body = str_replace(static::NOSCRIPT_PLACEHOLDER, '', $body);
                $body = preg_replace('/(<body.*?>)/is', "$1\n{$script}", $body);

                $response->setBody($body);
            }
        }

        return $response;
    }
}
