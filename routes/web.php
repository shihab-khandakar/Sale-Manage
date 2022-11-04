<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => "api/", "middleware" => ["cros"]], function () use ($router) {
    $router->group(["prefix" => "user/"], function () use ($router) {
        // $router->post("register/", 'AuthController@register');
        $router->post("login/", 'AuthController@login');
        $router->post("logout/", 'AuthController@logout');

        // register route
        $router->group(['namespace' => 'Register'], function () use ($router) {
            $router->post("register/", 'RegisterController@register');

            $router->group(["middleware" => "auth"], function () use ($router) {
                $router->patch("{id}/update", 'RegisterController@update');
                $router->delete("{id}/delete", 'RegisterController@userDelete');
            });
        });

    });



    $router->group(['prefix' => "user/", "middleware" => "auth"], function () use ($router) {
        $router->get("/me", 'AuthController@me');

        $router->group(['namespace' => 'Dashboard\User'], function () use ($router) {
            // user account route here
            $router->post("accounts/store/", 'AccountController@store');
            $router->post("accounts/{id}/update/", 'AccountController@update');
            $router->get("accounts/{id}/information/", 'AccountController@accountInformation');
            $router->get("/list/{lavel}/", 'UserManageController@index');

        });
    });

    $router->group(["middleware" => "auth", 'namespace' => 'Dashboard\RolePermission'], function () use ($router) {
        // permission route here
        $router->post("permissions/store/", 'PermissionController@store');
        $router->patch("permissions/{id}/update/", 'PermissionController@update');

        // role route here
        $router->post("roles/store/", 'RoleController@store');
        $router->get("roles/retrive/", 'RoleController@retrive');
        $router->patch("roles/{id}/update/", 'RoleController@update');
    });


    $router->group(["middleware" => "auth", 'namespace' => 'Dashboard\Profile'], function () use ($router) {
        // profile route here
        $router->post("profiles/store/", 'ProfileController@store');
        $router->post("profiles/{id}/update/", 'ProfileController@update');
    });


    $router->group(["middleware" => "auth", 'namespace' => 'Dashboard\CategoryBrand'], function () use ($router) {
        // category route here
        $router->post("categories/store/", 'CategoryController@store');
        $router->post("categories/{id}/update/", 'CategoryController@update');

        // brand route here
        $router->post("brands/store/", 'BrandController@store');
        $router->post("brands/{id}/update/", 'BrandController@update');
    });
    $router->group(["middleware" => "auth", 'namespace' => 'Dashboard\HierarchyManage'], function () use ($router) {
        // category route here
        $router->get("/hierarchies/index/", 'HierarchyManageController@index');
        $router->patch("/hierarchies/{id}/update/", 'HierarchyManageController@update');
    });

    $router->group(["middleware" => "auth", 'namespace' => 'Dashboard\Company_Warehouse'], function () use ($router) {
        // warehouse route here
        $router->post("warehouses/store/", 'WarehouseController@store');
        $router->patch("warehouses/{id}/update/", 'WarehouseController@update');

        // company-order route here
        $router->post("company_orders/store/", 'CompanyOrderController@store');
        $router->post("company_orders/{id}/update/", 'CompanyOrderController@update');
        $router->delete("company/orders/{id}/delete/", 'CompanyOrderController@destroy');

        // company-stock route here
        $router->get("company/warehouse/{id}/stocks/", 'CompanyStockController@stockIndex');
        $router->post("company_stocks/store/", 'CompanyStockController@store');
        $router->post("company_stocks/{id}/update/", 'CompanyStockController@update');
        $router->delete("company/stocks/{id}/delete/", 'CompanyStockController@destroy');

        // dealer-order route here
        $router->post("dealer_orders/store/", 'DealerOrderController@store');
        $router->post("dealer_orders/{id}/update/", 'DealerOrderController@update');
        $router->delete("dealer/orders/{id}/delete/", 'DealerOrderController@destroy');

        // dealer-stock route here
        $router->post("dealer_stocks/store/", 'DealerStockController@store');
        $router->post("dealer_stocks/{id}/update/", 'DealerStockController@update');
        $router->delete("dealer/stocks/{id}/delete/", 'DealerStockController@destroy');

        // sub-dealer-stock route here
        $router->post("sub_dealer_stocks/store/", 'SubDealerStockController@store');
        $router->post("sub_dealer_stocks/{id}/update/", 'SubDealerStockController@update');
        $router->delete("sub/dealer/stocks/{id}/delete/", 'SubDealerStockController@destroy');

        // sub-dealer-order route here
        $router->post("sub_dealer_orders/store/", 'SubDealerOrderController@store');
        $router->post("sub_dealer_orders/{id}/update/", 'SubDealerOrderController@update');
        $router->delete("sub/dealer/orders/{id}/delete/", 'SubDealerOrderController@destroy');

    });

    $router->group(["middleware" => "auth", 'namespace' => 'Dashboard\Product'], function () use ($router) {
        // Product route here
        $router->post("products/store/", 'ProductController@store');
        $router->post("products/{id}/update/", 'ProductController@update');
    });

    $router->group(["middleware" => "auth", 'namespace' => 'Dashboard\SidePartyInfo'], function () use ($router) {
        // side party info route here
        $router->post("side_party_infos/store/", 'SidePartyInfoController@store');
        $router->post("side_party_infos/{id}/update/", 'SidePartyInfoController@update');
    });

    $router->group(["middleware" => "auth", 'namespace' => 'Dashboard\Transport'], function () use ($router) {
        // Transport route here
        $router->post("transports/store/", 'TransportController@store');
        $router->post("transports/{id}/update/", 'TransportController@update');
    });

    $router->group(["middleware" => "auth", 'namespace' => 'Dashboard\Offer'], function () use ($router) {
        // offer route here
        $router->post("offers/store/", 'OfferController@store');
        $router->post("offers/{id}/update/", 'OfferController@update');
    });

    $router->group(["middleware" => "auth", 'namespace' => 'Dashboard\Bank'], function () use ($router) {
        // bank route here
        $router->post("banks/store/", 'BankController@store');
        $router->post("banks/{id}/update/", 'BankController@update');
    });

    // $router->group(["middleware" => "auth", 'namespace' => 'Dashboard\Benefit'], function () use ($router) {
    //     // Benefit route here
    //     // this module work has stoped
    //     $router->post("benefits/store/", 'BenefitController@store');
    //     $router->post("benefits/{id}/update/", 'BenefitController@update');
    // });

    // all retrive and delete here
    $router->group(["middleware" => ['auth', 'table_check', 'permissionCheck'], "prefix" => "common/", 'namespace' => 'Dashboard'], function () use ($router) {
        $router->get("{table:[a-z_]+}/{id:[0-9]+}/retrive/", 'CommonController@retrive');
        $router->delete("{table:[a-z_]+}/{id:[0-9]+}/destroy/", 'CommonController@destroy');
        //
        $router->get("{table:[a-z_]+}/", 'CommonController@index');
        $router->get("all/{table:[a-z_]+}/", 'CommonController@getAllData');
    });

    // all location here
    $router->group(["middleware" => ['auth', 'table_check', 'permissionCheck'], "prefix" => "location/"], function () use ($router) {
        $router->get("{table:[a-z_]+}/{id:[0-9]+}/retrive/", 'LocationController@retrive');
        $router->get("{table:[a-z_]+}/", 'LocationController@index');
        // example url divisions/2/districts/
        $router->get("{parentTable:[a-z_]+}/{parentTableId:[0-9]+}/{table:[a-z_]+}/", 'LocationController@locationRelationData');
    });
});

