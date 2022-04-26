<?php
return [
    App\Core\Route::get('|^user/register/?$|',                  'Main',             'getRegister'),
    App\Core\Route::post('|^user/register/?$|',                 'Main',             'postRegister'),
    App\Core\Route::get('|^user/login/?$|',                     'Main',             'getLogin'),
    App\Core\Route::post('|^user/login/?$|',                    'Main',             'postLogin'),



    App\Core\Route::get('|^category/([0-9]+)/?$|',              'Category',         'show'),
    App\Core\Route::get('|^category/([0-9]+)/delete/?$|',       'Category',         'delete'),

    App\Core\Route::get('|^auction/([0-9]+)/?$|',               'Auction',          'show'),
    App\Core\Route::post('|^search/?$|',                        'Auction',          'postSearch'),



    #Api routes:
    App\Core\Route::get('|^api/auction/([0-9]+)/?$|',           'ApiAuction',       'show'),
    App\Core\Route::get('|^api/bookmarks/?$|',                  'ApiBookmark',      'getBookmarks'),
    App\Core\Route::get('|^api/bookmarks/add/([0-9]+)?$|',      'ApiBookmark',      'addBookmarks'),
    App\Core\Route::get('|^api/bookmarks/clear/?$|',            'ApiBookmark',      'clear'),

    #User role routes:
    App\Core\Route::get('|^user/profile/?$|',                   'UserDashboard',          'index'),

    App\Core\Route::get('|^user/categories/?$|',                'UserCategoryManagment',  'categories'),
    App\Core\Route::get('|^user/categories/edit/([0-9]+)/?$|',  'UserCategoryManagment',  'getEdit'),
    App\Core\Route::post('|^user/categories/edit/([0-9]+)/?$|', 'UserCategoryManagment',  'postEdit'),
    App\Core\Route::get('|^user/categories/add/?$|',            'UserCategoryManagment',  'getAdd'),
    App\Core\Route::post('|^user/categories/add/?$|',           'UserCategoryManagment',  'postAdd'),

    App\Core\Route::get('|^user/auctions/?$|',                  'UserAuctionManagment',  'auctions'),
    App\Core\Route::get('|^user/auctions/edit/([0-9]+)/?$|',    'UserAuctionManagment',  'getEdit'),
    App\Core\Route::post('|^user/auctions/edit/([0-9]+)/?$|',   'UserAuctionManagment',  'postEdit'),
    App\Core\Route::get('|^user/auctions/add/?$|',              'UserAuctionManagment',  'getAdd'),
    App\Core\Route::post('|^user/auctions/add/?$|',             'UserAuctionManagment',  'postAdd'),


    App\Core\Route::any('|^.*$|', 'Main', 'home'),
];
