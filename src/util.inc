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

namespace SURFnet\SFOSSPSample;

class Twig {
    private static $twig = NULL;

    static public function getInstance() {
        if (Twig::$twig)
            return Twig::$twig;

        $loader = new \Twig_Loader_Filesystem( __DIR__ . '/../resources/' );
        Twig::$twig = new \Twig_Environment($loader, array(
            'autoescape' => 'html'
        ));

        return Twig::$twig;
    }
}

/** Get the userID to use in the SFO authentication request from the native auth source
 * @param $nativeAuth SimpleSAML_Auth_Simple
 *
 * @return The userID (string) or false on error
 */
function getUserID(\SimpleSAML_Auth_Simple $nativeAuth) {
    $nativeAttrs = $nativeAuth->getAttributes();
    if  ( !isset($nativeAttrs[UID_ATTRIBUTE_NAME])
        || !isset($nativeAttrs[ORG_ATTRIBUTE_NAME]) ) {
        return FALSE;
    }
    $uid = $nativeAttrs[UID_ATTRIBUTE_NAME][0];
    $org = $nativeAttrs[ORG_ATTRIBUTE_NAME][0];

    if ( (strlen($uid) == 0) || (strlen($org) == 0) ) {
        return FALSE;
    }

    $userID='urn:collab:person:' . $org . ':' . $uid;

    return $userID;
}