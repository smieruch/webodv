$(document).ready(function() {

    //localStorage.removeItem('field1');

    var inputs = $(document).find('input');

    $.each(inputs, function(i,e){
	var the_id = $(this).attr('id');
	//console.log(the_id)

	if (localStorage.getItem(the_id) != null){
    	    $('#'+the_id).val(localStorage.getItem(the_id));
	}
	
	$('#'+the_id).on('change',function(){
    	    localStorage.setItem(the_id,$(this).val());
	});
	
    });
    


})
