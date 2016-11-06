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

$app_basedir = realpath(__DIR__ . '/..');

// Override the simplesaml default config dir location (/vendor/simplesamlphp/simplesamlphp)
// to be outside the vendor tree managed by composer.

putenv("SIMPLESAMLPHP_CONFIG_DIR=${app_basedir}/config/simplesaml");

include_once( $app_basedir . '/config/app.php' );
include_once( $app_basedir . '/src/util.inc');
include_once( $app_basedir . '/vendor/autoload.php' );