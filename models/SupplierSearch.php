<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Supplier;

class SupplierSearch extends Supplier
{
    
    public $id_operator;

    public function rules()
    {
        // 只有在 rules() 函数中声明的字段才可以搜索
        return [
            [['id'], 'integer','message'=>'id must be a number'],
            [['name', 'code','t_status','id_operator'], 'safe'],
        ];
    }
    
    /**
     * @inheritdoc
     */
     public static function tableName()
     {
         return 'supplier';
     }

    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Supplier::find();
        $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 10,
                ],
                'sort' => [
                    'defaultOrder' => [
                        'id' => SORT_DESC
                    ]
                ]
            ]);

        // 从参数的数据中加载过滤条件，并验证
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        if($this->id&&$this->id_operator){
            // 增加过滤条件来调整查询对象
            $query->andFilterWhere([$this->id_operator,'id',$this->id]);
        }
        $query->andFilterWhere(['t_status' => $this->t_status]);
        $query->andFilterWhere(['like', 'name', $this->name])
              ->andFilterWhere(['like', 'code', $this->code]);

        return $dataProvider;
    }
}