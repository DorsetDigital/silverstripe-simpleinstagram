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
   foreach ($feed->medias as $item) {
    $itemList->push(ArrayData::create([
            'ID' => $item->id,
            'Type' => $item->typeName,
            'Link' => $item->link,
            'ThumbURL' => $item->thumbnailSrc,
            'Caption' => $item->caption
    ]));
   }

   $list = ArrayData::create([
           'Items' => $itemList,
           'ProfileImage' => $feed->profilePicture,
           'Link' => 'https://www.instagram.com/' . $feed->userName
   ]);

   return $list;
  }
 }
}
