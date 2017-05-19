<?php

namespace Bigfork\SilverStripeGoogleTagManager\Extension;

use Extension;
use FieldList;
use TextField;

class SiteConfigExtension extends Extension
{
    private static $db = array(
        'GTMContainerID' => 'Text'
    );

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
