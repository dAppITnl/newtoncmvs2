<?php
/**
 * General helper functions
 */

namespace common\helpers;

class GeneralHelper
{
  /**
   * set current date+time in format like '2022-11-14 15:25:23'
   *
   * @return [string] the current date
   */
  public static function getNow() {
    return Date("Y-m-d H:i:s");
  }

}