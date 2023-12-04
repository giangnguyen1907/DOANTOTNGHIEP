<?php
$index = 0;
if (!$form->isNew()) {
  $media = $form->getObject()->getMedia();
  
  if($media){
    $json_media = explode(';', trim($media, ''));
    $index = count($json_media);
  }
}
?>
<div class="row">
  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
      <div class="form-group sf_admin_form_row sf_admin_text sf_admin_form_field_media">
        <label class="col-md-3 control-label" for="ps_album_media">File media</label> 
        <div class="col-md-9">
        <button type="button" onclick="openLoadImages(this,'<?php echo sfConfig::get('app_admin_module_web_dir');?>kstools/browse.php?type=files','media')" class="btn btn-default btn-primary btn-sm btn-psadmin"> Chọn file</button>
      </div>
      </div>
  </div>
  <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
    <div class="form-group sf_admin_form_row sf_admin_text sf_admin_form_field_media">
      <?php if($form->isNew()){ ?>
        <label class="col-md-3 control-label" for="ps_album_media" id="lable"></label>
        <div class="col-md-9" id="filePathsContainer_media">
        </div>
      <?php }else{
        
        ?>
        <label class="col-md-3 control-label" for="ps_album_media" id="lable">Danh sách file:</label>
        <div class="col-md-9" id="filePathsContainer_media">
          <?php $i = 0;
          foreach($json_media as $key => $item_media) {
           ?>
            <div class="input-row" id="inputDiv_<?=$i?>">
              <input type="text" name="ps_lesson[media][<?=$i?>]" onclick="openKCFinderForInput(this,'<?php echo sfConfig::get('app_admin_module_web_dir');?>kstools/browse.php?type=files')" class="form-control" readonly="" style="width: 90%;" value="<?=$item_media?>">
              <button class="btn btn-danger btn-sm" type="button" onclick="removeInputRow('inputDiv_<?=$i?>','media')">Xóa</button>
            </div>
          <?php $i++; } ?>
        </div>
      <?php } ?>
    </div>
  </div>
</div>
<style>
  .input-row {
    display: flex;
    align-items: center;
  }
  .delete-button {
    margin-left: 10px;
  }
</style>

<script type="text/javascript">
  var index = <?=$index?>;
  function openLoadImages(field, add_url, nameFile) {
    window.KCFinder = {
      callBackMultiple: function(urls) {

          const container = document.getElementById("filePathsContainer_"+nameFile);

          const lable = document.getElementById("lable");
          lable.innerHTML = 'Danh sách file: ';

          for (const url of urls) {
            const inputDiv = document.createElement("div"); // Tạo một div bao gồm thẻ input và nút xóa
            inputDiv.className = "input-row"; // Thêm lớp tùy chỉnh cho div
            inputDiv.id = "inputDiv_" + index;
            const input = document.createElement("input");
            input.type = "text";
            input.name = "ps_album["+nameFile+"]["+index+ "]";
            input.className = "form-control";
            input.readOnly = true; // Thêm thuộc tính readonly
            input.style.width = "90%"; // Thêm kiểu dáng width
            input.value = url;
            input.addEventListener("click", function() {
              openKCFinderForInput(this,'<?php echo sfConfig::get('app_admin_module_web_dir');?>kstools/browse.php?type=files');
            });

            // container.appendChild(input);
            inputDiv.appendChild(input);

            const deleteButton = document.createElement("button");
            deleteButton.textContent = "Xóa";
            deleteButton.className = "btn btn-danger btn-sm"; // Thêm lớp cho nút xóa
            deleteButton.addEventListener("click", function() {
              container.removeChild(inputDiv);
            });
            inputDiv.appendChild(deleteButton);

            container.appendChild(inputDiv);

            index++;
          }
          window.KCFinder = null; // Đóng cửa sổ KCFinder
        },
      relative_urls: false,
      remove_script_host: false,
      convert_urls: true
    };
    window.open(add_url, 'kcfinder_textbox','inline=1, resizable=1, scrollbars=0, width=800, height=600');
  }

  function removeInputRow(divId,nameFile) {
    const container = document.getElementById("filePathsContainer_"+nameFile);
    const inputDiv = document.getElementById(divId);
    container.removeChild(inputDiv); // Xóa div chứa thẻ input
  }

  function openKCFinderForInput(field, add_url) {
    window.KCFinder = {
      callBack: function(url) {
        field.value = url;              
        window.KCFinder = null;                       
      },
      relative_urls: false,
      remove_script_host: false,
      convert_urls: true
    };
    window.open(add_url, 'kcfinder_textbox','inline=1, resizable=1, scrollbars=0, width=800, height=600');
  }
  </script>