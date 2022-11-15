<?php

namespace frontend\controllers;

use common\controllers\Controller;
use frontend\models\TotalpriceForm;
use frontend\models\ScheduledMaintanceJob;

/**
 * Pricing Controller
 */
class PricingController extends Controller
{

  /**
    * Displays pricing homepage.
    *
    * @return string
    */
  public function actionIndex()
  {
    return $this->render('index');
  }

  /**
   * Calculates and shows the totalprice of a car's service form + result page
   *
   * @return mixed
   */
  public function actionTotalprice()
  {
    $totalpriceResult = [];
    $totalpriceForm = new TotalpriceForm();
    if ($totalpriceForm->load($_POST) && $totalpriceForm->validate()) {
      $totalpriceResult = ScheduledMaintanceJob::calcTotalCarServicePrice($totalpriceForm->carLicense, $totalpriceForm->$startDate);
    }

    return $this->render('totalpriceForm', [
      'formModel' => $totalpriceForm,
      'totalprice' => $totalpriceResult
    ]);
  }

}