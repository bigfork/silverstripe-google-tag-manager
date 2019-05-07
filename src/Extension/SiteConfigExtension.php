<?php

namespace Bigfork\SilverStripeGoogleTagManager\Extension;

use SilverStripe\Core\Extension;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;

class SiteConfigExtension extends Extension
{
    private static $db = [
        'GTMContainerID' => 'Text'
    ];

    /**
     * @param FieldList $fields
     */
    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldToTab(
            'Root.GoogleTagManager',
            TextField::create('GTMContainerID', 'Container ID', '', 14)
                ->setDescription('Example: GTM-XXXX')
        );
    }
}
