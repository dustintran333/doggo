<?php

namespace Doggo\Task;

use Doggo\Model\Park;
use GuzzleHttp\Client;
use SilverStripe\Dev\BuildTask;
use SilverStripe\ORM\DB;

class FetchPNCCTask extends BuildTask
{
    private static $api_url2;

    private static $api_title2;

    public function run($request)
    {
        $client = new Client();

        $response = $client->request(
            'GET',
            $this->config()->get('api_url2'),
            ['User-Agent' => 'Doggo (www.somar.co.nz)']
        );

        if ($response->getStatusCode() !== 200) {
            user_error('Could not access ' . $this->config()->get('api_url2'));
            exit;
        }

        /*
         * Mark existing records as IsToPurge.
         *
         * As we encounter each record in the API source, we unset this.
         * Once done, any still set are deleted.
         */
        $existingParks = Park::get()->filter('Provider', $this->config()->get('api_title2'));
        foreach ($existingParks as $park) {
            $park->IsToPurge = true;
            $park->write();
        }

        $data = json_decode($response->getBody());

        $parks = $data->features;
        foreach ($parks as $park) {
            $parkObject = Park::get()->filter([
                'Provider' => $this->config()->get('api_title2'),
                'ProviderCode' => $park->properties->OBJECTID,
            ])->first();
            $status = 'changed';

            if (!$parkObject) {
                $status = 'created';
                $parkObject = Park::create();
                $parkObject->Provider = $this->config()->get('api_title2');
                $parkObject->ProviderCode = $park->properties->OBJECTID;
            }

            //set leash
            if (isset($park->properties->On_Off)) {
                if ($park->properties->On_Off === 'Off leash') {
                    $leash = 'Off-leash';
                } elseif ($park->properties->On_Off === 'Prohibited') {
                    continue;
                }
            } else {
                $leash = 'On-leash';
            }

            //set geometry
            if(isset($park->geometry))
                $geometry = $park->geometry->coordinates;

            //set the rest of attr
            $parkObject->update([
                'IsToPurge' => false,
                'Title' => $park->properties->RESERVE_NAME?$park->properties->RESERVE_NAME:"NO NAME",
                'Latitude' => isset($geometry)?$geometry[0][0][0]:null,
                'Longitude' => isset($geometry)?$geometry[0][0][1]:null ,
                'Notes' => $park->properties->DESCRIPTION,
                'GeoJson' => json_encode($park),
                'FeatureOnOffLeash' => $leash,
            ]);

            $parkObject->write();

            DB::alteration_message('[' . $parkObject->ProviderCode . '] ' . $parkObject->Title, $status);
        }

        $existingParks = Park::get()->filter([
            'Provider' => $this->config()->get('api_title2'),
            'IsToPurge' => true,
        ]);
        foreach ($existingParks as $park) {
            DB::alteration_message('[' . $parkObject->ProviderCode . '] ' . $parkObject->Title, 'deleted');
            $park->delete();
        }
    }
}
