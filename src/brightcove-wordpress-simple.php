<?php
/**
 * Plugin Name: Simple Brightcove Player Embed
 * Plugin URI: http://wordpress.org/plugins/simple-brightcove-player-embed/
 * Description: This allows a Brighcove Video Cloud video to be easily embedded by the URL you get from the studio. It does not need a Video Cloud Media API key, so works with any Brightcove account edition.
 * Version: 0.8
 * Author: mister-ben
 * Author URI: http://wordpress.org/plugins/simple-brightcove-player-embed/
 * License:GPL2
 * Usage: [bc]http://bcove.me/bbnvrhso[/bc]
 *      [bc width="480" height="270"]http://bcove.me/bbnvrhso[/bc]
 *
 *
 * This is a simple way to publish a player by using a link to a player.
 * This work with the bcove.me links from the media module and other links to pages with players.
 * There may be ways you can break this.
 * 
 */
class BC_Shortcode {
  static $add_script = false;
  static $dom;

  static function init() {
    add_shortcode('bc', array(__CLASS__, 'handle_shortcode'));
    add_action('init', array(__CLASS__, 'register_script'));
    add_action('wp_footer', array(__CLASS__, 'print_script'));
  }

  static function set_player_param($object, $name, $value) {
    $updated = false;
    $params = $object->getElementsByTagName('param');
    foreach ($params as $child) {
      if ($child->getAttribute('name') == $name )  {
        $child->setAttribute('value',$value);
        $updated = true;
      }
    }
    if (!$updated) {
      $newParam = self::$dom->createElement('param');
      $newParam->setAttribute("name",$name);
      $newParam->setAttribute("value",$value);
      $object->appendChild($newParam);
    }
  }

  static function get_player_param($object, $name) {
    $params = $object->getElementsByTagName('param');
    foreach ($params as $child) {
      if ($child->getAttribute('name') == $name )  {
        return $child->getAttribute('value');
      }
    }
  }

  static function handle_shortcode($atts, $content = null) {
    extract(shortcode_atts(array(
          'height' => null,
      'width' => null,
      'autostart' => null
      ), $atts));

    // Check if a valid URL
      if(!filter_var($content, FILTER_VALIDATE_URL) || substr($content, 0, 4) != 'http'){ 
        return '<p>"' . $content . '" is not a URL.</p>';
      }

    // Retrieve the URL and find the first object with class BrightcoveExperience
    // TODO: This could cache to the local db - maybe not, other caching plugins will cover this anyway 
    $html = file_get_contents($content);

    // Get video ID from link, if present
    $videoId = null;

    // Try to get video ID from URL, then header
    parse_str(parse_url($content, PHP_URL_QUERY),$args);
    if (isset($args['bctid'])) {
      $videoId = $args['bctid'];
    } else {
      foreach ($http_response_header as $header) {
        if (substr($header, 0, 10) == 'Location: ') {
          $location = explode(': ', $header);
          parse_str(parse_url($location[1], PHP_URL_QUERY),$args);
          if (isset($args['bctid'])) {
            $videoId = $args['bctid'];
          }
        }
      }
    }
  
    libxml_use_internal_errors(true);
    self::$dom = new DOMDocument;
    self::$dom->loadHTML($html);
    $xpath =new DOMXPath(self::$dom);
    $query = '//object[@class="BrightcoveExperience"]';
    $objects = $xpath->query($query);
    
    // If nothing matches, abort
    if ($objects->length == 0) {
      return '<p>There is no player at <a href="' . $content . '">' . $content . '</a>.</p>';
    }
    
    $object = $objects->item(0);

    // If there was no video id in the location header, look for it in the publsihing code 
    if (!isset($videoId)) {
      $videoId = self::get_player_param($object,'@videoPlayer');
    }

    if (!isset($videoId) || $videoId == '') {
      $videoId = self::get_player_param($object,'videoID');
    }

    if (!isset($videoId) || $videoId == '') {

      // If we don't have a video ID yet, grab the twitter card URL to find the video id
      $query = '//meta[@name="twitter:player"]';
      $metas = $xpath->query($query);
      if($metas->length > 0) {
        parse_str(parse_url($metas->item(0)->getAttribute("content"), PHP_URL_QUERY),$args);
        $videoId = $args['bctid'];
      }
    }

    self::set_player_param($object,'@videoPlayer',$videoId);
    

    // Override width and height if given
    if (!empty($width)) {
      self::set_player_param($object,'width',$width);
    }

    if (!empty($height)) {
      self::set_player_param($object,'height',$height);
    }

    // Wordpress lowercases the attribute names
    if (!empty($autostart)) {
      self::set_player_param($object,'autoStart',$autostart);
    }

    if (is_ssl()) {
      self::set_player_param($object,'secureConnections','true');
      self::set_player_param($object,'secureHTMLConnections','true');
    }

    // Add wmode=transparent (on IE, not using this means a flash object goes on top of everything / obstructs some navigation)
    self::set_player_param($object,'wmode','transparent');

    // Add htmlFallback. This means you'll get an HTML player if Flash is not installed **if** the browser can play h264
    self::set_player_param($object,'htmlFallback','true');

    // Create random element ID
    $expid = 'simple-brightcove-' . get_the_ID() . "-" . $videoId;
    $object->setAttribute("id",$expid);

    // IE8 fix — some themes give <object>s max-width:100%, which collapses the player on IE8
    $ie8Style = '<!--[if IE 8]><style type="text/css">.BrightcoveExperience {max-width:none;}</style><![endif]-->';

    // Make sure the script gets written, and return HTML
    self::$add_script = true;
    return "<!-- Brightcove Video -->\n" . self::$dom->saveXML($object) . "\n" . $ie8Style;
  }

  static function register_script() {
    if (is_ssl()) {
      $scriptUrl =  'https://sadmin.brightcove.com/js/BrightcoveExperiences.js';
    } else {
      $scriptUrl =  'http://admin.brightcove.com/js/BrightcoveExperiences.js';
    }
    wp_register_script('brightcove', $scriptUrl, array(), false, true);
  }

  static function print_script() {
    if ( ! self::$add_script )
      return;

    wp_print_scripts('brightcove');
  }
}

BC_Shortcode::init();
?>
