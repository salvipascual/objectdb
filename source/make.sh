#!/bin/bash

php process.php templates/index.tpl ../
php process.php templates/doc.tpl ../
php process.php templates/download.tpl ../
php process.php templates/about.tpl ../
php process.php templates/donate.tpl ../

php process.php templates/api.objectdb.tpl ../
php process.php templates/api.odbconnection.tpl ../
php process.php templates/api.odbobject.tpl ../
php process.php templates/api.odbexception.tpl ../

php process.php templates/userpostit.tpl ../
