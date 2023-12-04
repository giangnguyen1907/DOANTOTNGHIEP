/* web/js/eventform.js */
newfieldscount = 0;

function addFieldDetail(num, url){
  return $.ajax({
    type: 'GET',
    url: url,
    data: 'num=' + num,
    async: false
  }).responseText;
}

function addNewField(num){
  return $.ajax({
    type: 'GET',
    url: 'add?num='+num,
    async: false
  }).responseText;
}

// add: newfieldscount = newfieldscount - 1;
var removeNew = function(){
  $('.action_delete').click(function(e){
    e.preventDefault();
    $(this).parent().parent().remove();
    if (newfieldscount > 0)
		newfieldscount = newfieldscount - 1;
  })
};


$(function(){
  
  $('#adddetail').click(function(e){
	
	e.preventDefault();
    
    $('.sf_admin_list table#tb').append(addNewField(newfieldscount));
    
    newfieldscount = newfieldscount + 1;
    
    $('.removenew').unbind('click');
    
    removeNew();
   
  });
  
});