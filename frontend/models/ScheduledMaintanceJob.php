<?php

namespace frontend\models;

use common\models\Models;
use common\base\Exception;
use common\helpers\GeneralHelper;
use backend\models\ScheduledMaintanceJob as backendScheduledMaintanceJob;
use backend\models\Car;
use backend\models\SpareParts;

/**
 * ScheduledMaintanceJob class
 * The frontend additional functions to the ScheduledMaintanceJob backend class 
 */
class ScheduledMaintanceJob extends backendScheduledMaintanceJob
{
  // Set Vat percentage as constant; Alternatively can be from a general .env settings file or database table
  const $VATPERCENTAGE = 21;

  /**
   * rules for Scheduledmaintenancejob
   *
   * @return array
   */
  public function rules()
  {
    return [
      [['smj_id', 'smj_title'], 'required'],
      [['smj_id', 'smjtsl_id', 'smjeng_id', 'smjmjb_id'], 'integer'],
      [['smj_title'], 'string', 'max' => 255],
      [['smj_id'], 'unique'],
      [['smjeng_id'], 'exist', 'skipOnError' => true, 'targetClass' => Engineer::class, 'targetAttribute' => ['smjeng_id' => 'eng_id']],
      [['smjmjb_id'], 'exist', 'skipOnError' => true, 'targetClass' => Maintenancejob::class, 'targetAttribute' => ['smjmjb_id' => 'mjb_id']],
      [['smjtsl_id'], 'exist', 'skipOnError' => true, 'targetClass' => Timeslot::class, 'targetAttribute' => ['smjtsl_id' => 'tsl_id']],
    ];
  }

  /**
   * Labels for ScheduledMaintanceJob
   */
  public function attributeLabels()
  {
    return [
      'smj_id' => 'ID',
      'smjtsl_id' => 'Tijdinterval',
      'smjeng_id' => 'Monteur',
      'smjmjb_id' => 'Service taak',
      'smj_title' => 'Titel',
    ];
  }

  /**
   * Gets query for [[Smjcars]].
   *
   * @return \yii\db\ActiveQuery|SmjcarQuery
   */
  public function getSmjcars()
  {
    return $this->hasMany(Smjcar::class, ['smj_id' => 'smj_id']);
  }

  /**
   * Gets query for [[Smjeng]].
   *
   * @return \yii\db\ActiveQuery|EngineerQuery
   */
  public function getSmjeng()
  {
    return $this->hasOne(Engineer::class, ['eng_id' => 'smjeng_id']);
  }

  /**
   * Gets query for [[Smjmjb]].
   *
   * @return \yii\db\ActiveQuery|MaintenancejobQuery
   */
  public function getSmjmjb()
  {
    return $this->hasOne(Maintenancejob::class, ['mjb_id' => 'smjmjb_id']);
  }

  /**
   * Gets query for [[Smjtsl]].
   *
   * @return \yii\db\ActiveQuery|TimeslotQuery
   */
  public function getSmjtsl()
  {
    return $this->hasOne(Timeslot::class, ['tsl_id' => 'smjtsl_id']);
  }

  /**
   * find ScheduledMaintanceJob model data
   *
   * @return [model] the retrieved ScheduledMaintanceJob data, if any
   */
  public static function find()
  {
    return new \common\db\ActiveQuery(get_called_class());
  }

  /**
   * local _getScheduledmaintenanceJob function to find a service job ID or Error
   *
   * @param [type] $carLicense
   * @param [type] $startDate
   * @return [model] the result mjb_id
   */
  private function _getScheduledmaintenanceJob($carLicense, $startDate)
  {
    $result = [];
    if (!empty($carLicense) && !empty($startDate)) {
      $query = self::find();
      // find the MaintenanceJob.mjb_id as the service job ID by the carLicense and at startDate
      $query
        ->select('mjb.mjb_id')
        ->where([
          'car.license' => $carLicense,
          'tsl.Startdate' => $startDate,
        ])
        ->joinWith('car')
        ->joinWith('TimeSlot as tsl')
        ->joinWith('MaintenanceJob as mjb');
      $dataProvider = new \common\db\ActiveDataProvider(['query' => $query]);
      $result = $dataProvider->One();
    } else {
      $result['ERROR'] = sprintf('Empty carlicense %s or startdate %s', $carLicense, $startDate);
    }

    return $result;
  }

  /**
   * function: getTotalCarServicePrice
   *
   * @param [string] $carLicense
   * @param [string] $startDate
   * @return [array] with partsCosts, ServiceCosts, totalExVat, Vatprice and VATPercentage or ['Error'] with errormessage
   */
  public function calcTotalCarServicePrice($carLicense='', $startDate='')
  {
    $result = [];
    if (!empty($carLicense)) {
      try {
        $partCosts = $servicingHours = $servicingCosts = 0.0;

        // Select the car details
        $carModelId = Car::find('carcmd_id')->where(['car_license' => $carLicense]);

        // Set today if startDate not given
        if (empty($startDate)) $startDate = GeneralHelper::getNow();

        // Find MaintenanceJob-ID for the car's service at startDate
        $maintenanceJob = self::_getScheduledmaintenanceJob($carLicense, $startDate);

        // If MaintenanceJob-ID, so a service is available, calculale total price of all used parts
        if (!empty($maintenanceJob->mjb_id)) {
          // find all used parts
          $parts = SpareParts::getJobParts($maintenanceJob->mjb_id, $carModelId);

          // if part(s) found, sum prices
          if ($parts) {
            foreach($parts as $part) {
              $partCosts += $part->spp_cost;
            }           
          }
        }

        // if any service hours spent and hourrate is known, calc service hour costs
        if (!empty($maintenanceJob->smj_hoursSpent) && !empty($maintenanceJob->mjb_hourrate)) {
          $servicingCosts = $maintenanceJob->smj_hoursSpent * $maintenanceJob->mjb_hourrate;
        }

        // calculate total price and vat 
        $totalPriceExvat = $partCosts + $servicingCosts;
        $vat = $totalPriceExvat * $VATPERCENTAGE/100;

        // return the values
        $result = [
          'partCosts' => $partCosts,
          'serviceCosts' => $servicingCosts,
          'totalExVat' => $totalPriceExvat,
          'vat' => $vat,
          'vatPercentage' => $VATPERCENTAGE,
          'servicehours' => $maintenanceJob->smj_hoursSpent
        ];
      } catch (Exception $e) {
        // General error
        $result['error'] = sprintf("Exception: %s", $e->getMessage);
      }
    } else {
      // Missing carLicense value
      $result['error'] = "No carLicense given!";
    }

    return $result;
  }

}