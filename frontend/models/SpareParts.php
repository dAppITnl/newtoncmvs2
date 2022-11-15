<?php

namespace frontend\models;

use common\models\Models;

/**
 * SpareParts class
 */
class SpareParts extends Models
{

  /**
   * SpareParts table field rules
   *
   * @return [array] the rules
   */
  public function rules()
  {
    return [
      [['spp_id', 'spp_name', 'spp_costexcl', 'spp_vatperc'], 'required'],
      [['spp_id'], 'integer'],
      [['spp_costexcl', 'spp_vatperc'], 'number'],
      [['spp_name'], 'string', 'max' => 255],
      [['spp_id'], 'unique'],
    ];
  }

  /**
   * Find a Sparepart
   *
   * @return [model] found Sparepart data
   */
  public static function find()
  {
    return new \common\db\ActiveQuery(get_called_class());
  }

  /**
   * Find job parts
   *
   * @param [type] $mjbId
   * @param [type] $mdlId
   * @return void
   */
  public static function getJobParts($mjbId, $mdlId)
  {
    $result = [];
    if (!empty($mjbId) && !empty($mdlId)) {
      $query = self::find();
      $query
        ->select('spp.*')
        ->where([
          'mjbspp.mjb_id' => $mjbId,
          'cmdspp.mdl_id' => $mdlId
        ])
        ->joinWith('mjbspp')
        ->joinWith('cmdspp');
      $dataProvider = new \common\db\ActiveDataProvider(['query' => $query]);
      $result = $dataProvider->One();
    } else {
      $result['ERROR'] = sprintf('Empty mjbId %d or mdlId %d', $mjbId, $mdlId);
    }

    return $result;
  }
}