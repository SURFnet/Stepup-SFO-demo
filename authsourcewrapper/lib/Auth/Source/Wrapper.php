<?php
/**
 * Copyright 2016 SURFnet bv
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */


class sspmod_authsourcewrapper_Auth_Source_Wrapper extends SimpleSAML_Auth_Source {

    private $authSourceID;
    private $wrappedAuthSourceID;
    private $loginParamsFunc = NULL;

    const WRAPPED_AUTH_SOURCE_ID = 'sspmod_authsourcewrapper_Auth_Source_Wrapper.WrappedauthSourceId';
    const STAGE_ID_LOGIN         = 'sspmod_authsourcewrapper_Auth_Source_Wrapper.StageId.Login';
    const STAGE_ID_LOGOUT        = 'sspmod_authsourcewrapper_Auth_Source_Wrapper.StageId.Logout';

    /**
     * Constructor for this authentication source.
     *
     * @param array $info	 Information about this authentication source.
     * @param array $config	 Configuration.
     */
    public function __construct($info, $config) {
        assert('is_array($info)');
        assert('is_array($config)');

        $authSourceID=$info['AuthId'];
        SimpleSAML\Logger::debug("sspmod_authsourcewrapper_Auth_Source_Wrapper: Constructing authsource '{$authSourceID}'");

        // Call the parent constructor first, as required by the interface
        parent::__construct($info, $config);

        if (!array_key_exists('authsource', $config)) {
            throw new Exception('The required "authsource" config option was not found');
        }

        $wrappedAuthSourceID = $config['authsource'];
        SimpleSAML\Logger::debug("sspmod_authsourcewrapper_Auth_Source_Wrapper: Wrapping authsource '{$wrappedAuthSourceID}'");

        $as = SimpleSAML_Auth_Source::getById($wrappedAuthSourceID);
        if ($as === NULL) {
            throw new Exception("Invalid authentication source: '{$wrappedAuthSourceID}'");
        }
        $this->wrappedAuthSourceID = $wrappedAuthSourceID;
        $this->authSourceID=$authSourceID;

        if (array_key_exists('loginParams', $config)) {
            $loginParams = $config['loginParams'];
            // Is there a way we can verify whether $loginParams is a closure?
            $this->loginParamsFunc=$loginParams;
        }
    }

    /**
     * Copy state from the authentication source $as into state
     *
     * @param &$state: state array of the wrapping authentication source
     * @param $as: the wrapped authentication source
     */
    private static function updateState(&$state, $as)
    {
        // We need to copy Attributes and user identifier and maybe other information from the wrapped
        // authentication source to make it available to the client of the wrapped authentication source
        // What do we need to copy? What should we copy?
        //
        // Current implementation:
        // Add keys from the wrapped auth source without overwriting existing keys

        // TODO: Is this the best / correct way to do this?
        $authData = $as->getAuthDataArray();
        if (is_array($authData))
            $state = array_merge($authData, $state);
    }


    /**
     * Save $state in the current session and create an URL that redirect to the www
     * directory in this module
     *
     * Use getAuthState() to get the previously loaded state
     *
     * @param $state: The state array to save
     * @param $stage: The stage identifier
     * @param $url: The redirect URL
     */
    private static function getStateRedirectURL($state, $stage, $url) {
        // Save the $state array, so that we can restore if after a redirect
        $id = SimpleSAML_Auth_State::saveState($state, $stage);

        // Create the redirect URL
        $redirectURL = SimpleSAML\Module::getModuleURL(
            'authsourcewrapper/'.$url,
            array('AuthState' => $id)
        );

        return $redirectURL;
    }

    /**
     * Load the previous saved state from a SimpleSAMLphp session
     *
     * @param $stage: Stage identifier of the state
     *
     * @throws Exception on error
     */
    private static function getAuthState($stage) {
        if (!array_key_exists('AuthState', $_REQUEST)) {
            throw new SimpleSAML_Error_BadRequest('Missing AuthState parameter.');
        }
        $authStateId = $_REQUEST['AuthState'];
        $state = SimpleSAML_Auth_State::loadState($authStateId, $stage);
        if (NULL === $state)
            throw new SimpleSAML_Error_Exception('Error loading AuthState');

        return $state;
    }

    /**
     * Process a request
     *
     * See SimpleSAML_Auth_Source::authenticate()
     *
     * @param array &$state Information about the current authentication.
     *
     * @throws Exception
     */
    public function authenticate(&$state) {
        assert('is_array($state)');

        $state[self::WRAPPED_AUTH_SOURCE_ID] = $this->wrappedAuthSourceID;

        // Save $state and get a URL that we cab return to after login to complete the login process
        $url = self::getStateRedirectURL($state, self::STAGE_ID_LOGIN, 'complete_login.php');

        // Evaluate the closure to get the parameters
        $params = array();
        if ($this->loginParamsFunc) {
            $closure = $this->loginParamsFunc;
            $params = $closure($this->authSourceID);
        }
        $params['ReturnTo'] = $url;

        // Start login to the real authentication source
        $as = new SimpleSAML_Auth_Simple($this->wrappedAuthSourceID);
        $as->requireAuth( $params );    // Does not return when authentication is required

        // In case we were authenticated without redirect, or were already authenticated
        // Sync state from wrapped source
        self::updateState($state, $as);

        // Return implies successful authentication
    }


    /**
     * Handle redirect to complete_login.php
     */
    static function complete_login() {
        // Retrieve the authentication state that we saved when we started the authentication
        $state=self::getAuthState(self::STAGE_ID_LOGIN);

        // Get the ID of the wrapped authentication source
        if (!isset($state[self::WRAPPED_AUTH_SOURCE_ID])) {
            throw new SimpleSAML_Error_Exception('Missing WRAPPED_AUTH_SOURCE_ID in session');
        }
        $wrappedAuthSourceID = $state[self::WRAPPED_AUTH_SOURCE_ID];

        // Verify that the wrapped authentication source is authenticated
        // This could be a previous authentication in the same session (i.e. SSO).
        $as = new SimpleSAML_Auth_Simple($wrappedAuthSourceID);
        if ( ! $as->isAuthenticated() ) {
            // We're not authenticated???
            throw new SimpleSAML_Error_Error('Login failed');
        }

        // Sync state from wrapped source
        self::updateState($state, $as);

        SimpleSAML_Auth_Source::completeAuth($state);
    }


    /**
     * Log out from this authentication source.
     *
     * See SimpleSAML_Auth_Source::logout()
     *
     * @param array &$state Information about the current logout operation.
     */
    public function logout(&$state) {
        assert('is_array($state)');

        $state[self::WRAPPED_AUTH_SOURCE_ID] = $this->wrappedAuthSourceID;

        // Save $state and get a URL that we can return to after logout to complete the logout process
        $url = self::getStateRedirectURL($state, self::STAGE_ID_LOGOUT, 'complete_logout.php');

        // Start logout process
        $as = new SimpleSAML_Auth_Simple($this->wrappedAuthSourceID);
        if ( $as->isAuthenticated() ) {
            $as->logout(
                array( 'ReturnTo' => $url )
            );
            // Depending on the logout implementation of the wrapped auth source we could be redirected
        }

        // We could also have been logged out without redirect
        // Sync state from wrapped source
        self::updateState($state, $as);

        // return implies the the logout was successful
    }


    /**
     * Handle redirect to complete_logout.php
     */
    static function complete_logout() {
        // Retrieve the authentication state that we saved when we started the authentication
        $state=self::getAuthState(self::STAGE_ID_LOGOUT);

        // Get the ID of the wrapped authentication source
        if (!isset($state[self::WRAPPED_AUTH_SOURCE_ID])) {
            throw new SimpleSAML_Error_Exception('Missing WRAPPED_AUTH_SOURCE_ID in session');
        }
        $wrappedAuthSourceID = $state[self::WRAPPED_AUTH_SOURCE_ID];

        $as = new SimpleSAML_Auth_Simple($wrappedAuthSourceID);
        if ( $as->isAuthenticated() ) {
            // We're still authenticated???
            throw new SimpleSAML_Error_Error('Logout failed');
        }

        // Sync state from wrapped source
        self::updateState($state, $as);

        SimpleSAML_Auth_Source::completeLogout($state);
    }

}