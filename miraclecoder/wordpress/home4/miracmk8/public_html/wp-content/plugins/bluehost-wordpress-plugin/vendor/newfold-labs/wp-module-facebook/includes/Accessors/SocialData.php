<?php
namespace NewfoldLabs\WP\Module\Facebook\Accessors;

use NewfoldLabs\WP\Module\Facebook\Accessors\Business;
use NewfoldLabs\WP\Module\Facebook\Accessors\User;

class SocialData
{
  public $source = '';

  public $user;

  public $business;

  public function __construct()
  {
    $this->user = new User();
    $this->business = new Business();
  }

  function set_source($source)
  {
    $this->source = $source;
  }

  function get_source()
  {
    return $this->source;
  }

  function set_user($user)
  {
    $this->user = $user;
  }

  function get_user()
  {
    return $this->user;
  }

  function set_business($business)
  {
    $this->business = $business;
  }

  function get_business()
  {
    return $this->business;
  }
}
