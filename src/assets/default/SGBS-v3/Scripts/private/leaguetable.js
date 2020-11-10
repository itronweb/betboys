$(document).ready(function () {
    $('.countrydropdown').easyDropDown({
        cutOff: 8,
        wrapperClass: 'dropdown countrydropdown',
        onChange: function (selected) {
            window.location = selected.value;
        }
    });

    $('.leaguedropdown').easyDropDown({
        cutOff: 8,
        wrapperClass: 'dropdown leaguedropdown',
        onChange: function (selected) {
            window.location=selected.value;
        }
    });
    $('.seasondropdown').easyDropDown({
        cutOff: 8,
        wrapperClass: 'dropdown seasondropdown',
        onChange: function (selected) {
            window.location=selected.value;
        }
    });

    $('.rounddropdown').easyDropDown({
        cutOff: 8,
        wrapperClass: 'dropdown rounddropdown',
        onChange: function (selected) {
            window.location = selected.value;
        }
    });

    $('.weekdropdown').easyDropDown({
        cutOff: 8,
        wrapperClass: 'dropdown weekdropdown',
        onChange: function (selected) {
            window.location = selected.value;
        }
    });
    
});