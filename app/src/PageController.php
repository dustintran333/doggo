<?php

namespace {

    use SilverStripe\CMS\Controllers\ContentController;
    use SilverStripe\Core\Environment;
    use SilverStripe\View\Requirements;

    class PageController extends ContentController
    {
        /**
         * An array of actions that can be accessed via a request. Each array element should be an action name, and the
         * permissions or conditions required to allow the user to access it.
         *
         * <code>
         * [
         *     'action', // anyone can access this action
         *     'action' => true, // same as above
         *     'action' => 'ADMIN', // you must have ADMIN permissions to access this action
         *     'action' => '->checkAction' // you can only access this action if $this->checkAction() returns true
         * ];
         * </code>
         *
         * @var array
         */
        private static $allowed_actions = [];

        protected function init()
        {
            parent::init();

            $liveReload = Environment::getEnv('LIVE_RELOAD_URL');

            if (!empty($liveReload)) {
                Requirements::javascript($liveReload);
            }

            Requirements::css('app.css');
            Requirements::javascript('app.js');
        }

        public function appSettings()
        {
            $data = json_encode([
                'MAPBOX_TOKEN' => Environment::getEnv('MAPBOX_TOKEN')
            ], TRUE);

            return $data;
        }
    }
}
