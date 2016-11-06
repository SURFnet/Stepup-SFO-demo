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

include_once '../src/bootstrap.inc';

// Logout from the native session
$nativeAuth = new SimpleSAML_Auth_Simple('native-auth');
if ( $nativeAuth->isAuthenticated() ) {
    $nativeAuth->logout( array (
            'ReturnTo' => 'logout.php'   // Return to this page
        )
    );
}

// Logout from the SFO session
$sfoAuth = new SimpleSAML_Auth_Simple('sfo-sp');
if ( $sfoAuth->isAuthenticated() ) {
    $sfoAuth->logout(
        array(
            'ReturnTo' => 'index.php'   // Return to index page
        )
    );
}

\SimpleSAML\Utils\HTTP::redirectTrustedURL('index.php'); // Return to index page