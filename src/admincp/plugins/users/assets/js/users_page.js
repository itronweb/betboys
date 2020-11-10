/* ------------------------------------------------------------------------------
 *
 *  # CKEditor editor
 *
 *  Specific JS code additions for editor_ckeditor.html page
 *
 *  Version: 1.0
 *  Latest update: Aug 1, 2015
 *
 * ---------------------------------------------------------------------------- */

$(function () {



	// File input
	$(".file-styled").uniform({
		fileButtonHtml: '<i class="icon-googleplus5"></i>',
		wrapperClass: 'bg-primary'
	});



	// Styled form components

	// Checkboxes, radios



	// --------------------------------------------------------------------


	$(".styled, .multiselect-container input").uniform({
		radioClass: 'choice'
	});


	// --------------------------------------------------------------------

	$('#select-all').click(function () {
		$(':checkbox').prop('checked', this.checked);
	});

	$(".number").on("keypress", function (event) {

		var englishAlphabetAndWhiteSpace = /[0-9]/g;
		var key = String.fromCharCode(event.which);

		if (event.keyCode == 8 || event.keyCode == 37 || event.keyCode == 39 || englishAlphabetAndWhiteSpace.test(key))
			return true;

		return false;
	});


	$(function () {
		var numberInputs = $("input.money");
		var convertToCurrencyDisplayFormat = function (str) {
			var regex = /(-?[0-9]+)([0-9]{3})/;
			str += '';
			while (regex.test(str)) {
				str = str.replace(regex, '$1,$2');
			}
			return str;
		};
		var stripNonNumeric = function (str) {
			str += '';
			str = str.replace(/[^0-9]/g, '');
			return str;
		};

		numberInputs.blur(function () {
			this.value = convertToCurrencyDisplayFormat(this.value);
		});
		numberInputs.focus(function () {
			this.value = convertToCurrencyDisplayFormat(this.value);

		});
		numberInputs.keyup(function () {
			this.value = stripNonNumeric(this.value);
			this.value = convertToCurrencyDisplayFormat(this.value);

		});
		$("form").submit(function () {
			numberInputs.each(function () {
				this.value = stripNonNumeric(this.value);
			});
		});
	});

	 CKEDITOR.replace( 'text', {
        height: '300px',
        extraPlugins: 'forms',
		filebrowserBrowseUrl : '../../filemanager/dialog.php?type=2&editor=ckeditor&fldr=',
		filebrowserUploadUrl : '../../filemanager/dialog.php?type=2&editor=ckeditor&fldr=',
		filebrowserImageBrowseUrl : '../../filemanager/dialog.php?type=1&editor=ckeditor&fldr='
    });



});
