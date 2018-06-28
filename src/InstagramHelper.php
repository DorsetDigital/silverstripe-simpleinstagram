<?php
namespace DorsetDigital\SimpleInstagram;

use Instagram\Storage\CacheManager;
use Instagram\Api;
use SilverStripe\Control\Director;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;
use SilverStripe\Core\Injector\Injectable;

/**
 * Simple wrapper to get the Instagram feed of a user and return it as a SilverStripe data list
 *
 * @author Tim Burt - dorset-digital.net
 */
class InstagramHelper
{

 use Injectable;

 private $api;

 public function __construct($userName)
 {
  $cache = new CacheManager(Director::baseFolder() . DIRECTORY_SEPARATOR . 'instagram-');
  $this->api = new Api($cache);
  $this->api->setUserName($userName);
 }
 
 public function getFeed()
 {
  $feed = $this->api->getFeed();
  if ($feed) {
   $itemList = ArrayList::create();
   foreach ($feed->getMedias() as $item) {
    $itemList->push(ArrayData::create([
            'ID' => $item->getId(),
            'Type' => $item->getTypeName(),
            'Link' => $item->getLink(),
            'ThumbURL' => $item->getThumbnailSrc(),
            'Caption' => $item->getCaption(),
            'Likes' => $item->getLikes(),
            'Date' => $item->getDate()->format('h:i d-m-Y')
    ]));
   }

   $list = ArrayData::create([
           'Items' => $itemList,
           'ProfileImage' => $feed->getProfilePicture(),
           'Link' => 'https://www.instagram.com/' . $feed->userName,
           'Bio' => $feed->getBiography(),
           'ID' => $feed->getId(),
           'FullName' => $feed->getFullName(),
           'Followers' => $feed->getFollowers(),
           'UserName' => $feed->getUserName(),
           'Following' => $feed->getFollowing()
   ]);

   return $list;
  }
 }
}
