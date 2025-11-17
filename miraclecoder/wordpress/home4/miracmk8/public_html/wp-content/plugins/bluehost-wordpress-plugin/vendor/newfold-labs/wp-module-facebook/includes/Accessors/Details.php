<?php
namespace NewfoldLabs\WP\Module\Facebook\Accessors;

class Details
{
  public $profile = [];

  public $posts;

  public $images;

  function set_profile($profile)
  {
    $this->profile = $profile;
  }

  function get_profile()
  {
    return $this->profile;
  }

  function set_posts($posts)
  {
    $this->posts = $posts;
  }

  function get_posts()
  {
    return $this->posts;
  }

  function set_images($images)
  {
    $this->images = $images;
  }

  function get_images()
  {
    return $this->images;
  }
}
