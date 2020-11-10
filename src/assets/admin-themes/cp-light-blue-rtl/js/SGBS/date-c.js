// JavaScript Document

$(document).ready(function () {
document.getElementById("datepicker5").addEventListener("input", function(){
	alert("نوشتن مجاز نیست. لطفا از تقویم انتخاب کنید.");
	document.getElementById("datepicker5").value="";
});
document.getElementById("datepicker6").addEventListener("input", function(){
	alert("نوشتن مجاز نیست. لطفا از تقویم انتخاب کنید.");
	document.getElementById("datepicker6").value="";
});

      // Debug mode
        // --------------------------------------------
//        window.persianDatepickerDebug = true;

//         Inline Sample
//         --------------------------------------------
        // Inline Sample
        // --------------------------------------------

        window.from = $("#datepicker5").persianDatepicker({
			
            altField: '#fromAlt',
            altFormat: 'LLLL',
            initialValue: false,
			observer: true,
			autoClose: true,
			calendarType: 'persian',
			timePicker: {
                enabled: false
            },
//		 from.options = {maxDate: unix},
			format: 'YYYY/MM/DD',
			toolbox: {
                calendarSwitch: {
                    enabled: false
                }
            },

//            minDate: new persianDate().subtract('day', 3).valueOf(),
            maxDate: new persianDate().add('day',0).valueOf(),
			onSelect: function (unix) {
			from.touched = true;
			if(from.touched == true){
			document.getElementById("datepicker6").value=null;
			window.to = $("#datepicker6").persianDatepicker({
            altField: '#toAlt',
            altFormat: 'LLLL',
            initialValue: false,
			observer: true,
			autoClose: true,
			calendarType: 'persian',
			timePicker: {
                enabled: false
            },
			
			format: 'YYYY/MM/DD',
			toolbox: {
                calendarSwitch: {
                    enabled: false
                }
            },
 maxDate: new persianDate().add('day',0).valueOf(),
            //minDate: new persianDate().subtract('day', 3).valueOf(),
            //maxDate: new persianDate().add('day', 0).valueOf(),
            onSelect: function (unix) {
                to.touched = true;
                if (from && from.options && from.options.maxDate != unix) {
                    var cachedValue = from.getState().selected.unixDate;
                    from.options = {maxDate: unix};
                    if (from.touched) {
                        from.setDate(cachedValue);
                    }
                }
            }
        });
		}
                if (to && to.options && to.options.minDate != unix) {
                    var cachedValue = to.getState().selected.unixDate;
                    to.options = {minDate: unix};
                    if (to.touched) {
                        to.setDate(cachedValue);
						
                    }
                }
            }
        });

       
  });