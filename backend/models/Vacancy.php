<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vacancy".
 *
 * @property int $id
 * @property string $title
 * @property string|null $description
 * @property int|null $salary
 * @property string|null $created_at
 */
class Vacancy extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vacancy';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'salary'], 'default', 'value' => null],
            [['title'], 'required'],
            [['description'], 'string'],
            [['salary'], 'default', 'value' => null],
            [['salary'], 'number'],
            [['created_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'description' => 'Описание',
            'salary' => 'Зарплата',
            'created_at' => 'Created At',
        ];
    }

    public function fields()
    {
        return [
            'id',
            'title',
            'description',
            'salary' => function () {
                return $this->salary !== null ? $this->salary / 100 : null;
            },
            'created_at',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // salary приходит в рублях, храним в копейках
            $this->salary = $this->salary !== null ? (int) round($this->salary * 100) : null;
            return true;
        }
        return false;
    }
}
