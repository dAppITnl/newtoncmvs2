<?php

namespace frontend\models;

use common\models\Models;

class TotalpriceForm extends Models
{
  public $carLicense;
  public $startDate;

  /**
   * Form rules 
   */
  public function rules()
  {
    return [
      [['carLicense'], 'required'],
      [['startDate'], 'safe']
    ];
  }

  /**
   * Form labels
   */
  public function attributelabels()
  {
    return [
      'carLicense' => 'kenteken',
      'startDate' => 'Service datum'
    ];
  }

}