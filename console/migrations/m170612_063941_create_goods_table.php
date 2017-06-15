<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods`.
 */
class m170612_063941_create_goods_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods', [
            'id' => $this->primaryKey(),
            //name	varchar(50)	名称
            'name'=>$this->string(20)->notNull()->comment('商品名称'),
            'sn'=>$this->string(20)->notNull()->comment('货号'),
            'logo'=>$this->string(255)->comment('LOGO'),
            'goods_category_id'=>$this->integer()->comment('商品分类id'),
            'brand_id'=>$this->integer()->comment('品牌分类'),
            'market_price'=>$this->decimal(10,2)->comment('市场价格'),
            'shop_price'=>$this->decimal(10,2)->comment('商品价格'),
            'stock'=>$this->integer()->comment('库存'),
            //status	int(2)	状态(0下架 1在售)
            'is_on_sale'=>$this->smallInteger(1)->comment('是否在售'),
            'sort'=>$this->integer()->comment('排序'),
            //status	int(2)	状态(0回收站 1正常)
            'status'=>$this->smallInteger(1)->comment('状态'),
            'crate_time'=>$this->integer(11)->comment('创建时间'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods');
    }
}
