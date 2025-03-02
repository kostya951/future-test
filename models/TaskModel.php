<?php

namespace app\models;

/**
 * Model representing tasks
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $due_date
 * @property string $create_date
 * @property string $status
 * @property string $category
 * @property string $priority
 */
class TaskModel extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%tasks}}';
    }

    public function rules()
    {
        return [
            [['name'],'required'],
            [['name'],'string','max' => 255],
            [['status'], 'in', 'range' => [null,'выполнена', 'не выполнена']],
            [['priority'], 'in', 'range' => [null,'низкий', 'средний', 'высокий']],
            [['category'], 'in', 'range' => [null,'работа', 'дом', 'личное']],
            [['name','description','due_date','create_date','status','priority','category'],'safe']
        ];
    }
}