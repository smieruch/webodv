<?php

return [
    'settings_path' => getenv('settings_path'),
    'settings_name' => getenv('settings_name'),
    'path_to_odv_settings' => getenv('path_to_odv_settings'),
    'path_to_odv_data' => getenv('path_to_odv_data'),
    'proxy' => getenv('settings_path').'/'.getenv('path_to_odv_settings').'/'.getenv('proxy_ws'),
    'webodv_service' => getenv('webodv_service'),
    'geotraces_url' => getenv('geotraces_url'),
    'emodnet_url' => getenv('emodnet_url'),
    'explore_url' => getenv('explore_url'),
    'awi_url' => getenv('awi_url'),
    'set_auth' => true,
    'home_url' => getenv('home_url'),
    'mode' => '.min', //empty or '.min'
    'copyrights' => 'webODV 2021',
    'brand' => 'Your brand',
    'index_heading' => 'Add your heading!',
    'index_add_text' => '<div class="text-success"><b>Add your own text!. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</b></div>',
    'login_add_text' => '<div class="text-success"><b>Add your own text!. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</b></div>',
    'cookie_text' => 'We use cookies to ensure that we give you the best experience on our website. Additionally we use the web analytics software <i>Matomo</i> to monitor page usage. If you continue to use this site we will assume that you are happy with it.'
];

?>