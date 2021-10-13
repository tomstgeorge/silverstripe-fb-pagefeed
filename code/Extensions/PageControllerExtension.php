<?php

namespace Olliepop\FBPageFeed;

use SilverStripe\ORM\DataExtension;

class PageControllerExtension extends DataExtension
{

    public function getFBPageFeed()
    {
        $fbService = new FBPageFeedService();
        return $fbService->getStoredPosts();
    }

}