<?php
$api->version('v1', [], function ($api) {
    $api->group(['middleware' => 'jwt.auth'], function($api) {

        # Customer Record
        $api->group(['prefix' => 'customers/{customer_id}'], function($api) {
            $api->get('/', 'App\Http\Controllers\Records\CustomerRecordsController@customer');
            $api->resource('customer-records', 'App\Http\Controllers\Records\CustomerRecordsController');
        });

        $api->post('customer-records/upload', 'App\Http\Controllers\Records\CustomerRecordsController@upload');
        $api->get('customer-records/download/{customer_record_id}', 'App\Http\Controllers\Records\CustomerRecordsController@download');

        # Employee (BOR and Employee) Record
        $api->group(['prefix' => 'employees'], function($api) {
            $api->get('bods', 'App\Http\Controllers\Records\EmployeeRecordsController@bods');
            $api->get('non-bods', 'App\Http\Controllers\Records\EmployeeRecordsController@nonBods');
            $api->group(['prefix' => '{employee_id}'], function($api) {
                $api->resource('employee-records', 'App\Http\Controllers\Records\EmployeeRecordsController');
            });
        });

        $api->post('employee-records/upload', 'App\Http\Controllers\Records\EmployeeRecordsController@upload');
        $api->get('employee-records/download/{bod_record_id}', 'App\Http\Controllers\Records\EmployeeRecordsController@download');

        # Box Description
        $api->resource('box-descriptions', 'App\Http\Controllers\Records\BoxDescriptionsController');

        # Company Documents
        $api->resource('company-documents', 'App\Http\Controllers\Records\CompanyDocumentsController');
        $api->group(['prefix' => 'company-documents/{company_document_id}'], function($api) {
            $api->resource('company-document-records', 'App\Http\Controllers\Records\CompanyDocumentRecordsController');
            $api->resource('transfer-histories', 'App\Http\Controllers\Records\TransferHistoriesController');
        });

    });
});
