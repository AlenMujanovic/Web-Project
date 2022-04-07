<?php
return [
    App\Core\Route::get('|^category/([0-9]+)/?$|',              'Category',               'show'),
    App\Core\Route::get('|^category/([0-9]+)/delete/?$|',       'Category',               'delete'),

    App\Core\Route::get('|^auction/([0-9]+)/?$|',              'Auction',               'show'),

    App\Core\Route::any('|^.*$|', 'Main', 'home')


];
