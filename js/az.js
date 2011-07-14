/**
 * shows a model window containing the form to register
 */
function modal_register() {

	// reset fields to null
	$('#modal_register input:not(:hidden)').each(function() {
		$(this).val('');
	});
	
	// create jQueryUI dialog
	$("#modal_register").dialog({
		width:		500,
		modal:		true,
		resizable:	false,
		title:		'Register',
		buttons: {
			'Continue': function() {
				$("#form_register").submit();
			},
			'Cancel': function() {
				$(this).dialog("close");
			}
		}
	});
	
	// convert textareas into tinyMCE editors
	tinyMCE.init({
        mode : "textareas",
        theme : "simple"
	});
}

/**
 * shows a modal window when executing an action (eg: ajax) to block the navigation in the meanwhile
 */
function showDisplayOperation()
{
	$("#waiting_operation").dialog({
		modal:			true,
		closeOnEscape:	false,
		resizable:		false,
		title:			'Executing operation...',
	});
}

/**
 * hides the modal window showed by showDisplayOperation()
 */
function hideDisplayOperation()
{
	$("#waiting_operation").dialog('destroy');
}

/**
 * deletes a photo (via ajax)
 * 
 * @param photo_id id of the photo in database
 * @param url url for deleting a photo
 * @returns
 */
function delete_photo(photo_id, url)
{
	var photo_tag = $('#photo_'+photo_id);
	
	// performs ajax call
	$.ajax({
		type: 'POST',
		url: url,
		data:
		{
			'photo_id': photo_id
		},
		beforeSend: function()
		{
			// show modal window
			showDisplayOperation();
		},
		success: function()
		{
			// delete image tag
			photo_tag.remove();
			
			// hide modal window
			hideDisplayOperation();
		}
	});
}

/**
 * retrieves a photo tag (via ajax)
 * 
 * @param photo_id id of the photo in database
 * @param url url of the photo
 * @returns
 */
function getAjaxPhoto(photo_id, url)
{
	// performs ajax call
	$.ajax({
	  type: 'POST',
	  url: 	url,
	  data:
	  {
	  	'photo_id': photo_id
	  },
	  success: function (html)
	  {
		  // appends new tag to container
		  $('.photos_list').append(html);
	  },
	});
}