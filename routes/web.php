<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return view('frontend/home/index');
});

Route::get('/consummation', function () {
    return view('frontend/home/consummation');
});

//未使用　faqはhtmlで実装
//Route::get('/faqs', function () {
//    return view('frontend/home/faqs');
//});

Route::get('/register', 'RegisterController@index');
Route::post('/register', 'RegisterController@update');
Route::get('/register/complete', 'RegisterController@show');

Route::get('/complete', function () {
    return view('frontend/home/complete');
});

Route::get('/login', 'AuthController@show')->middleware('check_had_login');
Route::post('/login', 'AuthController@login');

Route::get('/accountReminder', 'AuthController@passwordReminder');
Route::post('/accountReminder', 'AuthController@passwordReminderApply');
Route::post('/passwordChange', 'AuthController@passwordChangeApply');

Route::match(['get', 'post'], 'reservation', 'PaymentController@executeReservation');
Route::match(['get', 'post'], 'cancelreservation', 'PaymentController@cancelReservation');

//map excel upload
Route::post('excel/import','ExcelController@index');

//event image upload
Route::post('eventImage/import', 'EvenController@eventUploadImage')->middleware('event_info_authority_fault_access');//STS 2021/09/06 Task 48 No.2

//map block image upload
Route::post('blockImage/import', 'EvenController@uploadImage');

//image upload
Route::post('/upload_image', 'EvenController@editorImangeUpload');

Route::post('/accountPasswordChange', 'AdminManageController@accountPasswordChange');

Route::group(['middleware' => ['check_login']], function () {
    
    Route::get('/logout', 'AuthController@logout');

    Route::get('/notice', 'NoticeController@index');
    Route::get('/help', 'HelpController@index');

    Route::group(['middleware' => ['profile_authority']], function () {
        //user manage
        Route::get('/userManage', 'UserManage@index')->name('userManage');
        Route::post('/userManage', 'UserManage@addUserData');
        Route::post('/userManage/dataChange', 'UserManage@changeSubUserData');
        Route::get('/userManage/editInf', 'UserManage@edit')->middleware('event_info_authority_fault_access'); //STS 2021/09/10 Task 48 No.2
        Route::post('/userManage/editInf', 'UserManage@accountInfChange')->middleware('event_info_authority_fault_access'); //STS 2021/09/10 Task 48 No.2
        Route::post('/userManage/changePassword', 'UserManage@changePassword');
        Route::get('/userManage/infApply', 'UserManage@infApply');
        Route::get('/userManage/countryCheack', 'UserManage@countryCheack');
        Route::post('/userManage/accountDelete', 'UserManage@accountDelete');
        // Route::get('/userManage/trans', 'UserManage@transport');
    });

    Route::group(['middleware' => ['event_info_authority']], function () {
        //event manage
        Route::get('/events', 'EvenController@index');
        Route::get('/events/filter', 'EvenController@indexFilter');
        Route::get('/events/create', 'EvenController@create')->middleware('event_info_authority_fault_access'); //STS 2021/09/06 Task 48 No.2
        Route::post('/events/create', 'EvenController@addData')->middleware('event_info_authority_fault_access');//STS 2021/09/06 Task 48 No.2
        Route::get('/events/info/{performanceId}', 'EvenController@edit')->middleware('check_performance_id');
        Route::post('/events/info/{performanceId}', 'EvenController@update')->middleware('check_performance_id');
        Route::post('/events/delete', 'EvenController@eventDelete');
        Route::post('/events/preview/{performanceId}', 'EvenController@preview')->middleware('event_info_authority_fault_access');//STS 2021/09/06 Task 48 No.2
        // Route::post('/events/MapFileUpload', 'EvenController@mapDataUpdate');
        Route::post('/events/trans', 'EvenController@transport')->middleware('check_performance_id');//STS 2021/09/14 Task 48 No.2
        Route::post('/events/republish', 'EvenController@republish')->middleware('check_performance_id');//STS 2021/09/14 Task 48 No.2
    });

    Route::group(['middleware' => ['sales_info_authority']], function () {
        //sell manage
        Route::get('/sell', 'SellManage@index');
        Route::post('/sell', 'SellManage@indexSearch');
        Route::get('/sell/manage/{performanceId}', 'SellManage@manage')->middleware('check_performance_id');
        Route::get('/sell/unpublished/seat/{draft_id}/{performance_date}/{rule_id}', 'SellManage@unpublished');
        Route::get('/sell/stop', 'SellManage@stop');
        Route::get('/sell/detail/{scheduleId}', 'SellManage@detail')->middleware('check_schedule_id');
        Route::post('/sell/resSeatSetting', 'SellManage@insertDraw');
        Route::post('/sell/resendNotice', 'SellManage@resendNotice');
        Route::post('/sell/detail/{scheduleId}', 'SellManage@detailSelect')->middleware('check_schedule_id');
        Route::get('/sell/seat/{scheduleId}', 'SellManage@seatMap')->middleware('check_schedule_id');
        Route::post('/sell/seat', 'SellManage@uploadSeatMap');
        Route::get('/sell/members/{keyword?}', 'SellManage@getMembers');
        Route::post('/sell/orderCancel/{scheduleId}', 'SellManage@orderCancel')->middleware('check_schedule_id');//STS 2021/09/06 Task 48 No.2
        Route::post('/sell/reviseAmount/{scheduleId}', 'SellManage@reviseAmount');
        Route::match(['get', 'post'], '/orders', 'SellManage@getOrders');
        Route::get('/sell/detail/csv/{scheduleId}','SellManage@csvExport')->middleware('check_schedule_id');//STS 2021/09/14 Task 48 No.2
        Route::get('/csv/orders','SellManage@orderCsvExport');

        //report manage
        Route::get('/report', 'ReportController@index');
        Route::get('/systemreport', 'ReportController@systemReport');
        Route::post('/systemreport', 'ReportController@selectEvent');
        //Route::get('/systemreport/selectevent/{GLID}/{date}', 'ReportController@selectEvent');
        Route::post('/systemreport/systemreport', 'ReportController@systemReportOutput');
    });

    //member manage
    Route::get('/member', 'MemberController@index');
    Route::get('/member/information/{userId}', 'MemberController@information');
    Route::get('/member/orders/{userId}/{ordersId}', 'MemberController@orders');

    Route::group(['middleware' => ['event_info_authority_fault_access']], function () { //STS 2021/09/06 Task 48 No.2
        Route::get('/schedule/list/{performanceId}/{scheduleId}', 'ScheduleController@scheduleList')->middleware('check_performance_id');
        Route::post('/schedule/cancel', 'ScheduleController@scheduleCancel');
    });

    Route::group(['middleware' => ['check_admin_permission']], function () {
        //admin manage
        Route::get('/adminManage', 'AdminManageController@index');
        Route::get('/dataValidation/{GLID}', 'AdminManageController@dataValidation');
        Route::post('/dataValidation/upload', 'AdminManageController@dataValidationUpload');
        Route::post('/dataValidation/accountInf/upload', 'AdminManageController@accountDataUpload');
        Route::get('/dataDetail/{GLID}', 'AdminManageController@detail');
        Route::post('/dataDetail/{GLID}', 'AdminManageController@detailUpdate');
    });
});
