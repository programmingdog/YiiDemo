<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap4\Modal;

/* @var $this yii\web\View */
/* @var $model app\models\PostSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-search">
    <?php $form = ActiveForm::begin([
        'action' => ['supplier'],
        'method' => 'get',
        'options' => ['class'=>'form-inline'],
    ]); ?>
    <span class="margin-rt-small">Id</span>
    <?= $form->field($model, 'id_operator',['inputOptions' => ['class' => 'search-input-lg form-control'],'options'=>['class'=>'form-inline form-field']])->dropDownList(['>'=>'>','>='=>'>=','='=>'=','<'=>'<','<='=>'<='], ['prompt'=>'select operator','style'=>'width:120px'])->label('') ?>
    <?= $form->field($model, 'id',['inputOptions' => ['class' => 'search-input form-control'],'options'=>['class'=>'form-inline form-field']])->label('') ?>
    
    <?= $form->field($model, 'name',['labelOptions' => ['class' => 'margin-rt-small'],'inputOptions' => ['class' => 'search-input form-control'],'options'=>['class'=>'form-inline form-field']]) ?>

    <?= $form->field($model, 'code',['labelOptions' => ['class' => 'margin-rt-small'],'inputOptions' => ['class' => 'search-input form-control'],'options'=>['class'=>'form-inline form-field']]) ?>
    <?= $form->field($model, 't_status',['labelOptions' => ['class' => 'margin-rt-small'],'inputOptions' => ['class' => 'search-input-lg form-control'],'options'=>['class'=>'form-inline form-field']])->dropDownList(['ok'=>'ok','hold'=>'hold'], ['prompt'=>'please select','style'=>'width:120px']) ?>
    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::button('Export', ['class' => 'btn btn-success margin-lf-small','id' => 'export-btn','data-toggle'=>'modal','data-target'=>"#export-modal"]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<?php 
    Modal::begin([
        'id' => 'export-modal',
        'title' => '<h4 class="modal-title">Export CSV</h4>',
        'footer' => '<a href="#" class="btn btn-danger" data-dismiss="modal">Close</a><button id="export" class="btn btn-success">Export</button>',
    ]); 
    echo '
        <div style="margin-bottom:10px;font-size:18px;color:#B0B0B0;">
            <span>Please select export field.</span>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" name="export_type" type="checkbox" value="id" checked="true" id="id_check" disabled>
          <label class="form-check-label" for="id_check">
            Id
          </label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" name="export_type" type="checkbox" value="name" checked="true" id="name_check">
          <label class="form-check-label" for="name_check">
            Name
          </label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" name="export_type" type="checkbox" value="code" checked="true" id="code_check">
          <label class="form-check-label" for="code_check">
            Code
          </label>
        </div>
        <div class="form-check form-check-inline">
          <input class="form-check-input" name="export_type" type="checkbox" value="t_status" checked="true" id="t_status">
          <label class="form-check-label" for="t_status">
            T_Status
          </label>
        </div>
    ';
    $this->registerJs(
    '
        //导出
        $("#export").click(()=>{
            let checkList = [];
            $("input[name=export_type]:checked").each(function(){
        　　    checkList.push($(this).val());
        　　});
        　　let url = decodeURIComponent("/index.php?r=site/export" + window.location.href.substring(window.location.href.indexOf("&"))) + "&checkType=" + checkList + "&selectedRow=" + selectedItems;
        　　window.open(url);
            console.log(checkList)
        });
    '    
    );
    Modal::end();
?>