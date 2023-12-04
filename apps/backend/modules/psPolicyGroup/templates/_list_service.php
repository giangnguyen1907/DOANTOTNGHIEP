<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"> 
<?php 
$psListService = Doctrine_Query::create()
  ->select('s.*')
  ->from('Service s')
  ->addwhere('s.is_activated = 1')
  ->addwhere('s.enable_roll = 1')
  ->addwhere('s.is_kidsschool = 1')
  ->execute();

if (!$form->isNew()) {

  $list_ser = $form->getObject()->getJsonService();

  $array_ser = array();
  if($list_ser){
    $item_ser = json_decode($list_ser, true);
    $array_ser = (array)$item_ser;
  }
}
?>
<ul class="checkbox_list" style="min-width:100px;">
  <?php if (count($psListService) > 0) { ?>
    <li class="choise_all" style="min-width:300px;">
      <label>
        <input class="checkbox check-all" type="checkbox" value="0" />
        <span>Chọn tất cả</span>
      </label>
      <hr>
    </li>
  <?php } ?>
    <li class="choise_all" style="min-width:300px;">
      <p><b>Ghi chú: </b> Giá trị nhập nhỏ hơn <b>100</b> thì sẽ được hiểu là giảm theo %. Còn lớn hơn <b>100</b> thì sẽ giảm trừ tiền mặt</p>
    </li>
  <?php
    foreach ($psListService as $list) {
      $checked = '';
      $disabled = 'disabled';
      if(isset($array_ser[$list->getId()])){
        $checked =  'checked';
        $disabled = '';
      }
    ?>
      <li class="select2" style="min-width:400px; padding-bottom: 10px;">

        <input class="select2 item_check" style="min-width:100px;" type="checkbox" id="check_service_<?php echo $list->getId() ?>" onclick="checkService(<?php echo $list->getId() ?>)" <?php echo $checked; ?>>&nbsp;

        <input placeholder="Nhập số tiền (hoặc %) giảm trừ" class="item_number_hssv" style="min-width:250px;" name="ps_policy_group[json_service][<?php echo $list->getId() ?>]" type="number" id="input_service_<?php echo $list->getId() ?>" value="<?php echo $array_ser[$list->getId()] ? $array_ser[$list->getId()] : '' ?>" <?php echo $disabled; ?> >&nbsp;

        <label class="select2" style="min-width:200px;" for="ps_student_list_hssv_id_dichvu">
          <b><?php echo $list->getTitle() ?></b>
        </label>
      </li>
    <?php } ?>
</ul>
</div>
<script>
 function checkService(id){
    if ($('#check_service_'+id).is(":checked")) {
      $('#input_service_'+id).attr("disabled", false);
    }else{
      $('#input_service_'+id).attr("disabled", true);
    }

    $('#check_service_'+id).change(function() {
      if ($('#check_service_'+id).is(":checked")) {
        $('#input_service_'+id).attr("disabled", false);
      }else{
        $('#input_service_'+id).attr("disabled", true);
      }
    });
  }

  $(document).ready(function() {
    $('.check-all').on('change', function() {
      if ($(".check-all:checked").val() == 0) {
        $('.item_check').prop('checked', true);
        $('.item_number_hssv').attr("disabled", false);
      } else {
        $('.item_check').prop('checked', false);
        $('.item_number_hssv').attr("disabled", true);
      }
    });

  });
</script>
<style type="text/css">
  .item_number_hssv{
/*    max-width: 50px;*/
/*    width: 50px;*/
    height: 30px;
    padding: 2px 10px;
  }
</style>