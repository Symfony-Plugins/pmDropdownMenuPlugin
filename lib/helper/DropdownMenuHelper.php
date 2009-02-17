<?php

function _add_dropdown_menu_resources($options)
{
  sfContext::getInstance()->getResponse()->addJavascript('/sf/prototype/js/prototype');
  sfContext::getInstance()->getResponse()->addJavascript('/pmDropdownMenuPlugin/js/dropdown');
  sfContext::getInstance()->getResponse()->addStylesheet('/pmDropdownMenuPlugin/css/dropdown');

  if (isset($options["css"]))
    sfContext::getInstance()->getResponse()->addStylesheet($options["css"]);
}

function dropdown_menu($config_file, $options = array())
{
  use_helper("I18N", "Javascript");

  _add_dropdown_menu_resources($options);

  $items = sfYaml::load($config_file);

  $html = tag("div", array("id" => "dropdown_menu"), false);
  $html .= content_tag("h1", isset($options["title"])?$options["title"]:__("Dropdown menu plugin"));
  $html .= tag("div", array("id" => "menu"), false);

  $i = 0;
  foreach ($items as $item) {
    $display = true;
    if (isset($items["credentials"]))
      $display = $sf_user->hasCredential($item["credentials"]);

    if ($display) {
      $title = isset($item["title"])?$item["title"]:"";
      if (isset($item["dropdown"])) {
        $html .= link_to_function(__($title), "togglePanels('panel$i')", array("id" => "panel$i-link"));
      } else {
        $url = isset($item["url"])?$item["url"]:"#";
        $html .= link_to(__($title), $url);
      }
      $html .= " ".(isset($options["separator"])?$options["separator"]:"|")." ";
    }
    $i++;
  }
  $html = substr($html, 0, -2);

  $i = 0;
  foreach ($items as $item) {
    $display = true;
    if (isset($items["credentials"]))
      $display = $sf_user->hasCredential($item["credentials"]);

    if ($display) {
      if (isset($item["dropdown"])) {
        $html .= tag("div", array("id" => "panel$i", "class" => "panel", "style" => "display: none;"), false);

        $html .= tag("ul", null, false);
        foreach ($item["dropdown"] as $ditem) {
          $display = true;
          if (isset($ditem["credentials"]))
            $display = $sf_user->hasCredential($item["credentials"]);

          if ($display) {
            $title = isset($ditem["title"])?$ditem["title"]:"";
            $url = isset($ditem["url"])?$ditem["url"]:"#";
            $html .= content_tag("li", link_to(__($title), $url));
          }
        }
        $html .= tag("/ul", null, false);
        $html .= tag("/div", null, false);
      }
    }
    $i++;
  }

  $html .= tag("/div", null, false);
  $html .= tag("/div", null, false);

  return $html;
}
