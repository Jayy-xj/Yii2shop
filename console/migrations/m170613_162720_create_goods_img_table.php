<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_img`.
 */
class m170613_162720_create_goods_img_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_img', [
            'id' => $this->primaryKey(),
            'goods_id'=>$this->integer()->comment('商品id'),
            'path'=>$this->string(255)->comment('图片'),
        ]);
    }
    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_img');
    }
}
