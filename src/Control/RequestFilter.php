<?php

namespace Bigfork\SilverStripeGoogleTagManager\Control;

use DataModel;
use RequestFilter as SilverStripeRequestFilter;
use Session;
use SiteConfig;
use SS_HTTPRequest;
use SS_HTTPResponse;

class RequestFilter implements SilverStripeRequestFilter
{
    const PLACEHOLDER = '%%GTM_PLACEHOLDER%%';

    /**
     * {@inheritdoc}
     */
    public function preRequest(SS_HTTPRequest $request, Session $session, DataModel $model)
    {
        return true;
    }

    /**
     * Inserts the Google Tag Manager <noscript> tag after the opening <body> tag
     *
     * {@inheritdoc}
     */
    public function postRequest(SS_HTTPRequest $request, SS_HTTPResponse $response, DataModel $model)
    {
        $siteConfig = SiteConfig::current_site_config();
        $redirectCodes = array(301, 302, 303, 304, 305, 307);

        // There's no point trying to insert tag manager code to redirects
        if (!in_array($response->getStatusCode(), $redirectCodes) && $siteConfig->GTMContainerID) {
            $body = $response->getBody();

            // Response body could be quite large, and we don't want the code inserted in the admin area, so we do a
            // "dumb" search for a placeholder added earlier in the request before using regular expressions
            if (strpos($body, static::PLACEHOLDER) !== false) {
                $script = $siteConfig->renderWith('GoogleTagManagerSnippet')
                    ->setProcessShortcodes(false)
                    ->forTemplate();
                $noScript = $siteConfig->renderWith('GoogleTagManagerNoScript')
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

        return true;
    }
}
