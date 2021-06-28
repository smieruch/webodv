<?php


//----- wrap all into web middleware to make session etc. available ------//

//----- and also header, which cleans the second header, i.e. name of data set next to webODV -----//

Route::group(['middleware' => ['web','header','auth']], function () {
    Route::get('/webodv/data/{datasetname}/service/DataExploration', 'webodv\odvonline\OdvonlineController@odvonline_init')->name('odvonline_init');
    Route::get('/odvonline', 'webodv\odvonline\OdvonlineController@odvonline')->name('odvonline');
});

?>