<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Core\Plugin;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

/**
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 */
Router::defaultRouteClass(DashedRoute::class);

Router::scope('/', function (RouteBuilder $builder) {
    /**
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, src/Template/Pages/home.ctp)...
     */
    $builder->connect('/', ['controller' => 'Home', 'action' => 'index']);

    $builder->connect('invite/*', ['controller' => 'Home', 'action' => 'invite']);

    $builder->connect('/privacy-policy', ['controller' => 'Home', 'action' => 'page', 'privacy-policy']);
    $builder->connect('/terms-and-condition', ['controller' => 'Home', 'action' => 'page', 'terms-and-condition']);


    $builder->connect('/terms-and-condition', ['controller' => 'Home', 'action' => 'page', 'terms-and-condition']);

    /* $builder->connect('/page/about-us', ['controller' => 'Home', 'action' => 'page2', 'about-us']);
    $builder->connect('/page/contact-us', ['controller' => 'Home', 'action' => 'page2', 'contact-us']);
    $builder->connect('/page/terms-and-condition', ['controller' => 'Home', 'action' => 'page2', 'terms-and-condition']);
    $builder->connect('/page/privacy-policy', ['controller' => 'Home', 'action' => 'page2', 'privacy-policy']);

    $builder->connect('/page/offers', ['controller' => 'Home', 'action' => 'page2', 'offers']);
    $builder->connect('/page/promotions', ['controller' => 'Home', 'action' => 'page2', 'promotions']);
    $builder->connect('/page/support', ['controller' => 'Home', 'action' => 'page2', 'support']);
    $builder->connect('/page/view-my-team', ['controller' => 'Home', 'action' => 'page2', 'view-my-team']);
    $builder->connect('/page/leaderboard', ['controller' => 'Home', 'action' => 'page2', 'leaderboard']);
    $builder->connect('/page/tutorial', ['controller' => 'Home', 'action' => 'page2', 'tutorial']);

    $builder->connect('/page/survey', ['controller' => 'Home', 'action' => 'page2', 'survey']);
    $builder->connect('/page/profile', ['controller' => 'Home', 'action' => 'page2', 'profile']);
    $builder->connect('/page/more', ['controller' => 'Home', 'action' => 'page2', 'more']);
    $builder->connect('/page/redeem', ['controller' => 'Home', 'action' => 'page2', 'redeem']);
    $builder->connect('/page/mymatches', ['controller' => 'Home', 'action' => 'page2', 'mymatches']); */

    $builder->connect('/match-questions/*', ['controller' => 'Home', 'action' => 'matchquestions']);
    $builder->connect('/match-result/*', ['controller' => 'Home', 'action' => 'matchresult']);





    $builder->connect('/api-user-registration', ['controller' => 'Api', 'action' => 'signup']);

    $builder->connect('/api-temp-registration', ['controller' => 'Api', 'action' => 'tempsignup']);
    $builder->connect('/api-check-registration', ['controller' => 'Api', 'action' => 'checksignuptype']);
    $builder->connect('/api-check-account', ['controller' => 'Api', 'action' => 'checkaccount']);


    $builder->connect('/api-user-login', ['controller' => 'Api', 'action' => 'login']);
    $builder->connect('/api-user-login-otp', ['controller' => 'Api', 'action' => 'loginOtp']);
    $builder->connect('/api-banner-list', ['controller' => 'Api', 'action' => 'bannerlist']);
    $builder->connect('/api-home-upcoming-list', ['controller' => 'Api', 'action' => 'homeupcoming']);
    $builder->connect('/api-user-profile', ['controller' => 'Api', 'action' => 'userprofile']);
    $builder->connect('/api-update-user-profile', ['controller' => 'Api', 'action' => 'editProfile']);
    $builder->connect('/api-add-kyc', ['controller' => 'Api', 'action' => 'addkyc']);
    $builder->connect('/api-get-bank-info', ['controller' => 'Api', 'action' => 'getIfscInfo']);
    $builder->connect('/api-add-payment-wallet', ['controller' => 'Api', 'action' => 'addPaymentWallet']);
    $builder->connect('/api-user-wallet', ['controller' => 'Api', 'action' => 'userwallet']);

    $builder->connect('/api-verify-otp', ['controller' => 'Api', 'action' => 'verifyOtp']);
    $builder->connect('/api-resend-otp', ['controller' => 'Api', 'action' => 'resendOtp']);

    $builder->connect('/api-forgot-password', ['controller' => 'Api', 'action' => 'forgotPassword']);
    $builder->connect('/api-change-password', ['controller' => 'Api', 'action' => 'changePasword']);
    $builder->connect('/api-upload-image', ['controller' => 'Api', 'action' => 'uploadUserImage']);
    
    $builder->connect('/api-transactions', ['controller' => 'Api', 'action' => 'transactions']);
    $builder->connect('/api-notification-list', ['controller' => 'Api', 'action' => 'notificationList']);
    $builder->connect('/api-get-referral-code', ['controller' => 'Api', 'action' => 'getreferalcode']);

    $builder->connect('/api-deposit', ['controller' => 'Api', 'action' => 'depositWallet']);
    $builder->connect('/api-add-withdraw-request', ['controller' => 'Api', 'action' => 'addWithdrawRequest']);
    $builder->connect('/api-check-withdraw-status', ['controller' => 'Api', 'action' => 'checkWithdrawStatus']);
    $builder->connect('/api-wallet-transfer-request', ['controller' => 'Api', 'action' => 'walletTransferRequest']);

    $builder->connect('/api-add-account', ['controller' => 'Api', 'action' => 'addAccount']);
    $builder->connect('/api-account-list', ['controller' => 'Api', 'action' => 'accountList']);
    $builder->connect('/api-account-detail', ['controller' => 'Api', 'action' => 'accountDetail']);

    $builder->connect('/api-get-user-name', ['controller' => 'Api', 'action' => 'getusername']);
    $builder->connect('/api-get-deposit-details', ['controller' => 'Api', 'action' => 'getdepositdetails']);
    $builder->connect('/api-getappsetting', ['controller' => 'Api', 'action' => 'getappsetting']);
    $builder->connect('/api-getusersetting', ['controller' => 'Api', 'action' => 'getusersetting']);
    $builder->connect('/api-updateusersetting', ['controller' => 'Api', 'action' => 'updateusersetting']);


    $builder->connect('/api-home-active', ['controller' => 'Api', 'action' => 'homeactive']);
    $builder->connect('/api-home-upcoming', ['controller' => 'Api', 'action' => 'homeupcoming']);
    $builder->connect('/api-draft-team', ['controller' => 'Api', 'action' => 'draftteam']);
    $builder->connect('/api-draft-player', ['controller' => 'Api', 'action' => 'draftplayer']);

    $builder->connect('/api-mycontest-upcoming', ['controller' => 'Api', 'action' => 'mycontestupcoming']);
    $builder->connect('/api-mycontest-live', ['controller' => 'Api', 'action' => 'mycontestlive']);
    $builder->connect('/api-mycontest-completed', ['controller' => 'Api', 'action' => 'mycontestcompleted']);

    $builder->connect('/api-contest-detail', ['controller' => 'Api', 'action' => 'contestDetail']);
    $builder->connect('/api-playerdata', ['controller' => 'Api', 'action' => 'playerData']);
    

    $builder->connect('/api-get-countries', ['controller' => 'Api', 'action' => 'getCountryList']);
    $builder->connect('/api-get-states', ['controller' => 'Api', 'action' => 'getStateList']);
    $builder->connect('/api-get-cities', ['controller' => 'Api', 'action' => 'getCityList']);
    $builder->connect('/api-user-selectteam', ['controller' => 'Api', 'action' => 'selectteam']);
    $builder->connect('/api-player-selected', ['controller' => 'Api', 'action' => 'playerselected']);
    $builder->connect('/api-before-join-draft', ['controller' => 'Api', 'action' => 'joinContestWalletAmount']);
    $builder->connect('/api-editplayer-selected', ['controller' => 'Api', 'action' => 'editplayerselected']);
    $builder->connect('/api-user-transaction', ['controller' => 'Api', 'action' => 'usertransaction']);
    $builder->connect('/api-userdata', ['controller' => 'Api', 'action' => 'userdata']);


    $builder->connect('/api-get-faq', ['controller' => 'Api', 'action' => 'getFaq']);
    $builder->connect('/api-contact-us', ['controller' => 'Api', 'action' => 'contactus']);
    $builder->connect('/page/*', ['controller' => 'Api', 'action' => 'getPage']);
    


	/*
    $routes->connect(
        '/blog/:id-:email', // E.g. /blog/3-CakePHP_Rocks
        ['controller' => 'pages', 'action' => 'getdataslug']
    )
    ->setPass(['id','email'])
    ->setPatterns([
        'id' => '[0-9]+',
    ]);
    */

    //$routes->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);

    /**
     * ...and connect the rest of 'Pages' controller's URLs.
     */
    //$routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);

    /**
     * Connect catchall routes for all controllers.
     *
     * Using the argument `DashedRoute`, the `fallbacks` method is a shortcut for
     *    `$routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'DashedRoute']);`
     *    `$routes->connect('/:controller/:action/*', [], ['routeClass' => 'DashedRoute']);`
     *
     * Any route class can be used with this method, such as:
     * - DashedRoute
     * - InflectedRoute
     * - Route
     * - Or your own route class
     *
     * You can remove these routes once you've connected the
     * routes you want in your application.
     */
    $builder->fallbacks(DashedRoute::class);
});

/*
 * Set admin prefix
 */

Router::prefix('admin', function ($routes) {
    // All routes here will be prefixed with `/admin`
    // And have the prefix => admin route element added.
    $routes->connect('/', ['controller' => 'Users', 'action' => 'login']);
    $routes->fallbacks(DashedRoute::class);
});
/**
 * Load all plugin routes. See the Plugin documentation on
 * how to customize the loading of plugin routes.
 */
Plugin::routes();
