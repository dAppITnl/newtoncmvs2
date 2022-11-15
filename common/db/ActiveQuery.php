<?php
/** 
 * Normally part of vendor\Yii library, but to give an idea..
 */

namespace common\db;

class ActiveQuery
{
  /**
   * Constructor.
   * @param string $modelClass the model class associated with this query
   * @param array $config configurations to be applied to the newly created query object
   */
    public function __construct($modelClass, $config = [])
    {
        $this->modelClass = $modelClass;
        //parent::__construct($config);
    }

}