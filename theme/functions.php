<?php
/**
 * Theme related functions. 
 *
 */
 
/**
 * Get title for the webpage by concatenating page specific title with site-wide title.
 *
 * @param string $title for this page.
 * @return string/null weather the favicon is defined or not.
 */
function get_title($title) {
  global $roo;
  return $title . (isset($roo['title_append']) ? $roo['title_append'] : null);
}


/**
 * Get navigation bar.
 *
 */
 function get_navbar($menu) {
    $html = "<nav>\n<ul class='{$menu['class']}'>\n";
    foreach($menu['items'] as $item) {
        $selected = $menu['callback_selected']($item['url']) ? " class='selected' " : null;
        $html .= "<li{$selected}><a href='{$item['url']}' title='{$item['title']}'>{$item['text']}</a></li>\n";
    }
    $html .= "</ul>\n</nav>\n";
    return $html;
 }

