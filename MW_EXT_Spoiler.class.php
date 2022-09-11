<?php

namespace MediaWiki\Extension\Z17DEV;

use OutputPage, Parser, PPFrame, Skin;

/**
 * Class MW_EXT_Spoiler
 */
class MW_EXT_Spoiler
{
  /**
   * Register tag function.
   *
   * @param Parser $parser
   *
   * @return bool
   * @throws \MWException
   */
  public static function onParserFirstCallInit(Parser $parser)
  {
    $parser->setHook('spoiler', [__CLASS__, 'onRenderTagSpoiler']);
    $parser->setHook('hide', [__CLASS__, 'onRenderTagHide']);

    return true;
  }

  /**
   * Render tag function: Spoiler.
   *
   * @param $input
   * @param array $args
   * @param Parser $parser
   * @param PPFrame $frame
   *
   * @return string
   */
  public static function onRenderTagSpoiler($input, array $args, Parser $parser, PPFrame $frame)
  {
    // Argument: title.
    $getTitle = MW_EXT_Kernel::outClear($args['title'] ?? '' ?: '');
    $outTitle = empty($getTitle) ? MW_EXT_Kernel::getMessageText('spoiler', 'title') : $getTitle;

    // Get content.
    $getContent = trim($input);
    $outContent = $parser->recursiveTagParse($getContent, $frame);

    // Out HTML.
    $outHTML = '<details class="mw-ext-spoiler navigation-not-searchable">';
    $outHTML .= '<summary>' . $outTitle . '</summary>';
    $outHTML .= '<div class="mw-ext-spoiler-body"><div class="mw-ext-spoiler-content">' . "\n\r" . $outContent . "\n\r" . '</div></div>';
    $outHTML .= '</details>';

    // Out parser.
    $outParser = $outHTML;

    return $outParser;
  }

  /**
   * Render tag function: Hide.
   *
   * @param $input
   * @param Parser $parser
   * @param PPFrame $frame
   *
   * @return string
   */
  public static function onRenderTagHide($input, Parser $parser, PPFrame $frame)
  {
    // Get content.
    $getContent = trim($input);
    $outContent = $parser->recursiveTagParse($getContent, $frame);

    // Out HTML.
    $outHTML = '<span class="mw-ext-hide navigation-not-searchable">';
    $outHTML .= '<span class="mw-ext-hide-body"><span class="mw-ext-hide-content">' . $outContent . '</span></span>';
    $outHTML .= '</span>';

    // Out parser.
    $outParser = $outHTML;

    return $outParser;
  }

  /**
   * Load resource function.
   *
   * @param OutputPage $out
   * @param Skin $skin
   *
   * @return bool
   */
  public static function onBeforePageDisplay(OutputPage $out, Skin $skin)
  {
    $out->addModuleStyles(['ext.mw.spoiler.styles']);

    return true;
  }
}
