$(document).ready(function () {

    setTimeout(function () { updateScores() }, 15000);

    $('.tab li:eq(0)').click(function () {
        window.location = changeUrl("All");
    });
    $('.tab li:eq(1)').click(function () {
        window.location = changeUrl("Live");
    });
    $('.tab li:eq(2)').click(function () {
        window.location = changeUrl("Finished");
    });
    $('.tab li:eq(3)').click(function () {
        window.location = changeUrl("Scheduled");
    });

});


function changeUrl(option) {
    var url = window.location + '';
    var index = url.toLowerCase().indexOf('/livescore');
    return url.substring(0, index) + "/Livescore/" + option;
}
function updateScores() {
    $.ajax({
        url: "/api/livescore",
        dataType: "json",
        data: { lang: $('#lang').val() },
    }).done(function (data) {
        var goal = false;
        var final = false;
        var error = false;
        var Id, Score, HT, Start, Status, StatusFa, Live;
        $.each(data, function (key, val) {
            $.each(val, function (key1, val1) {
                switch (key1) {
                    case "Id":
                        Id = val1;
                        break;
                    case "Score":
                        Score = val1;
                        break;
                    case "HT":
                        HT = val1;
                        break;
                    case "Start":
                        Start = val1;
                        break;
                    case "Status":
                        Status = val1;
                        break;
                    case "Live":
                        Live = val1;
                        break;
                }
            });
            if ($('.' + Id + ' td:eq(3)').length > 0 && $.trim($('.' + Id + ' td:eq(3) a').html()).length > 1 && $.trim($('.' + Id + ' td:eq(3) a').html()) != Score) {
                $('.' + Id + ' td:eq(3) a').addClass("newgoal");
                setTimeout(function () {
                    $('.livescore tr td a, .livescore tr td').removeClass("newgoal");
                }, 60000);

                var pregoals = $.trim($('.' + Id + ' td:eq(3) a').html()).split(' - ');
                var goals = Score.split(' - ');
                if (parseInt(convertDigitIn(goals[0])) > parseInt(convertDigitIn(pregoals[0])) || parseInt(convertDigitIn(goals[1])) > parseInt(convertDigitIn(pregoals[1]))) {
                    goal = true;
                } else {
                    error = true;
                }

            }
            if ($('.' + Id + ' td:eq(1)').length>0 && $.trim($('.' + Id + ' td:eq(1) span').html()) != Status && (Status == "FT" || Status == "پایان")) {
                $('.' + Id + ' td:eq(1)').addClass("newgoal");
                setTimeout(function () {
                    $('.livescore tr td a, .livescore tr td').removeClass("newgoal");
                }, 60000);
                final = true;
            }
            $('.' + Id + ' td:eq(0)').html(Start);
            if (Live || Status == "Extra" || Status == "وقت اضافه") {
                $('.' + Id + ' td:eq(1)').html('<span class="flash">' + Status + '</span>');
                $('.' + Id + ' td:eq(3) a').html(Score);
            }
            else {
                $('.' + Id + ' td:eq(1)').html('<span class="">' + Status + '</span>');
            }
            if (Status != "" && Status != "Postponed" && Status != "معوق") {
                $('.' + Id + ' td:eq(3) a').html(Score);
            }

            $('.' + Id + ' td:eq(5)').html(HT);
        });
        if (goal)
            makeSound("default");
        else if (error)
            makeSound("error");
        else if (final)
            makeSound("whistleb");

        setTimeout(function () { updateScores() }, 15000);
    });
}


function makeSound(name) {
    $('.livesound').html("<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0' WIDTH='1' HEIGHT='1'><param name='movie' value='/Content/Sounds/" + name + ".swf'><param name='quality' value='high'><embed src='/Content/Sounds/" + name + ".swf' quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash' width='1' height='1'></embed></object>");
}

function convertDigitIn(enDigit) {
    var newValue = "";
    for (var i = 0; i < enDigit.length; i++) {
        var ch = enDigit.charCodeAt(i);
        if (ch >= 1776 && ch <= 1785) {
            var newChar = ch - 1728;
            newValue = newValue + String.fromCharCode(newChar);
        }
        else
            newValue = newValue + String.fromCharCode(ch);
    }
    return newValue;
}