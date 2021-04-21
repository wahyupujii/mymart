<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "item".
 *
 * @property int $id
 * @property string|null $name
 * @property int $price
 * @property int $category_id
 *
 * @property Category $category
 */
class Item extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $upload;
    public static function tableName()
    {
        return 'item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['price', 'category_id'], 'required'],
            [['price', 'category_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name', 'image'], 'string', 'max' => 255],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
            [['upload'], 'file', 'extensions' => ['png','jpg','jpeg'], 'maxSize' => '500000'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'price' => 'Price',
            'image' => 'Image',
            'category_id' => 'Category ID',
            'upload' => 'Item Image',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    
   /** 
    * Gets query for [[OrderItems]]. 
    * 
    * @return \yii\db\ActiveQuery 
    */ 
    public function getOrderItems() 
    { 
        return $this->hasMany(OrderItem::className(), ['item_id' => 'id']); 
    } 
   
    public function getPriceRp() {
        return 'Rp '.number_format($this->price,2,'.','.');
    }

    public function getImagePre() {
        return Yii::$app->request->hostInfo.'/'.$this->image;
    }
}
