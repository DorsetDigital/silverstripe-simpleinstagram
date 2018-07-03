<?php
namespace DorsetDigital\SimpleInstagram;

use Instagram\Storage\CacheManager;
use Instagram\Api;
use SilverStripe\Control\Director;
use SilverStripe\ORM\ArrayList;
use SilverStripe\View\ArrayData;
use SilverStripe\Core\Injector\Injectable;
use Psr\SimpleCache\CacheInterface;
use SilverStripe\Core\Flushable;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Core\Config\Configurable;

/**
 * Simple wrapper to get the Instagram feed of a user and return it as a SilverStripe data list
 *
 * @author Tim Burt - dorset-digital.net
 */
class InstagramHelper implements Flushable
{

 use Injectable;
 use Configurable;

 private $api;
 private $cache;

 /**
  * @config
  *
  * Enable caching of feed via Silverstripe cache mechanisms
  * @var bool
  */
 private static $enable_cache = false;

 /**
  * @config
  *
  * Cache time
  * @var bool
  */
 private static $cache_time = 900;

 public function __construct($userName)
 {
  $instaCache = new CacheManager(Director::baseFolder() . DIRECTORY_SEPARATOR . 'instagram-');
  $this->api = new Api($instaCache);
  $this->api->setUserName($userName);

  if ($this->config()->get('enable_cache') === true) {
   $this->cache = Injector::inst()->get(CacheInterface::class . '.DDInstaCache');
  }
 }

 public function getFeed()
 {

  $cacheKey = 'InstagramCache';

  if (($this->config()->get('enable_cache') === true) && ($this->cache->has($cacheKey))) {
   return unserialize($this->cache->get($cacheKey));
  }

  $feed = $this->api->getFeed();
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

  if ($this->config()->get('enable_cache') === true) {
   $this->cache->set($cacheKey, serialize($list), $this->config()->get('cache_time'));
  }

  return $list;
 }

 public static function flush()
 {
  Injector::inst()->get(CacheInterface::class . '.DDInstaCache')->clear();
 }
}
