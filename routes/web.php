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
// Auth::routes();

/*
|--------------------------------------------------------------------------
| Website Routes
|--------------------------------------------------------------------------
|
| Routes for the website
|
*/
// Landing Page
Route::get('/', 'WebNavigationController@index')->name('landingpage');

// Web - Amenities
Route::get('/amenities', 'WebNavigationController@goToAmenities')->name('web.amenities');

// Web - Caterers
Route::get('/caterers', 'WebNavigationController@goToCaterers')->name('web.caterers');

// Web - About Us
Route::get('/about-us', 'WebNavigationController@goToAboutUs')->name('web.aboutus');

// Web - Contact Us
Route::get('/contact-us', 'WebNavigationController@goToContactUs')->name('web.contactus');

// Web - Schedule
Route::get('/schedule', 'WebNavigationController@goToSchedules')->name('web.schedules');

// Web - Rates
Route::get('/rates', 'WebNavigationController@goToRates')->name('web.rates');

// Web - Reservation
// Route::get('/reservation', 'WebNavigationController@goToReservation')->name('web.reservation');
// Route::post('/reservation', 'ReservationInfoController@store')->name('reservationform.submit');

// GET ROUTES FOR AJAX
Route::post('/getreservedrooms', 'WebNavigationController@getReservedRooms');
Route::post('/edit/getreservedrooms', 'WebNavigationController@getOnEditReservedRooms');

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------
|
| Routes for the customer
|
*/
Route::prefix('customer')->group(function() {
    Route::get('/login', 'Auth\CustomerLoginController@showLoginForm')->name('client.login');
    Route::post('/login', 'Auth\CustomerLoginController@login')->name('client.login.submit');
    Route::get('/register', 'Auth\CustomerRegisterController@showRegisterForm')->name('client.register');
    Route::post('/register', 'Auth\CustomerRegisterController@register')->name('client.register.submit');

    Route::group(['middleware' => ['auth:customer']], function() {
        Route::post('/logout', 'Auth\CustomerLoginController@logout')->name('client.logout');

        // Home?????
        Route::get('/', 'ClientController@index')->name('client.index');

        // Reservation routes
        Route::get('/reservation', 'ClientController@goToReservationsPage')->name('client.landingpage');
        Route::get('/reservation/{id}/view', 'ClientController@showReservationInfo')->name('client.show.reservationinfo');
        Route::get('/reservation/create', 'ClientController@showReservationForm')->name('client.reservationform');
        Route::post('/reservation/create', 'ClientController@submitReservationForm')->name('client.reservationform.submit');
        Route::get('/reservation/{id}/edit', 'ClientController@editReservationInfo')->name('client.edit.reservationinfo');
        Route::put('/reservation/{id}/update', 'ClientController@updateReservationInfo')->name('client.update.reservationinfo');
        Route::put('/reservation/{id}/cancel', 'ClientController@cancelReservation')->name('client.reservation.cancel');
        Route::delete('/reservation/{id}/undo-cancellation', 'ClientController@undoReservationCancellation')->name('client.undo.cancel');
        Route::put('/reservation/{id}/done', 'ClientController@markReservationAsDone')->name('client.reservation.done');

        // Payment routes
        Route::get('/payments', 'ClientController@goToPaymentsPage')->name('client.payments');
        Route::post('/payments/add', 'ClientController@submitNewPayment')->name('client.payments.submit');
        Route::put('/payments/{id}/update', 'ClientController@updatePayment')->name('client.payments.update');

        // Balance
        Route::get('/balances', 'ClientController@goToBalancePage')->name('client.balance');
        
        // Print routes
        Route::get('/reservation/{id}/print', 'ClientController@printReservationInfo')->name('client.print.reservationinfo');
        Route::get('/resevation/{id}/voucher', 'ClientController@printVoucher')->name('client.print.voucher');
        Route::get('/reservation/{id}/reservation-confirmation', 'ClientController@printReservationConfirmation')->name('client.print.confirmation');
        Route::get('/reservation/{id}/reservation-contract', 'ClientController@printReservationContract')->name('client.print.reservation-contract');
        Route::get('/reservation/{id}/billing-statement', 'ClientController@printBillingStatement')->name('client.print.billing-statement');

        // Other routes
        Route::get('/profile', 'ClientController@goToProfilePage')->name('client.show.profile');
        Route::post('/profile', 'ClientController@updateProfile')->name('client.update.profile');
        Route::post('/password/update', 'ClientController@updatePassword')->name('client.update.password');
        Route::get('/settings', 'ClientController@goToSettingsPage')->name('client.settings');

        Route::get('/test', 'ClientController@test')->name('client.test');
    });
});


/*
|--------------------------------------------------------------------------
| Admin Side Routes
|--------------------------------------------------------------------------
|
| Routes for the admin
|
*/
Route::prefix('admin')->group(function() {
    Route::get('/login', 'Auth\LoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'Auth\LoginController@login')->name('admin.login.submit');

    Route::group(['middleware' => ['auth']], function() {
        Route::post('/logout', 'Auth\LoginController@logout')->name('admin.logout');
        // DASHBOARD
        Route::get('/dashboard', 'AdminNavigationController@index')->name('admin.dashboard');

        // RESERVATIONS
        // Route::resource('reservations', 'ReservationController');
        Route::get('/reservations', 'ReservationController@index')->name('admin.reservation');
        Route::get('/reservations/view/{id}', 'ReservationController@showReservationInfo')->name('admin.showreservationinfo');
        Route::put('/reservations/{id}/cancel', 'ReservationController@cancelReservation')->name('admin.reservation.cancel');
        Route::put('/reservations/{id}/delete', 'ReservationController@cancelandDeleteReservation')->name('admin.reservation.delete');
        // Release of forms
        Route::get('/reservation/{id}/contract-release', 'ReservationController@releaseContract')->name('admin.release.contract');
        Route::get('/reservation/{id}/billing-release', 'ReservationController@releaseBilling')->name('admin.release.billing');
        Route::post('/reservation/{id}/contract-release', 'ReservationController@submitContract')->name('admin.submit.contract');
        Route::post('/reservation/{id}/billing-release', 'ReservationController@submitBilling')->name('admin.submit.billing');

        // PAYMENTS
        // Route::resource('payments', 'PaymentController');
        Route::get('/payments', 'PaymentController@index')->name('admin.payments.index');
        Route::put('/payments/{id}/confirm', 'PaymentController@confirm')->name('admin.payments.confirm');
        Route::put('/payments/{id}/reject', 'PaymentController@reject')->name('admin.payments.reject');

        // BALANCE
        Route::get('/balances', 'AdminNavigationController@balance')->name('admin.balance.index');

        Route::prefix('maintenance')->group(function() {
            // Amenities
            // Route::resource('amenities', 'AmenityController');
            Route::get('/amenities', 'AmenityController@index')->name('admin.amenities.index');
            Route::post('/amenities', 'AmenityController@store')->name('admin.amenities.store');
            Route::put('/amenities/{id}/edit', 'AmenityController@update')->name('admin.amenities.edit');
            Route::delete('/amenities/{id}/destroy', 'AmenityController@destroy')->name('admin.amenities.destroy');
            Route::patch('/amenities/{id}/restore', 'AmenityController@restore')->name('admin.amenities.restore');

            // Equipments
            // Route::resource('equipments', 'EquipmentController');
            Route::get('/equipments', 'EquipmentController@index')->name('admin.equipments.index');
            Route::post('/equipments/zxc', 'EquipmentController@store')->name('admin.equipments.store');
            Route::put('/equipments/{id}/edit', 'EquipmentController@update')->name('admin.equipments.edit');
            Route::delete('/equipments/{id}/destroy', 'EquipmentController@destroy')->name('admin.equipments.destroy');
            Route::patch('/equipments/{id}/restore', 'EquipmentController@restore')->name('admin.equipments.restore');
            Route::post('/equipments/get', 'EquipmentController@getEquipment'); // AJAX GET ROUTE

            // Event Nature
            // Route::resource('events', 'EventNatureController');
            Route::get('/events', 'EventNatureController@index')->name('admin.events.index');
            Route::post('/events', 'EventNatureController@store')->name('admin.events.store');
            Route::put('/events/{id}/edit', 'EventNatureController@update')->name('admin.events.edit');
            Route::delete('/events/{id}/destroy', 'EventNatureController@destroy')->name('admin.events.destroy');
            Route::patch('/events/{id}/restore', 'EventNatureController@restore')->name('admin.events.restore');
            Route::post('/events/get', 'EventNatureController@getEventNature'); // AJAX GET ROUTE

            // Event Setup
            // Route::resource('setup', 'EventSetupController');
            Route::get('/setup', 'EventSetupController@index')->name('admin.setup.index');
            Route::post('/setup', 'EventSetupController@store')->name('admin.setup.store');
            Route::put('/setup/{id}/edit', 'EventSetupController@update')->name('admin.setup.edit');
            Route::delete('/setup/{id}/destroy', 'EventSetupController@destroy')->name('admin.setup.destroy');
            Route::patch('/setup/{id}/restore', 'EventSetupController@restore')->name('admin.setup.restore');
            Route::post('/setup/get', 'EventSetupController@getEventSetup'); // AJAX GET ROUTE

            // Function Halls
            // Route::resource('function-halls', 'FunctionHallController');
            Route::get('/function-halls', 'FunctionHallController@index')->name('admin.function-halls.index');
            Route::post('/function-halls', 'FunctionHallController@store')->name('admin.function-halls.store');
            Route::put('/function-halls/{id}/edit', 'FunctionHallController@update')->name('admin.function-halls.edit');
            Route::delete('/function-halls/{id}/destroy', 'FunctionHallController@destroy')->name('admin.function-halls.destroy');
            Route::patch('/function-halls/{id}/restore', 'FunctionHallController@restore')->name('admin.function-halls.restore');
            Route::post('/function-halls/get', 'FunctionHallController@getFunctionHall'); // AJAX GET ROUTE

            // Meeting Rooms
            // Route::resource('meeting-rooms', 'MeetingRoomController');
            Route::get('/meeting-rooms', 'MeetingRoomController@index')->name('admin.meeting-rooms.index');
            Route::post('/meeting-rooms', 'MeetingRoomController@store')->name('admin.meeting-rooms.store');
            Route::put('/meeting-rooms/{id}/edit', 'MeetingRoomController@update')->name('admin.meeting-rooms.edit');
            Route::delete('/meeting-rooms/{id}/destroy', 'MeetingRoomController@destroy')->name('admin.meeting-rooms.destroy');
            Route::patch('/meeting-rooms/{id}/restore', 'MeetingRoomController@restore')->name('admin.meeting-rooms.restore');
            Route::post('/meeting-rooms/get', 'MeetingRoomController@getMeetingRoom'); // AJAX GET ROUTE

            // Customers
            // Route::resource('customers', 'CustomerController');
            Route::get('/customers', 'AdminNavigationController@customers')->name('admin.customers.index');
            Route::post('/customers/get', 'AdminNavigationController@getCustomer');

            // Caterers
            // Route::resource('/caterers', 'CatererController');
            Route::get('/caterers', 'CatererController@index')->name('admin.caterers.index');
            Route::post('/caterers', 'CatererController@store')->name('admin.caterers.store');
            Route::put('/caterers/{id}/edit', 'CatererController@update')->name('admin.caterers.edit');
            Route::delete('/caterers/{id}/destroy', 'CatererController@destroy')->name('admin.caterers.destroy');
            Route::patch('/caterers/{id}/restore', 'CatererController@restore')->name('admin.caterers.restore');

            // Timeblock
            Route::resource('timeblock', 'TimeblockController');
        });

        // Utilities
        Route::prefix('utilities')->group(function() {
            // Users
            Route::get('/users', 'AdminAccountController@index')->name('admin.users.index');
            Route::post('/users', 'Auth\RegisterController@register')->name('admin.users.create');
            Route::put('/users/{id}/edit', 'AdminAccountController@update')->name('admin.users.edit');
            Route::delete('/users/{id}/destroy', 'AdminAccountController@destroy')->name('admin.users.destroy');
            Route::patch('/users/{id}/restore', 'AdminAccountController@restore')->name('admin.users.restore');
            Route::post('/users/get', 'AdminAccountController@getUserInfo')->name('admin.users.get'); //AJAX Route

            Route::get('/backupandrestore', 'AdminNavigationController@backupandrestore')->name('admin.backupandrestore');
            Route::get('/user-log', 'AdminNavigationController@userlog')->name('admin.userlog');
        });
        
        // Reports
        Route::get('/reports/reservation', 'ReportController@reservation')->name('admin.reports.reservation');
        Route::post('/reports/reservation', 'ReportController@updateReservationReport')->name('admin.reports-reservation.update');
        Route::post('/reports/reservation/generatepdf', 'ReportController@generateReservationReport')->name('admin.reports-reservation.generatepdf');

        Route::get('/reports/sales', 'ReportController@sales')->name('admin.reports.sales');
        Route::post('/reports/sales', 'ReportController@updateSalesReport')->name('admin.reports-sales.update');
        Route::post('/reports/sales/generatepdf', 'ReportController@generateSalesReport')->name('admin.reports-sales.generatepdf');


        // MISC
        Route::get('/profile', 'AdminNavigationController@goToProfilePage')->name('admin.show.profile');
        Route::put('/profile/update', 'AdminAccountController@updateProfile')->name('admin.profile.update');
        Route::put('/profile/password-update', 'AdminAccountController@updatePassword')->name('admin.password.update');
    });
});

// AJAX ROUTE????
Route::post('/availability/get', 'WebNavigationController@getRoomsAvailability');

// FOR TESTING OF PDF
Route::get('/test', 'WebNavigationController@test');