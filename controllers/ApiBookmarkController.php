<?php

namespace App\Controllers;

use App\Models\UserModel;

class ApiBookmarkController extends \App\Core\ApiController
{
    public function getBookmarks()
    {
        $userId = $this->getSession()->get('user_id');

        if ($userId) {
            $userModel = new UserModel($this->getDatabaseConnection());
            $user = $userModel->getById($userId);
            $usernameFetch = $user->username;
        }

        $bookmarks = $this->getSession()->get('bookmarks', []);
        $this->set('usernameFetch', $usernameFetch);
        $this->set('bookmarks', $bookmarks);

        // print_r([$usernameFetch, $bookmarks]);
        // exit;
    }

    public function addBookmark($auctionId)
    {
        $auctionModel = new \App\Models\AuctionModel($this->getDatabaseConnection());
        $auction = $auctionModel->getById($auctionId);

        if (!$auction) {
            $this->set('error', -1);
            return;
        }

        $bookmarks = $this->getSession()->get('bookmarks', []);

        foreach ($bookmarks as $bookmark) {
            if ($bookmark->auction_id == $auctionId) {
                $this->set('error', -2);
                return;
            }
        }

        $bookmarks[] = $auction;
        $this->getSession()->put('bookmarks', $bookmarks);

        $this->set('error', 0);
    }

    public function clear()
    {
        $this->getSession()->put('bookmarks', []);

        $this->set('error', 0);
    }
}
