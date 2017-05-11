<?php
class GoogleTagManager extends DataExtension
{
        static $db = array(
            'GTMContainerID' => 'Text'
        );

        public function updateCMSFields(FieldList $fields)
        {
                $fields->addFieldToTab(
                    'Root.GoogleTagManager',
                    textField::create('GTMContainerID', 'Container ID', '', 14)
                        ->setDescription('Example: GTM-XXXX')
                );
        }

        function contentControllerInit($controller)
        {

        }

}
