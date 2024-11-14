<?php

use App\Http\Controllers\CinetPayController;
use App\Http\Controllers\NepalstePaymnetController;
use App\Http\Controllers\PaiementProController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\CoingatePaymentController;
use App\Http\Controllers\CompanySettingController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CustomController;
use App\Http\Controllers\FlutterwavePaymentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MercadoPaymentController;
use App\Http\Controllers\MolliePaymentController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\PaystackPaymentController;
use App\Http\Controllers\PaytmPaymentController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PlanRequestController;
use App\Http\Controllers\QuickViewController;
use App\Http\Controllers\RazorpayPaymentController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\SkrillPaymentController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\WidgetController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ToyyibpayController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\PayfastController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserlogController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\bankTransferController;
use App\Http\Controllers\sspayController;
use App\Http\Controllers\IyziPayController;
use App\Http\Controllers\PaytabController;
use App\Http\Controllers\BenefitPaymentController;
use App\Http\Controllers\CashfreeController;
use App\Http\Controllers\AamarpayController;
use App\Http\Controllers\AuthorizeNetPaymentController;
use App\Http\Controllers\FedapayController;
use App\Http\Controllers\KhaltiPaymentController;
use App\Http\Controllers\PaytrController;
use App\Http\Controllers\PaymentWallController;
use App\Http\Controllers\YooKassaController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\OzowPaymentController;
use App\Http\Controllers\XenditPaymentController;
use App\Http\Controllers\PayHereController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\TapPaymentController;

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


require __DIR__ . '/auth.php';
Route::any('/cookie-consent', [SettingController::class, 'CookieConsent'])->name('cookie-consent');
Route::any('/', [HomeController::class, 'landingPage']);

Route::any('test', [SiteController::class, 'test'])->name('test');
Route::any('site/dashboard/link/{id}/{type?}/{lang}', [SiteController::class, 'site_dashboard_link'])->name('site.dashboard.link');
Route::any('get-chart', [ChartController::class, 'get_chart_data'])->name('get-chart');
Route::any('get-chart-new', [ChartController::class, 'get_chart_data_new'])->name('get-chart-new');
Route::any('genrate_accesstoken', [AnalyticsController::class, 'genrate_accesstoken'])->name('genrate_accesstoken');
Route::any('active-page', [ChartController::class, 'active_page'])->name('active-page');
Route::any('live-user', [ChartController::class, 'live_user'])->name('live-user');
Route::any('quickview/link/{id}/{lang}', [QuickViewController::class, 'quickview_link'])->name('quickview.share.link');
Route::any('quick-view-data', [QuickViewController::class, 'quick_view_data'])->name('quick-view-data');

Route::any('site/analyse/link/{id}/{type?}/{lang}', [SiteController::class, 'site_analyse_link'])->name('site.analyse.link');
Route::any('get-channel-data', [AnalyticsController::class, 'get_channel_data'])->name('get-channel-data');
Route::any('get-audience-data', [AnalyticsController::class, 'get_audience_data'])->name('get-audience-data');
Route::any('get-page-data', [AnalyticsController::class, 'get_page_data'])->name('get-page-data');
Route::any('get-seo-data', [AnalyticsController::class, 'get_seo_data'])->name('get-seo-data');
Route::any('custom-share-chart', [CustomController::class, 'custom_share_chart'])->name('custom-share-chart');



Route::any('/verify-email/{lang?}', [EmailVerificationPromptController::class, 'showverifyform'])->middleware(['XSS', 'auth'])
    ->name('verification.notice');
Route::any('add-site', [AnalyticsController::class, 'index'])->name('add-site')->middleware(['auth', 'XSS']);


Route::group(['middleware' => ['verified'],], function () {

    Route::any('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard')->middleware(['auth', 'XSS']);
    Route::any('save-json', [HomeController::class, 'save_json'])->name('save-json');
    Route::any('aletr', [AlertController::class, 'index'])->name('aletr')->middleware(['auth', 'XSS']);
    Route::any('save-aletr', [AlertController::class, 'create'])->name('save-aletr')->middleware(['auth', 'XSS']);
    Route::any('aletr/history', [AlertController::class, 'history'])->name('aletr-history')->middleware(['auth', 'XSS']);
    Route::any('delete/aletr/history/{id}', [AlertController::class, 'delete_history'])->name('delete-alert-history')->middleware(['auth', 'XSS']);

    Route::any('report/history', [ReportController::class, 'report_history'])->name('report-history')->middleware(['auth', 'XSS']);

    Route::any('delete/report/history/{id}', [ReportController::class, 'delete_history'])->name('delete-report-history')->middleware(['auth', 'XSS']);
    Route::any('show/report/{id}', [ReportController::class, 'show_history'])->name('show-report')->middleware(['auth', 'XSS']);

    Route::any('users', [UsersController::class, 'index'])->name('users')->middleware(['auth', 'XSS']);
    Route::any('save-user', [UsersController::class, 'save_user'])->name('save-user')->middleware(['auth', 'XSS']);
    Route::any('update-user', [UsersController::class, 'update_user'])->name('update-user')->middleware(['auth', 'XSS']);
    Route::any('delete-user/{id}', [UsersController::class, 'delete_user'])->name('delete-user')->middleware(['auth', 'XSS']);

    Route::any('plans', [PlanController::class, 'index'])->name('plans')->middleware(['auth', 'XSS']);
    Route::any('save-plan', [PlanController::class, 'save_plan'])->name('save-plan')->middleware(['auth', 'XSS']);
    Route::any('edit-plan/{id}', [PlanController::class, 'edit_plan'])->name('edit-plan')->middleware(['auth', 'XSS']);
    Route::DELETE('plans/{id}', [PlanController::class, 'destroy'])->name('plans.destroy')->middleware(['auth', 'XSS']);
    Route::post('plan-disable', [PlanController::class, 'planDisable'])->name('plan.disable')->middleware(['auth', 'XSS']);
    Route::any('plans-request', [PlanRequestController::class, 'index'])->name('plans-request')->middleware(['auth', 'XSS']);
    Route::any('/take-plan-trial/{plan_id}', [PlanController::class, 'take_plan_trial'])->name('take.plan.trial')->middleware(['auth', 'XSS']);
    Route::any('request_send/{id}/{frequency?}', [PlanRequestController::class, 'userRequest'])->name('send.request')->middleware(['auth', 'XSS']);
    Route::any('request_cancel/{id}', [PlanRequestController::class, 'cancelRequest'])->name('request.cancel')->middleware(['auth', 'XSS']);

    Route::any('referral', [ReferralController::class, 'index'])->name('referral')->middleware(['auth', 'XSS']);
    Route::post('/referral-program/setting/store', [referralController::class, 'store'])->name('setting.store')->middleware(['auth', 'XSS',]);
    Route::post('referral/store', [ReferralController::class, 'payoutstore'])->name('payout.store')->middleware(['auth', 'XSS']);
    Route::post('referral/status', [ReferralController::class, 'storestatus'])->name('referral_store.status')->middleware(['auth', 'XSS']);
    Route::post('referral-settings', [ReferralController::class, 'savereferralSettings'])->name('referral.settings');

    Route::any('/payment/{frequency}/{code}', [PlanController::class, 'payment'])->name('payment')->middleware(['auth', 'XSS']);
    Route::any('request_response/{id}/{response}', [PlanRequestController::class, 'acceptRequest'])->name('response.request')->middleware(['auth', 'XSS']);
    Route::any('coupon', [CouponController::class, 'index'])->name('coupon')->middleware(['auth', 'XSS']);
    Route::any('save-coupon', [CouponController::class, 'save_coupon'])->name('save-coupon')->middleware(['auth', 'XSS']);
    Route::any('coupons.destroy/{id}', [CouponController::class, 'destroy_coupon'])->name('coupons.destroy')->middleware(['auth', 'XSS']);
    Route::any('edit-coupon/{id}', [CouponController::class, 'edit_coupon'])->name('edit-coupon')->middleware(['auth', 'XSS']);
    Route::any('coupons/{id}', [CouponController::class, 'show'])->name('coupons.show')->middleware(['auth', 'XSS']);


    Route::any('/company/settings', [CompanySettingController::class, 'settings'])->name('company.settings')->middleware(['auth', 'XSS']);
    Route::post('/company/settings', [CompanySettingController::class, 'settingsStore'])->name('company.settings.store')->middleware(['auth', 'XSS']);
    Route::post('/company/system_settings', [CompanySettingController::class, 'SystemsettingsStore'])->name('company.settings.system.store')->middleware(['auth', 'XSS']);
    Route::post('/company/email/settings', [CompanySettingController::class, 'emailSettingStore'])->name('company.email.settings.store')->middleware(['auth', 'XSS']);
    Route::any('/company/test/mail', [CompanySettingController::class, 'testmail'])->name('company.test.mail')->middleware(['auth', 'XSS']);
    Route::any('/company/test/mail/send', [CompanySettingController::class, 'testmailstore'])->name('company.test.email.send')->middleware(['auth', 'XSS']);


    Route::any('roles', [RoleController::class, 'index'])->name('roles.index')->middleware(['auth', 'XSS']);
    Route::any('create/roles', [RoleController::class, 'create'])->name('create.roles')->middleware(['auth', 'XSS']);
    Route::any('store/roles', [RoleController::class, 'store'])->name('store.roles')->middleware(['auth', 'XSS']);
    Route::any('edit/roles/{id}', [RoleController::class, 'edit'])->name('roles.edit')->middleware(['auth', 'XSS']);
    Route::any('destroy/roles/{id}', [RoleController::class, 'destroy'])->name('roles.destroy')->middleware(['auth', 'XSS']);
    Route::any('edit-user/{id}', [UsersController::class, 'edit_user'])->name('edit-use')->middleware(['auth', 'XSS']);

    Route::any('update/roles/{id}', [RoleController::class, 'update'])->name('roles.update')->middleware(['auth', 'XSS']);


    Route::resource('permissions', PermissionController::class)->middleware(['auth', 'XSS']);

    Route::post('resetpassword', [UsersController::class, 'resetPassword'])->name('reset.password')->middleware(['auth', 'XSS']);

    Route::any('/my-account', [UsersController::class, 'account'])->name('my.account')->middleware(['auth', 'XSS']);
    Route::post('/my-account', [UsersController::class, 'accountupdate'])->name('account.update')->middleware(['auth', 'XSS']);
    Route::post('/my-account/password', [UsersController::class, 'updatePassword'])->name('update.password')->middleware(['auth', 'XSS']);
    Route::delete('/my-account', [UsersController::class, 'deleteAvatar'])->name('delete.avatar')->middleware(['auth', 'XSS']);
    Route::get('home/{id}/login-with-admin', [UsersController::class, 'LoginWithAdmin'])->name('login.with.admin')->middleware('auth', 'XSS');
    Route::get('login-with-admin/exit', [UsersController::class, 'ExitAdmin'])->name('exit.admin')->middleware('auth', 'XSS');
    Route::get('company-info/{id}', [UsersController::class, 'CompnayInfo'])->name('company.info')->middleware('auth', 'XSS');
    Route::post('user-unable', [UsersController::class, 'UserUnable'])->name('user.unable');
    Route::any('settings', [SettingController::class, 'index'])->name('settings.index')->middleware(['auth', 'XSS']);
    Route::post('settings', [SettingController::class, 'store'])->name('settings.store')->middleware(['auth', 'XSS']);
    Route::post('email-settings', [SettingController::class, 'emailSettingStore'])->name('email.settings.store')->middleware(['auth', 'XSS']);
    Route::post('pusher-settings', [SettingController::class, 'pusherSettingStore'])->name('pusher.settings.store')->middleware(['auth', 'XSS']);
    Route::any('test', [SettingController::class, 'testEmail'])->name('test.email')->middleware(['auth', 'XSS']);
    Route::post('test/send', [SettingController::class, 'testEmailSend'])->name('test.email.send')->middleware(['auth', 'XSS']);
    Route::post('payment-setting', [SettingController::class, 'savePaymentSettings'])->name('payment.setting')->middleware(['auth', 'XSS']);
    Route::post('storage-settings', [SettingController::class, 'storageSettingStore'])->name('storage.setting.store')->middleware(['auth', 'XSS']);
    Route::post('setting/seo', [SettingController::class, 'saveSEOSettings'])->name('seo.settings.store')->middleware(['auth', 'XSS']);
    Route::post('cookie-setting', [SettingController::class, 'saveCookieSettings'])->name('cookie.setting');
    Route::post('setting/recaptcha', [SettingController::class, 'recaptcha'])->name('recaptcha.settings.store')->middleware(['auth', 'XSS']);

    Route::any('/apply-coupon', [CouponController::class, 'applyCoupon'])->name('apply.coupon')->middleware(['auth', 'XSS']);

    Route::post('/stripe', [StripePaymentController::class, 'stripePost'])->name('stripe.post')->middleware(['auth', 'XSS']);

    Route::get('/refund/{id}/{user_id}', [StripePaymentController::class, 'refund'])->name('order.refund')->middleware(['auth', 'XSS']);

    Route::post('plan-pay-with-paypal', [PaypalController::class, 'planPayWithPaypal'])->name('plan.pay.with.paypal')->middleware(['auth', 'XSS']);
    Route::any('{id}/plan-get-payment-status', [PaypalController::class, 'planGetPaymentStatus'])->name('plan.get.payment.status')->middleware(['auth', 'XSS']);
    Route::any('/orders', [StripePaymentController::class, 'index'])->name('order.index');
    Route::post('/plan-pay-with-paystack', [PaystackPaymentController::class, 'planPayWithPaystack'])->name('plan.pay.with.paystack')->middleware(['auth', 'XSS']);
    Route::any('/plan/paystack/{pay_id}/{plan_id}', [PaystackPaymentController::class, 'getPaymentStatus'])->name('plan.paystack');

    Route::post('/plan-pay-with-flaterwave', [FlutterwavePaymentController::class, 'planPayWithFlutterwave'])->name('plan.pay.with.flaterwave')->middleware(['auth', 'XSS']);
    Route::any('/plan/flaterwave/{txref}/{plan_id}', [FlutterwavePaymentController::class, 'getPaymentStatus'])->name('plan.flaterwave');

    Route::post('/plan-pay-with-razorpay', [RazorpayPaymentController::class, 'planPayWithRazorpay'])->name('plan.pay.with.razorpay')->middleware(['auth', 'XSS']);
    Route::any('/plan/razorpay/{txref}/{plan_id}', [RazorpayPaymentController::class, 'getPaymentStatus'])->name('plan.razorpay');

    Route::post('/plan-pay-with-toyyibpay', [ToyyibpayController::class, 'charge'])->name('plan-pay-with-toyyibpay')->middleware(['auth', 'XSS']);


    Route::any('/plan/toyyibpay/{planId}/{getAmount?}/{couponCode?}/{frequency?}', [ToyyibpayController::class, 'status'])->name('plan.status');


    Route::post('/plan-pay-with-mercado', [MercadoPaymentController::class, 'planPayWithMercado'])->name('plan.pay.with.mercado')->middleware(['auth', 'XSS']);
    Route::any('/plan/mercado/{plan_id}', [MercadoPaymentController::class, 'getPaymentStatus'])->name('plan.mercado');

    Route::post('/plan-pay-with-mollie', [MolliePaymentController::class, 'planPayWithMollie'])->name('plan.pay.with.mollie')->middleware(['auth', 'XSS']);
    Route::any('/plan/mollie/{plan}', [MolliePaymentController::class, 'getPaymentStatus'])->name('plan.mollie');

    Route::post('/plan-pay-with-skrill', [SkrillPaymentController::class, 'planPayWithSkrill'])->name('plan.pay.with.skrill')->middleware(['auth', 'XSS']);
    Route::any('/plan/skrill/{plan}', [SkrillPaymentController::class, 'getPaymentStatus'])->name('plan.skrill');

    Route::post('/plan-pay-with-coingate', [CoingatePaymentController::class, 'planPayWithCoingate'])->name('plan.pay.with.coingate')->middleware(['auth', 'XSS']);
    Route::any('/plan/coingate/{plan}', [CoingatePaymentController::class, 'getPaymentStatus'])->name('plan.coingate');

    Route::any('oauth2callback', [AnalyticsController::class, 'oauth2callback'])->name('oauth2callback')->middleware(['auth', 'XSS']);
    Route::any('channel', [AnalyticsController::class, 'channel_analytics'])->name('channel')->middleware(['auth', 'XSS']);

    Route::any('audience', [AnalyticsController::class, 'audience_analytics'])->name('audience')->middleware(['auth', 'XSS']);
    Route::any('page', [AnalyticsController::class, 'page_analytics'])->name('page')->middleware(['auth', 'XSS']);
    Route::any('seo-analysis', [AnalyticsController::class, 'seo_analysis'])->name('seo-analysis')->middleware(['auth', 'XSS']);
    Route::any('new', [AnalyticsController::class, 'new'])->name('new')->middleware(['auth', 'XSS']);
    Route::any('getProperty', [SiteController::class, 'getProperty'])->name('getProperty')->middleware(['auth', 'XSS']);
    Route::any('getView', [SiteController::class, 'getView'])->name('getView')->middleware(['auth', 'XSS']);
    Route::any('save-site', [SiteController::class, 'save_site'])->name('save-site')->middleware(['auth', 'XSS']);
    Route::any('site-standard/{id}', [SiteController::class, 'site_standard'])->name('site-standard')->middleware(['auth', 'XSS']);
    Route::any('manage-site', [SiteController::class, 'manage_site'])->name('manage-site')->middleware(['auth', 'XSS']);
    Route::any('site-list', [SiteController::class, 'site_list'])->name('site-list')->middleware(['auth', 'XSS']);
    Route::any('edit-site/{id}', [SiteController::class, 'edit_site'])->name('edit-site')->middleware(['auth', 'XSS']);
    Route::any('delete-site/{id}', [SiteController::class, 'delete_site'])->name('delete-site')->middleware(['auth', 'XSS']);
    Route::any('share/setting/{type}', [SiteController::class, 'site_share_setting'])->name('save-share-setting')->middleware(['auth', 'XSS']);
    Route::any('edit/share/setting/{id}/{type}', [SiteController::class, 'show_site_share_setting'])->name('edit-share-setting')->middleware(['auth', 'XSS']);

    Route::any('quickview/share/setting/', [QuickViewController::class, 'quickview_share_setting'])->name('save-quickview-setting')->middleware(['auth', 'XSS']);
    Route::any('quickview/edit/share/setting/', [QuickViewController::class, 'show_quickview_share_setting'])->name('edit-quickview-setting')->middleware(['auth', 'XSS']);

    Route::post('company/slack-settings', [CompanySettingController::class, 'saveSlackSettings'])->name('company.slack.settings');
    Route::post('company/report-settings', [CompanySettingController::class, 'savereportSettings'])->name('company.report.settings');




    Route::post('payfast-plan', [PayfastController::class, 'index'])->name('payfast.payment')->middleware(['auth']);
    Route::get('payfast-plan/{success}', [PayfastController::class, 'success'])->name('payfast.payment.success')->middleware(['auth']);


    Route::any('banktransfer', [bankTransferController::class, 'status'])->name('banktransfer.post')->middleware(['auth', 'XSS']);
    Route::any('banktransfer/{id}', [bankTransferController::class, 'edit'])->name('banktransfer.edit')->middleware(['auth', 'XSS']);
    Route::any('banktransfer-update/{id}', [bankTransferController::class, 'update'])->name('banktransfer.update')->middleware(['auth', 'XSS']);
    Route::get('status/{id}/{response}/{frequency}', [bankTransferController::class, 'acceptRequest'])->name('response.status')->middleware(['auth', 'XSS',]);
    Route::any('order-delete/{id}', [bankTransferController::class, 'destroy'])->name('orders.destroy')->middleware(['auth', 'XSS',]);

    
    
    
    

    Route::any('widget', [WidgetController::class, 'show_widget'])->name('widget')->middleware(['auth', 'XSS']);
    Route::any('save-widget', [WidgetController::class, 'save_widget'])->name('save-widget')->middleware(['auth', 'XSS']);
    Route::any('widget-data', [WidgetController::class, 'widget_data'])->name('widget-data')->middleware(['auth', 'XSS']);
    Route::any('edit-widget', [WidgetController::class, 'edit_widget_data'])->name('edit-widget')->middleware(['auth', 'XSS']);
    Route::any('quick-view/{id}', [QuickViewController::class, 'quick_view'])->name('quick-view')->middleware(['auth', 'XSS']);
    Route::any('edit-quick-view-data', [QuickViewController::class, 'edit_quick_view_data'])->name('edit-quick-view-data')->middleware(['auth', 'XSS']);
    Route::any('save-quick-view-data', [QuickViewController::class, 'save_quick_view_data'])->name('save-quick-view-data')->middleware(['auth', 'XSS']);
    Route::any('custom-dashboard', [CustomController::class, 'custom_dashboard'])->name('custom-dashboard')->middleware(['auth', 'XSS']);
    Route::any('get-dimension', [CustomController::class, 'get_dimension'])->name('get-dimension')->middleware(['auth', 'XSS']);
    Route::any('custom-chat', [CustomController::class, 'custom_chart'])->name('custom-chat')->middleware(['auth', 'XSS']);

    

    Route::post('plan-pay-with-paytab', [PaytabController::class, 'planPayWithpaytab'])->middleware(['auth'])->name('plan.pay.with.paytab');
    Route::any('plan-paytab-success/', [PaytabController::class, 'PaytabGetPayment'])->middleware(['auth'])->name('plan.paytab.success');

    Route::any('/payment/initiate', [BenefitPaymentController::class, 'initiatePayment'])->name('benefit.initiate');
    Route::any('call_back', [BenefitPaymentController::class, 'call_back'])->name('benefit.call_back');
    
    Route::post('cashfree/payments/store', [CashfreeController::class, 'cashfreePaymentStore'])->name('cashfree.payment');
    Route::any('cashfree/payments/success', [CashfreeController::class, 'cashfreePaymentSuccess'])->name('cashfreePayment.success');
    
    Route::post('/aamarpay/payment', [AamarpayController::class, 'pay'])->name('pay.aamarpay.payment');
    Route::any('/aamarpay/success/{data}', [AamarpayController::class, 'aamarpaysuccess'])->name('pay.aamarpay.success');

    Route::post('/planpayment', [PaymentWallController::class, 'planpay'])->name('paymentwall')->middleware(['auth', 'XSS']);
    Route::post('/paymentwall-payment/{plan}', [PaymentWallController::class, 'planPayWithPaymentWall'])->name('paymentwall.payment')->middleware(['auth', 'XSS']);
    Route::get('/plan/error/{flag}', [PaymentWallController::class, 'planerror'])->name('error.plan.show');
});
Route::get('plans/plans-trial/{id}', [PlanController::class, 'PlanTrial'])->name('plans.trial');

Route::any('/plan/change/{id}', [UsersController::class, 'changePlan'])->name('users.change.plan')->middleware(['auth', 'XSS']);
Route::any('user/{id}/plan/{pid}/{duration}', [UsersController::class, 'manuallyActivatePlan'])->name('manually.activate.plan')->middleware(['auth', 'XSS']);

Route::any('/stripe-payment-status', [StripePaymentController::class, 'planGetStripePaymentStatus'])->name('stripe.payment.status');

Route::get('user-login/{id}', [UsersController::class, 'LoginManage'])->name('user.login');
Route::post('/plan-pay-with-paytm', [PaytmPaymentController::class, 'planPayWithPaytm'])->name('plan.pay.with.paytm')->middleware(['auth', 'XSS']);
Route::post('/plan/paytm/{plan}', [PaytmPaymentController::class, 'getPaymentStatus'])->name('plan.paytm');
Route::any('manage-language/{lang}', [LanguageController::class, 'manageLanguage'])->name('manage.language')->middleware(['auth', 'XSS']);
Route::any('create-language', [LanguageController::class, 'createLanguage'])->name('create.language')->middleware(['auth', 'XSS']);
Route::post('store-language', [LanguageController::class, 'storeLanguage'])->name('store.language')->middleware(['auth', 'XSS']);
Route::delete('/lang/{lang}', [LanguageController::class, 'destroyLang'])->name('lang.destroy')->middleware(['auth', 'XSS']);
Route::post('store-language-data/{lang}', [LanguageController::class, 'storeLanguageData'])->name('store.language.data')->middleware(['auth', 'XSS']);
Route::post('disable-language', [LanguageController::class, 'disableLang'])->name('disablelanguage')->middleware(['auth', 'XSS']);
Route::any('/super_admin/change_lang/{lang}', [LanguageController::class, 'changeLangAdmin'])->name('change_lang_admin')->middleware(['auth', 'XSS']);


Route::post('setting/cache', [SettingController::class, 'cacheSettings'])->name('cache.settings')->middleware(['auth', 'XSS']);


Route::get('userlogs', [UserlogController::class, 'index'])->name('userlog.index')->middleware(['auth', 'XSS']);
Route::get('userlogsView/{id}', [UserlogController::class, 'view'])->name('userlog.view')->middleware(['auth', 'XSS']);
Route::delete('userlogsdelete/{id}', [UserlogController::class, 'destroy'])->name('userlog.destroy')->middleware(['auth', 'XSS']);
Route::get('userlogs/getlogdetail/{id}', [UserlogController::class, 'getlogDetail'])->name('userlog.getlogdetail');

Route::post('iyzipay/prepare', [IyziPayController::class, 'initiatePayment'])->name('iyzipay.payment.init');
Route::post('iyzipay/callback/plan/{id}/{amount}/{frequency}/{coupan_code?}', [IyzipayController::class, 'iyzipayCallback'])->name('iyzipay.payment.callback');

Route::post('sspay-prepare-plan', [sspayController::class, 'SspayPaymentPrepare'])->middleware(['auth'])->name('sspay.prepare.plan');
Route::get('sspay-payment-plan/{plan_id}/{amount}/{frequency}/{couponCode?}', [sspayController::class, 'SspayPlanGetPayment'])->middleware(['auth'])->name('plan.sspay.callback');

Route::post('/paytr/payment', [PaytrController::class, 'PlanpayWithPaytr'])->name('pay.paytr.payment');
Route::any('/paytr/success', [PaytrController::class, 'paytrsuccess'])->name('pay.paytr.success');

Route::get('/plan/yookassa/payment', [YooKassaController::class, 'planPayWithYooKassa'])->name('plan.pay.with.yookassa');
Route::get('/plan/yookassa/{plan}', [YooKassaController::class, 'planGetYooKassaStatus'])->name('plan.get.yookassa.status');

Route::any('/midtrans', [MidtransController::class, 'planPayWithMidtrans'])->name('plan.get.midtrans');
Route::any('/midtrans/callback', [MidtransController::class, 'planGetMidtransStatus'])->name('plan.get.midtrans.status');

Route::post('plan-payhere-payment', [PayHereController::class, 'planPayWithPayHere'])->name('plan.payhere.payment');
Route::get('/plan-payhere-status', [PayHereController::class, 'planGetPayHereStatus'])->name('payhere.status');

Route::any('/xendit/payment', [XenditPaymentController::class, 'planPayWithXendit'])->name('plan.xendit.payment');
Route::any('/xendit/payment/status', [XenditPaymentController::class, 'planGetXenditStatus'])->name('plan.xendit.status');

Route::post('plan-pay-with/paiementpro', [PaiementProController::class, 'planPayWithpaiementpro'])->name('plan.pay.with.paiementpro');
Route::get('plan-get-paiementpro-status/', [PaiementProController::class, 'planGetpaiementproStatus'])->name('paiementpro.status');

Route::post('/nepalste/payment', [NepalstePaymnetController::class, 'planPayWithnepalste'])->name('plan.pay.with.nepalste');
Route::get('nepalste/status/', [NepalstePaymnetController::class, 'planGetNepalsteStatus'])->name('nepalste.status');
Route::get('nepalste/cancel/', [NepalstePaymnetController::class, 'planGetNepalsteCancel'])->name('nepalste.cancel');

Route::post('/plan/company/payment', [CinetPayController::class, 'planPayWithCinetPay'])->name('plan.pay.with.cinetpay');
Route::post('/plan/company/payment/return', [CinetPayController::class, 'planCinetPayReturn'])->name('cinetpay.status');

Route::post('/plan/company/fedapay', [FedapayController::class, 'planPayWithFedapay'])->name('plan.pay.with.fedapay');
Route::any('plan-get-fedapay-status/{plan_id}', [FedapayController::class, 'planGetFedapayStatus'])->name('fedapay.status');

Route::post('/tap/payment', [TapPaymentController::class, 'planPayWithTap'])->name('plan.with.tap');
Route::get('tap/status/', [TapPaymentController::class, 'planTapStatus'])->name('tap.status');
Route::get('cinetpay/cancel/', [TapPaymentController::class, 'planGetcinetpayCancel'])->name('cinetpay.cancel');

Route::post('/authorizenet/payment', [AuthorizeNetPaymentController::class, 'planPayWithAuthorizeNet'])->name('plan.with.authorizenet');
Route::any('authorizenet/status/', [AuthorizeNetPaymentController::class, 'planAuthorizeNetStatus'])->name('authorizenet.status');
Route::get('authorizenet/cancel/', [AuthorizeNetPaymentController::class, 'planGetauthorizenetCancel'])->name('authorizenet.cancel');

Route::post('/ozow/payment', [OzowPaymentController::class, 'planPayWithozow'])->name('plan.with.ozow');
Route::get('ozow/status/', [OzowPaymentController::class, 'planGetozowStatus'])->name('ozow.status');
Route::get('ozow/cancel/', [OzowPaymentController::class, 'planGetozowCancel'])->name('ozow.cancel');

Route::post('/khalti/payment', [KhaltiPaymentController::class, 'planPayWithkhalti'])->name('plan.with.khalti');
Route::post('khalti/status/', [KhaltiPaymentController::class, 'planKhaltiStatus'])->name('khalti.status');
Route::get('khalti/cancel/', [KhaltiPaymentController::class, 'planGetkhaltiCancel'])->name('khalti.cancel');
