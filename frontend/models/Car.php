<?php

namespace frontend\models;

use common\models\Models;

/**
 * Car model
 */
class Car extends Models
{
  /**
   * Car table field rules
   *
   * @return [array] the rules
   */
  public function rules()
  {
    return [
      [['car_id', 'carcmd_id', 'carcst_id', 'car_license'], 'required'],
      [['car_id', 'carcmd_id', 'carcst_id'], 'integer'],
      [['car_license'], 'string', 'max' => 32],
      [['car_id'], 'unique'],
      [['carcmd_id'], 'exist', 'skipOnError' => true, 'targetClass' => Carmodel::class, 'targetAttribute' => ['carcmd_id' => 'cmd_id']],
      [['carcst_id'], 'exist', 'skipOnError' => true, 'targetClass' => Customer::class, 'targetAttribute' => ['carcst_id' => 'cst_id']],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels()
  {
    return [
      'car_id' => 'ID',
      'carcmd_id' => 'Auto model',
      'carcst_id' => 'Klant',
      'car_license' => 'Kenteken',
    ];
  }

  /**
   * Gets query for [[Carcmd]].
   *
   * @return \yii\db\ActiveQuery|CarmodelQuery
   */
  public function getCarcmd()
  {
    return $this->hasOne(Carmodel::class, ['cmd_id' => 'carcmd_id']);
  }

  /**
   * Gets query for [[Carcst]].
   *
   * @return \yii\db\ActiveQuery|CustomerQuery
   */
  public function getCarcst()
  {
    return $this->hasOne(Customer::class, ['cst_id' => 'carcst_id']);
  }

  /**
   * Gets query for [[Smjcars]].
   *
   * @return \yii\db\ActiveQuery|SmjcarQuery
   */
  public function getSmjcars()
  {
    return $this->hasMany(Smjcar::class, ['car_id' => 'car_id']);
  }

  /**
   * Find Car
   * @return CarQuery the active query used by this AR class.
   */
  public static function find()
  {
    return new CarQuery(get_called_class());
  }
}