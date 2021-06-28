#!/bin/bash

#replace privacy announcement
cp my_privacy.html ../webodv/public/privacy.html

#replace impressum
cp my_impressum.html ../webodv/public/impressum.html 

#replace config
cp my_webodv.php ../webodv/config/webodv.php

#replace .env
cp my_PKTenv ../settings/PKTenv 

#replace settings
cp my_webodv_settings_default.json ../settings/webodv_settings_default.json 
