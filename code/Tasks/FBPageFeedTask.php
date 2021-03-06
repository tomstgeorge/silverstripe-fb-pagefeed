<?php

use Olliepop\FBPageFeed\FBPageFeedService;
use Olliepop\FBPageFeed\FacebookPost;

use SilverStripe\Control\CliController;

//      Run every 10 minutes. Store in crontab:
//      */10 * * * * /var/www/vhosts/nzse.ac.nz/httpdocs/framework/sake FBPageFeed "flush=1"

/*
 * Class FBPageFeedTask
 */

class FBPageFeedTask extends CliController
{

    /**
     * @var
     */
    private $fbService;

    /**
     * Initiate the service and copy new posts to our database
     */
    public function process()
    {
        $this->fbService = new FBPageFeedService();

        $storedPosts = $this->fbService->getStoredPosts(1);
        $posts = $this->fbService->getPostsFromFacebook(1);
        $inserted = 0;
        foreach ($posts as $i => $post) {

            if (!isset($post['FBID'])) {
                break;
            }

            $existingPost = FacebookPost::get()->filter('FBID', $post['FBID'])->first();


            if ($existingPost) {
                break;
            } else {
                if (isset($post['source'])) {
                    $imageSource = $post['source'];
                } else {
                    $imageSource = null;
                }

                $this->fbService->storePost($post['FBID'], $post['Content'], $post['URL'], $post['TimePosted'], $imageSource);
                $inserted++;
            }
        }

        echo 'Stored ' . $inserted . ' new posts.';
    }

}
