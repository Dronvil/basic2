<?php


namespace app\models;


use yii\db\ActiveRecord;
use yii\widgets\ActiveField;

class Sort
{

    public function rules()
    {
        return [
            [['field', 'type', 'sort'], 'string', 'max' => 255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'field' => 'Поле ввода',
            'type' => 'Тип поля',
            'sort' => 'Метод сортировки',
        ];
    }
}