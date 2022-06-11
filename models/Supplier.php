<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\data\ActiveDataProvider;

class Supplier extends ActiveRecord
{

    public static function findList()
    {
        $supplierList = self::find()->where(['t_status' => "ok"]);
        $provider = new ActiveDataProvider([
            'query' => $supplierList,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ],
        ]);
        
        return $provider;
    }
}

?>