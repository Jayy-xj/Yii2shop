<!-- 页面主体 start -->
<div class="main w1210 bc mt10">
    <div class="crumb w1210">
        <h2><strong>我的XX </strong><span>> 我的订单</span></h2>
    </div>

    <!-- 左侧导航菜单 start -->
    <div class="menu fl">
        <h3>我的XX</h3>
        <div class="menu_wrap">
            <dl>
                <dt>订单中心 <b></b></dt>
                <dd><b>.</b><a href="">我的订单</a></dd>
                <dd><b>.</b><a href="">我的关注</a></dd>
                <dd><b>.</b><a href="">浏览历史</a></dd>
                <dd><b>.</b><a href="">我的团购</a></dd>
            </dl>

            <dl>
                <dt>账户中心 <b></b></dt>
                <dd class="cur"><b>.</b><a href="">账户信息</a></dd>
                <dd><b>.</b><a href="">账户余额</a></dd>
                <dd><b>.</b><a href="">消费记录</a></dd>
                <dd><b>.</b><a href="">我的积分</a></dd>
                <dd><b>.</b><a href="">收货地址</a></dd>
            </dl>

            <dl>
                <dt>订单中心 <b></b></dt>
                <dd><b>.</b><a href="">返修/退换货</a></dd>
                <dd><b>.</b><a href="">取消订单记录</a></dd>
                <dd><b>.</b><a href="">我的投诉</a></dd>
            </dl>
        </div>
    </div>
    <!-- 左侧导航菜单 end -->

    <!-- 右侧内容区域 start -->
    <div class="content fl ml10">
        <div class="address_hd">
            <h3>收货地址薄</h3>
            <?php
            $conut=1;
            foreach ($queres as $query){

                echo '<dl>';
                echo '<dt>'.$conut++.'.'.$query->username.'&nbsp;'.\frontend\models\Locations::getProvince($query->province).'&nbsp;'.\frontend\models\Locations::getCity($query->city).'&nbsp;'.\frontend\models\Locations::getDistrict($query->district).'&nbsp;'.$query->detail.'&nbsp;'.$query->tel.'</dt>';
                echo '<dd>';
                echo \yii\helpers\Html::a('修改',['edit','id'=> $query->id]).'&nbsp;';
                echo \yii\helpers\Html::a('删除',['delete','id'=> $query->id]).'&nbsp;';
                echo \yii\helpers\Html::a('设为默认地址',['default','id'=> $query->id]);
                echo '</dd>';
                echo '</dl>';
            }
            ?>
        </div>

        <div class="address_bd mt10">
            <h4>新增收货地址</h4>
            <?php
            //注册表单  不需要使用bootstrap样式，所以使用\yii\widgets\ActiveForm
            $form = \yii\widgets\ActiveForm::begin(
                ['fieldConfig'=>[
                    'options'=>[
                        'tag'=>'li',
                    ],
                    'errorOptions'=>[
                        'tag'=>'p'
                    ]
                ]]
            );
            echo '<ul>';
            echo $form->field($model,'username')->textInput(['class'=>'txt']);//用户名
            echo $form->field($model, 'district')->widget(\chenkby\region\Region::className(),[
                'model'=>$model,
                'url'=> \yii\helpers\Url::toRoute(['get-region']),
                'province'=>[
                    'attribute'=>'province',
                    'items'=>\frontend\models\Locations::getRegion(),
                    'options'=>['class'=>'form-control form-control-inline','prompt'=>'选择省份']
                ],
                'city'=>[
                    'attribute'=>'city',
                    'items'=>\frontend\models\Locations::getRegion($model['province']),
                    'options'=>['class'=>'form-control form-control-inline','prompt'=>'选择城市']
                ],
                'district'=>[
                    'attribute'=>'district',
                    'items'=>\frontend\models\Locations::getRegion($model['city']),
                    'options'=>['class'=>'form-control form-control-inline','prompt'=>'选择县/区']
                ]
            ]);

            echo $form->field($model,'detail')->textInput(['class'=>'txt']);//详细地址
            echo $form->field($model,'tel')->textInput(['class'=>'txt']);//电话
            echo $form->field($model, 'defaultMe')->checkbox();
            echo '<li>
								<label for="">&nbsp;</label>
								<input type="submit" name="" class="btn" value="保存">
							</li>';
            echo '</ul>';

            \yii\widgets\ActiveForm::end();
            ?>
        </div>

    </div>
    <!-- 右侧内容区域 end -->
</div>
<!-- 页面主体 end-->