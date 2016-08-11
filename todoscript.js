$(function(){
	$("#add").on('click', addListItem);
	$("#todolist").on('click', ".delete", deleteClosestItem);
	$("#todolist").on('click', ".done", finishItem);
	$('#new-text').on("keypress", function(e) {
        if (e.keyCode == 13) {
        	addListItem();
            return false;
        }
});
});

function deleteClosestItem(){
	//alert('Deleting closest item...');
	$(this).closest('li').remove();	
}

function finishItem(){
	if($(this).parent().css('textDecoration') == 'line-through' ){
		$(this).parent().css('textDecoration','none');
	}else{
		$(this).parent().css('textDecoration','line-through');
	}	
}

function addListItem() {
	//alert('Adding to list...');

	var text = $("#new-text").val();
	if(text == ''){
		alert('You did not enter a task!');
	}else{
		$("#todolist").append('<li><input type="checkbox" class = "done">'+text+' <button class=delete>Delete</button></li>');
		$("#new-text").val('');
	}
}

