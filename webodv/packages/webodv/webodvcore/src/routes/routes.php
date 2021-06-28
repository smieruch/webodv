<?php


//----- wrap all into web middleware to make session etc. available ------//

//----- and also header, which cleans the second header, i.e. name of data set next to webODV -----//

Route::group(['middleware' => ['web','header']], function () {
    Route::get('/api/wms/form', 'webodv\webodvcore\WebodvcoreController@apiwmsform')->name('apiwmsform');

    Route::get('/monitor', 'webodv\webodvcore\WebodvcoreController@monitor')->name('monitor');
    Route::post('/monitor_process', 'webodv\webodvcore\WebodvcoreController@monitor_process')->name('monitor_process');

    Route::get('/geotraces/download/stats', 'webodv\webodvcore\WebodvcoreController@geotraces_download_stats')->name('geotraces_download_stats');

});

Route::get('/api/wms', 'webodv\webodvcore\WebodvcoreController@apiwms')->name('apiwms');











?>