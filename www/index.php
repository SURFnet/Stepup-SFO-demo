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

// This demo application uses the second factor only (SFO) authentication endpoint of SURFconext
// Strong Authentication (SA). SFO authentication is for applications that want to authenticate *only* the second
// authentication factor of the user. These applications use some other, application specific, means of handling the
// first factor authentications and establishing the identity of the user.
// Applications that do not use SFO use the normal SURFconext Strong Authentication endpoint and get all of the above
// in one authentication step.

$twig = \SURFnet\SFOSSPSample\Twig::getInstance();
$template = $twig->loadTemplate('index.html.twig');

$param = array(
    'native' => array(),
    'sfo' =>array()
);

// Fetch native authentication state
$nativeAuth = new SimpleSAML_Auth_Simple('native-auth');
$param['native']['auth'] = $nativeAuth->isAuthenticated();
if ( $param['native']['auth'] ) {
    $param['native']['attr'] = $nativeAuth->getAttributes();
}

$param['sfo']['userid'] = \SURFnet\SFOSSPSample\getUserID($nativeAuth);
$sfo = new SimpleSAML_Auth_Simple('sfo-sp');
$param['sfo']['auth'] = $sfo->isAuthenticated();
$param['sfo']['authData'] = $sfo->getAuthDataArray();

echo $template->render($param);