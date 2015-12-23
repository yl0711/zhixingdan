/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
		//config.enterMode = CKEDITOR.ENTER_BR;
		//config.shiftEnterMode = CKEDITOR.ENTER_P; 
		//config.startupMode = 'source';
	// config.uiColor = '#AADC6E';
	
	CKFINDER_PATH = '';
	config.filebrowserBrowseUrl = CKFINDER_PATH+'ckfinder.html';
	config.filebrowserImageBrowseUrl = CKFINDER_PATH+'ckfinder.html?Type=Images';
	config.filebrowserFlashBrowseUrl = CKFINDER_PATH+'ckfinder.html?Type=Flash';
	config.filebrowserUploadUrl = CKFINDER_PATH+editorImgAction+'?command=QuickUpload&type=Files';
	config.filebrowserImageUploadUrl = CKFINDER_PATH+editorImgAction+'?command=QuickUpload&type=Images';
	config.filebrowserFlashUploadUrl = CKFINDER_PATH+editorImgAction+'?command=QuickUpload&type=Flash'	;
	
	config.toolbar = 'Basic';
 
	config.toolbar_Full =
	[
		{ name: 'document', items : [ 'Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates' ] },
		{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
		{ name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
		{ name: 'forms', items : [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'Image', 
			'HiddenField' ] },		
		{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
		{ name: 'colors', items : [ 'TextColor','BGColor' ] },
		'/',
		{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv',
		'-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
		{ name: 'links', items : [ 'Link','Unlink','Anchor' ] },
		{ name: 'tools', items : [ 'Maximize', 'ShowBlocks','-','About' ] },
		{ name: 'insert', items : [ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' ] },
		{ name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
		'/',
		
		
		
	];
	 
	config.toolbar_Basic =
	[
		{ name: 'document', items : [ 'Source' ] },
		{ name: 'clipboard', items : [ 'PasteText','Undo','Redo' ] },
		{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike' ] },
		{ name: 'links', items : [ 'Link','Unlink' ] },
		{ name: 'insert', items : [ 'Image','SpecialChar' ] },'/',
		{ name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
		{ name: 'paragraph', items : [ 'JustifyLeft','JustifyCenter','JustifyRight' ] },
	];	

 
	config.toolbar_MyToolbar =
	[
		{ name: 'document', items : [ 'Source','-','Preview','Print'] },
		{ name: 'clipboard', items : ['PasteText','-','Undo','Redo' ] },
		{ name: 'editing', items : [ 'Find','Replace' ] },
		{ name: 'insert', items : [ 'Image','Table','PageBreak'] },
		'/',
		{ name: 'styles', items : [ 'Format','Font','FontSize' ] },
		{ name: 'colors', items : [ 'TextColor','BGColor' ] },
		{ name: 'basicstyles', items : [ 'Bold','Italic','Strike','-','RemoveFormat' ] },
		{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'] },
		{ name: 'links', items : [ 'Link','Unlink' ] },
		{ name: 'tools', items : [ 'Maximize','ShowBlocks' ] }
	];

	config.height = 260; // 500 pixels.
	//config.width = 470; // 500 pixels.
};
