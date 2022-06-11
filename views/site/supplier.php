<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap4\ActiveForm $form */
/** @var app\models\ContactForm $model */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
$this->title = 'Supplier';
$this->params['breadcrumbs'][] = $this->title;
?>
<?= $this->render('supplier-search', ['model' => $searchModel]) ?>
<div class="select-all-tips" id="select-all-tips" style="display:none;">
    <div class="alert alert-warning" role="alert" style="margin-bottom:10px;">
      <div class="row">
          <div class="col-md-1">
              <strong>Tips：</strong>
          </div>
          <div class="col-md-5">
              <span>Total <b id="currPageCount">10</b> conversations have been selected</span>
          </div>
          <div class="col-md-6 text-right">
              <a id="operateSelectAll" href="javascript:void(0);">Select all conversations that match this search</a>
          </div>
      </div>
    </div>
</div>
<?php Pjax::begin(['id' => 'pjax-grid-view']); ?>
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'options' => ['id' => 'grid_supplier'],
    'tableOptions' => ['id'=>'table_supplier','class'=>'table table-striped table-bordered table-hover'],
    'layout'=>'{items}
        <div class="row" style="margin-left:0px!important;">
            <div class="col-md-6 col-xs-6">
                {pager}
            </div>
            <div class="col-md-6 col-xs-6">
                <div class="text-right">
                {summary}
                </div>
            </div>
        </div>
    ',
    "summary" =>"Showing <b>{begin}-{end}</b> of <b>{totalCount}</b> items Total <b>{pageCount}</b> pages",
    'headerRowOptions' => ['class'=>'gridview-table-header'],
    'columns' => [
        [
            'class' => 'yii\grid\CheckboxColumn',
            'headerOptions' => ['style' => 'text-align:center;', 'width' => '80'],
            'contentOptions' => ['style' => 'text-align:center;'],
            'checkboxOptions' => function($data){
                return ['id' => $data->id, 'onClick' => 'selectedRow(this)'];
            }
        ],
        [
            'attribute' => 'id',
            'headerOptions' => ['style' => 'text-align:center;', 'width' => '120'],
            'contentOptions' => ['style' => 'text-align:center;'],
        ],
        [
            'attribute' => 'name',
            'headerOptions' => ['style' => 'text-align:center;'],
            'contentOptions' => ['style' => 'text-align:center;'],
        ],
        [
            'attribute' => 'code',
            'headerOptions' => ['style' => 'text-align:center;', 'width' => '150'],
            'contentOptions' => ['style' => 'text-align:center;'],
        ],
        [
            'attribute' => 't_status',
            'headerOptions' => ['style' => 'text-align:center;', 'width' => '150'],
            'contentOptions' => ['style' => 'text-align:center;'],
        ],
    ],
]);
?>
<?php 
$this->registerJs(
   '$("document").ready(function(){ 
        $("#pjax-grid-view").on("pjax:end", function() {
            if(isSelectAllRecordCrossPage){
                let tableRows = $("#table_supplier")[0].rows;
                let rowNum = tableRows.length;
                for(i=1;i<rowNum;i++){
                    let id = tableRows[i].getAttribute("data-key");
                    if(!selectedItems.includes(id)) {
                        selectedItems.push(id);
                    }
                }
            }
            $.each(selectedItems, function (index,value) {
                $("#"+value).attr("checked",true);
            });
            
        });
    });
    //全选按钮事件
    $(".select-on-check-all").click(function(){
        let tableRows = $("#table_supplier")[0].rows;
        let rowNum = tableRows.length;
        if($(this)[0].checked){
            //选中状态
            for(i=1;i<rowNum;i++){
                let id = tableRows[i].getAttribute("data-key");
                if(!selectedItems.includes(id)) {
                    selectedItems.push(id);
                }
            }
            $("#currPageCount").text(selectedItems.length);
        }else{
            //取消状态
            for(i=1;i<rowNum;i++){
                let id = tableRows[i].getAttribute("data-key");
                for(j=0;j<selectedItems.length;j++){
                    if(selectedItems[j]==id){
                        selectedItems.splice(j,1);
                        break;
                    }
                }
            }
            $("#currPageCount").text(selectedItems.length);
        }
        toggleSelectAllTips();
    })
    '
);
?>
<?php Pjax::end(); ?>

<script type="text/javascript" src="assets/8021e5f3/jquery.min.js" charset="{$app->charset}"></script>
<script>
//定义选中项数组
let selectedItems=[];
//是否选中所有跨页数据
let isSelectAllRecordCrossPage = false;

//跨页全选
$("#operateSelectAll").click(()=>{
    console.log("跨页全选");
    isSelectAllRecordCrossPage = !isSelectAllRecordCrossPage;
    handleSelectLinkClick();
})

function selectedRow(ele){
    if(ele.checked) {
        if(!selectedItems.includes(ele.id)) {
            selectedItems.push(ele.id);
        }
    } else {
        for(i=0;i<selectedItems.length;i++){
            if(selectedItems[i]==ele.id){
                selectedItems.splice(i,1);
                break;
            }
        }
    }
    $("#currPageCount").text(selectedItems.length);
    toggleSelectAllTips();
}

function toggleSelectAllTips(){
    if(selectedItems.length>0){
        $("#select-all-tips").css("display","block");
    }else{
        $("#select-all-tips").css("display","none");
    }
}

function handleSelectLinkClick(){
    if(isSelectAllRecordCrossPage){
        //select all
        checkAllCrossPage();
        $("#operateSelectAll").text("clear selection");
    }else{
        //clear all
        unCheckAllCrossPage();
        $("#operateSelectAll").text("Select all conversations that match this search");
    }
}
function unCheckAllCrossPage(){
    if(selectedItems.length>0){
        let tableRows = $("#table_supplier")[0].rows;
        let rowNum = tableRows.length;
        $.each(tableRows, (index,value)=> {
            value.cells[0].firstChild.checked = false;
        });
    }
    selectedItems = [];
    $("#currPageCount").text(selectedItems.length);
}
function checkAllCrossPage(){
    let tableRows = $("#table_supplier")[0].rows;
    let rowNum = tableRows.length;
    for(i=1;i<rowNum;i++){
        let id = tableRows[i].getAttribute("data-key");
        if(!selectedItems.includes(id)) {
            selectedItems.push(id);
        }
    }
    $("#currPageCount").text("<?php echo $totalCount;?>");
    if(selectedItems.length>0){
        $.each(selectedItems, (index,value)=> {
            $("#"+value)[0].checked = true;
        });
    }
}
</script>
