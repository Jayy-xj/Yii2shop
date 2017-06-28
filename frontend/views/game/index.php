<a href="<?=\yii\helpers\Url::to(['game/start'])?>">点此开始游戏</a>


<form action="<?=\yii\helpers\Url::to(['game/check'])?>  " method="post">
    <input type="text" name="num1" /><br/>
    <input type="text" name="num2" /><br/>
    <input type="text" name="num3" /><br/>
    <input type="text" name="num4" /><br/>
    <input type="submit" value="验证"/>
    <input name="_csrf-frontend" type="hidden" id="_csrf-frontend" value="<?= Yii::$app->request->csrfToken ?>">
</form>
