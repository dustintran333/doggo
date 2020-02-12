<?php

namespace Doggo\Model;

class Park extends \SilverStripe\ORM\DataObject
{
    private static $table_name = 'Park';

    private static $db = [
        'Title' => 'Varchar',
        'Latitude' => 'Decimal(9,6)',
        'Longitude' => 'Decimal(9,6)',
        'Notes' => 'Text',
        'Provider' => "Enum(array('Palmy CC', 'Wellington City Council'))",
        'ProviderCode' => 'Varchar(100)', //GlobalID
        'GeoJson' => 'Text',
        'FeatureOnOffLeash' => "Enum(array('On-leash', 'Off-leash'), 'On-leash')",
        'IsToPurge' => 'Boolean',
    ];

    private static $summary_fields = [
        'Title' => 'Title',
    ];

    private static $indexes = [
        'Provider' => [
            'columns' => ['Provider'],
        ],
        'ProviderCode' => [
            'columns' => ['Provider', 'ProviderCode'],
        ],
    ];

    private static $api_access = true;

    public function validate()
    {
        $validate = parent::validate();

        if (empty($this->Title)) {
            $validate->addFieldError('Title', 'Title is required');
        }

        return $validate;
    }

    public function canView($member = null)
    {
        return true;
    }
}
