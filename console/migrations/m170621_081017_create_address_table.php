<?php

use yii\db\Migration;

/**
 * Handles the creation of table `address`.
 */
class m170621_081017_create_address_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('address', [
            'id' => $this->primaryKey(),
            'username'=>$this->string(50)->notNull()->comment('用户名'),
//tel	char(11)	电话
            'tel'=>$this->char(11)->comment('电话'),
            'address'=>$this->char(255)->comment('详细地址'),
//status	int(1)	状态（1正常，0删除）
            'status'=>$this->integer()->comment('状态'),
//created_at	int	添加时间
            'created_at'=>$this->integer()->comment('添加时间'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('address');
    }
}
