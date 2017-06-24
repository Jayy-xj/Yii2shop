<?php
use yii\helpers\Html;
foreach ($categories as $k=>$category)://遍历所有一级分类?>
    <div class="cat <?=$k==0?"item1":""?>">
        <h3><?=Html::a($category->name,['goods/list','goods_category_id'=>$category->id])?><b></b></h3>
        <div class="cat_detail">
            <?php foreach ($category->children as $k2=>$child)://遍历该一级分类的子分类（二级分类）?>
                <dl <?=$k2==0?'class="dl_1st"':''?>>
                    <dt><?=Html::a($child->name,['goods/list','goods_category_id'=>$child->id])?></dt>
                    <dd>
                        <?php foreach ($child->children as $cate)://循环遍历该二级分类的子分类（三级分类）?>
                            <?=Html::a($cate->name,['goods/list','goods_category_id'=>$cate->id])?>
                        <?php endforeach;?>
                    </dd>
                </dl>
            <?php endforeach;?>

        </div>
    </div>
<?php endforeach;?>