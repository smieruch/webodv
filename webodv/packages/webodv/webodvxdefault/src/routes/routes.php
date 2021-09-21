<?php


//----- wrap all into web middleware to make session etc. available ------//

//----- and also header, which cleans the second header, i.e. name of data set next to webODV -----//


/* Route::get('/', function () { */
/*     return abort(401); */
/* }); */

//Basic HTTP Auth
//Route::group(['middleware' => ['auth.basic.once']], function () {


//Route::group(['middleware' => ['web','header','auth']], function () {

if (config('webodv.set_auth')){
    $use_middleware = ['web','header','auth'];
} else {
    $use_middleware = ['web','header'];
}


Route::group(['middleware' => $use_middleware], function () {
            
    //stats
    Route::get('/webodv/stats/get', 'webodv\webodvcore\WebodvcoreController@stats')->name('stats');

    //download
   //override
    //Route::post('/webodv/data/{datasetname}/service/Download', 'webodv\webodvgeotraces\WebodvgeotracesController@webodvextractor_download')->name('webodvextractor_download');
    //Route::post('/{datasetname}/service/Download', 'webodv\webodvgeotraces\WebodvgeotracesController@webodvextractor_download')->name('webodvextractor_download');

    Route::post('/webodv/data/{datasetname}/service/Download', 'webodv\webodvextractor\WebodvextractorController@webodvextractor_download')->name('webodvextractor_download');



    //main
    Route::match(array('GET', 'POST'),'/{treeview_jump}', 'webodv\webodvcore\WebodvcoreController@index')->name('webodv');
    Route::match(array('GET', 'POST'),'/', 'webodv\webodvcore\WebodvcoreController@index')->name('webodv');
    Route::get('/', 'webodv\webodvcore\WebodvcoreController@index')->name('webodv');
    Route::get('/service/{datasetname}', 'webodv\webodvcore\WebodvcoreController@service')->name('data');

    //
    Route::post('/webodv/create_treeview_from_folder_ajax', 'webodv\webodvcore\WebodvcoreController@create_treeview_from_folder_ajax')->name('create_treeview_from_folder_ajax');
    //
    Route::get('/webodv/add_project', 'webodv\webodvcore\WebodvcoreController@add_project_get')->name('add_project_get');
    Route::post('/webodv/remove_project', 'webodv\webodvcore\WebodvcoreController@remove_project')->name('remove_project');
    Route::post('/webodv/add_project', 'webodv\webodvcore\WebodvcoreController@add_project_post')->name('add_project_post');

    //
    Route::post('webodv/data_upload', 'webodv\webodvcore\WebodvcoreController@data_upload')->name('data_upload');
    Route::post('webodv/delete_collection', 'webodv\webodvcore\WebodvcoreController@delete_collection')->name('delete_collection');
    Route::post('webodv/download_collection', 'webodv\webodvcore\WebodvcoreController@download_collection')->name('download_collection');

    //profile
    Route::post('/webodv/change/profile', 'webodv\webodvcore\WebodvcoreController@profile')->name('profile');

    //delete account
    Route::post('/webodv/delete/account', 'webodv\webodvcore\WebodvcoreController@deleteaccount')->name('deleteaccount');

    //only role==admin
    //write email to all users
    //Route::get('/announcements', 'webodv\webodvcore\WebodvcoreController@announcements')->name('announcements');

    //copy old geotraces db to new users db
    //Route::get('/copy_db', 'webodv\webodvcore\WebodvcoreController@copy_db')->name('copy_db');
});


Route::group(['middleware' => ['web']], function () {
    //disable CSRF to request via POST from other URL's, e.g. in a cloud environment
    Route::match(array('GET', 'POST'),'/{datasetname}/service/{servicename}/wsODV', 'webodv\webodvcore\WebodvcoreController@wsodv_init')->name('wsodv_init');

    //explore
    Route::get('/{datasetname}/service/DataExploration', 'webodv\odvonline\OdvonlineController@odvonline_init')->name('odvonline_init');
    Route::get('/odvonline', 'webodv\odvonline\OdvonlineController@odvonline')->name('odvonline');

    //extractor
    Route::get('/{datasetname}/service/DataExtraction', 'webodv\webodvextractor\WebodvextractorController@webodvextractor')->name('webodvextractor');
    Route::get('/{datasetname}/service/DataExtraction/create_treeview', 'webodv\webodvextractor\WebodvextractorController@webodvextractor_create_treeview')->name('webodvextractor_create_treeview');


    //contact
    Route::post('/webodv/get/contact', 'webodv\webodvcore\WebodvcoreController@contact')->name('contact');

    //tracking
    Route::get('/webodv/stats/tracking', 'webodv\webodvcore\WebodvcoreController@tracking')->name('tracking');

    //getuserauthinfo
    //Route::post('/getuserauthinfo', 'webodv\webodvcore\WebodvcoreController@getuserauthinfo')->name('getuserauthinfo');

    //awi import
    //Route::post('/awi/import', 'webodv\webodvawi\WebodvawiController@awiimport')->name('awiimport');

    //awi import client
    //Route::get('/awi/import/client', 'webodv\webodvawi\WebodvawiController@awiimportclient')->name('awiimportclient');

    //awi import service
    //Route::get('/awi/import/service', 'webodv\webodvawi\WebodvawiController@awiimportservice')->name('awiimportservice');
    
});





?>