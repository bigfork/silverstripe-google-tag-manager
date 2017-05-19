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
	const NOSCRIPT_PLACEHOLDER = '%%NOSCRIPT_PLACEHOLDER%%';

	/**
     * {@inheritdoc}
     */
    public function preRequest(SS_HTTPRequest $request, Session $session, DataModel $model)
    {
        return true;
    }

    /**
     * Inserts the Google Tag Manager <noscript> tag after the opening <body> tag
     * {@inheritdoc}
     */
    public function postRequest(SS_HTTPRequest $request, SS_HTTPResponse $response, DataModel $model)
    {
    	$siteConfig = SiteConfig::current_site_config();
    	$redirectCodes = array(301, 302, 303, 304, 305, 307);

    	// There's no point trying to insert tag manager code to redirects
    	if (!in_array($response->getStatusCode(), $redirectCodes) && $siteConfig->GTMContainerID) {
    		$body = $response->getBody();

    		// Response body could be quite large, and we don't want the code inserted in the admin area,
    		// so we search for a placeholder added earlier in the request
    		if (strpos($body, static::NOSCRIPT_PLACEHOLDER) !== false) {
    			$script = $siteConfig->renderWith('GoogleTagManagerNoScript')
    				->setProcessShortcodes(false)
    				->forTemplate();

    			// Remove the placeholder and inject the noscript code
    			// ...this isn't perfect, but it's far faster than DOMDocument
    			$body = str_replace(static::NOSCRIPT_PLACEHOLDER, '', $body);
    			$body = preg_replace('/(<body.*?>)/is', "$1\n{$script}", $body);

    			$response->setBody($body);
    		}
    	}

        return true;
    }
}
