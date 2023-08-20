$(function() {
    "use strict";

    $('.knob2').knob({
        'format' : function (value) {
            return value + '%';
         }
    });

    
  

    // progress bars
    $('.progress .progress-bar').progressbar({
            display_text: 'none'
    }); 

    // Visitors Statistics =============
    var d = [[1196463600000, 0], [1196550000000, 0], [1196636400000, 0], [1196722800000, 77], [1196809200000, 3636], [1196895600000, 3575], [1196982000000, 2736], [1197068400000, 1086], [1197154800000, 676], [1197241200000, 1205], [1197327600000, 906], [1197414000000, 710], [1197500400000, 639], [1197586800000, 540], [1197673200000, 435], [1197759600000, 301], [1197846000000, 575], [1197932400000, 481], [1198018800000, 591], [1198105200000, 608], [1198191600000, 459], [1198278000000, 234], [1198364400000, 1352], [1198450800000, 686], [1198537200000, 279], [1198623600000, 449], [1198710000000, 468], [1198796400000, 392], [1198882800000, 282], [1198969200000, 208], [1199055600000, 229], [1199142000000, 177], [1199228400000, 374], [1199314800000, 436], [1199401200000, 404], [1199487600000, 253], [1199574000000, 218], [1199660400000, 476], [1199746800000, 462], [1199833200000, 448], [1199919600000, 442], [1200006000000, 403], [1200092400000, 204], [1200178800000, 194], [1200265200000, 327], [1200351600000, 374], [1200438000000, 507], [1200524400000, 546], [1200610800000, 482], [1200697200000, 283], [1200783600000, 221], [1200870000000, 483], [1200956400000, 523], [1201042800000, 528], [1201129200000, 483], [1201215600000, 452], [1201302000000, 270], [1201388400000, 222], [1201474800000, 439], [1201561200000, 559], [1201647600000, 521], [1201734000000, 477], [1201820400000, 442], [1201906800000, 252], [1201993200000, 236], [1202079600000, 525], [1202166000000, 477], [1202252400000, 386], [1202338800000, 409], [1202425200000, 408], [1202511600000, 237], [1202598000000, 193], [1202684400000, 357], [1202770800000, 4414], [1202857200000, 3393], [1202943600000, 2353], [1203030000000, 1364], [1203116400000, 215], [1203202800000, 214], [1203289200000, 356], [1203375600000, 5599], [1203462000000, 1334], [1203548400000, 1348], [1203634800000, 1243], [1203721200000, 1126], [1203807600000, 1157], [1203894000000, 5288]];
    // first correct the timestamps - they are recorded as the daily
    // midnights in UTC+0100, but Flot always displays dates in UTC
    // so we have to add one hour to hit the midnights in the plot
    for (var i = 0; i < d.length; ++i) {
        d[i][0] += 60 * 60 * 1000;
    }
    // helper for returning the weekends in a period
    function weekendAreas(axes) {

        var markings = [],
            d = new Date(axes.xaxis.min);

        // go to the first Saturday

        d.setUTCDate(d.getUTCDate() - ((d.getUTCDay() + 1) % 7))
        d.setUTCSeconds(0);
        d.setUTCMinutes(0);
        d.setUTCHours(0);

        var i = d.getTime();

        // when we don't set yaxis, the rectangle automatically
        // extends to infinity upwards and downwards

        do {
            markings.push({ xaxis: { from: i, to: i + 2 * 24 * 60 * 60 * 1000 } });
            i += 7 * 24 * 60 * 60 * 1000;
        } while (i < axes.xaxis.max);

        return markings;
    }
    var options = {
        xaxis: {
            mode: "time",
            tickLength: 5
        },
        selection: {
            mode: "x"
        },
        grid: {
            markings: weekendAreas,
            borderColor: '#eaeaea',
            tickColor: '#eaeaea',
            hoverable: true,                           
            borderWidth: 1,
        }
    };
    var plot = $.plot("#Visitors_chart", [d], options);
    // now connect the two
    $("#Visitors_chart").bind("plotselected", function (event, ranges) {

        // do the zooming
        $.each(plot.getXAxes(), function(_, axis) {
            var opts = axis.options;
            opts.min = ranges.xaxis.from;
            opts.max = ranges.xaxis.to;
        });
        plot.setupGrid();
        plot.draw();
        plot.clearSelection();

        // don't fire event on the overview to prevent eternal loop

        overview.setSelection(ranges, true);
        
    });
    // Add the Flot version string to the footer
    $("#footer").prepend("Flot " + $.plot.version + " &ndash; ");
    // Visitors Statistics ============= end
    
});

$(function() {
    "use strict";
    var options;

    var data = {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        series: [{
            name: 'series-real',
            data: [200, 289, 263, 278, 320, 450, 359, 400, 369, 479, 628, 530],
        }, {
            name: 'series-projection',
            data: [240, 502, 360, 380, 505, 520, 590, 523, 600, 650, 790, 1020],            
        }]
    };

    // area chart
    options = {
        height: "255px",
        showArea: false,
        showLine: true,
        showPoint: true,
        axisX: {
            showGrid: false
        },
        axisY: {
            labelInterpolationFnc: function(value) {
                return (value / 1000) + 'k';
            }
        },
        lineSmooth: true,
    };
    new Chartist.Line('#Sales_Overview', data, options);

});



// ---------------------------------------------------------------------
// ----------------------------------------------------------------------

!function(a){"use strict";a.sessionTimeout=function(b){function c(){n||(a.ajax({type:i.ajaxType,url:i.keepAliveUrl,data:i.ajaxData}),
n=!0,setTimeout(function(){n=!1},i.keepAliveInterval))}function d(){clearTimeout(g),(i.countdownMessage||i.countdownBar)&&f("session",!0),
"function"==typeof i.onStart&&i.onStart(i),i.keepAlive&&c(),g=setTimeout(function(){"function"!=typeof i.onWarn?a("#session-timeout-dialog").modal("show")
:i.onWarn(i),e()},i.warnAfter)}function e(){clearTimeout(g),
    a("#session-timeout-dialog").hasClass("in")||!i.countdownMessage&&!i.countdownBar||f("dialog",!0),g=setTimeout(function(){"function"!=typeof
     i.onRedir?window.location=i.redirUrl:i.onRedir(i)},i.redirAfter-i.warnAfter)}function f(b,c){clearTimeout(j.timer),
        "dialog"===b&&c?j.timeLeft=Math.floor((i.redirAfter-i.warnAfter)/1e3)
        :"session"===b&&c&&(j.timeLeft=Math.floor(i.redirAfter/1e3)),i.countdownBar&&
        "dialog"===b?j.percentLeft=Math.floor(j.timeLeft/((i.redirAfter-i.warnAfter)/1e3)*100)
        :i.countdownBar&&"session"===b&&(j.percentLeft=Math.floor(j.timeLeft/(i.redirAfter/1e3)*100))
        ;var d=a(".countdown-holder"),e=j.timeLeft>=0?j.timeLeft:0;if(i.countdownSmart){var g=Math.floor(e/60),
            h=e%60,k=g>0?g+"m":"";k.length>0&&(k+=" "),k+=h+"s",d.text(k)}else d.text(e+"s");i.countdownBar
            &&a(".countdown-bar").css("width",j.percentLeft+"%"),j.timeLeft=j.timeLeft-1,j.timer=setTimeout(function(){f(b)},1e3)}
            var g,h={title:"Your Session is About to Expire!",message:"Your session is about to expire.",
            logoutButton:"Logout",keepAliveButton:"Stay Connected",keepAliveUrl:"/keep-alive",
            ajaxType:"POST",ajaxData:"",redirUrl:"/timed-out",logoutUrl:"/log-out",warnAfter:9e5,redirAfter:12e5,
            keepAliveInterval:5e3,keepAlive:!0,ignoreUserActivity:!1,onStart:!1,onWarn:!1,onRedir:!1,countdownMessage:!1,countdownBar:!1,countdownSmart:!1},i=h,j={};
            if(b&&(i=a.extend(h,b)),i.warnAfter>=i.redirAfter)return 
            console.error('Bootstrap-session-timeout plugin is miss-configured. Option "redirAfter" must be equal or greater than "warnAfter".'),!1;if("function"!=typeof i.onWarn){var k=i.countdownMessage?"<p>"+i.countdownMessage.replace(/{timer}/g,'<span class="countdown-holder"></span>')+"</p>":"",l=i.countdownBar?'<div class="progress">                   <div class="progress-bar progress-bar-striped countdown-bar active" role="progressbar" style="min-width: 15px; width: 100%;">                     <span class="countdown-holder"></span>                   </div>                 </div>':"";a("body").append('<div class="modal fade" id="session-timeout-dialog">               <div class="modal-dialog">                 <div class="modal-content">                   <div class="modal-header">                     <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>                     <h4 class="modal-title">'+i.title+'</h4>                   </div>                   <div class="modal-body">                     <p>'+i.message+"</p>                     "+k+"                     "+l+'                   </div>                   <div class="modal-footer">                     <button id="session-timeout-dialog-logout" type="button" class="btn btn-default">'+i.logoutButton+'</button>                     <button id="session-timeout-dialog-keepalive" type="button" class="btn btn-primary" data-dismiss="modal">'+i.keepAliveButton+"</button>                   </div>                 </div>               </div>              </div>"),a("#session-timeout-dialog-logout").on("click",function(){window.location=i.logoutUrl}),a("#session-timeout-dialog").on("hide.bs.modal",function(){d()})}if(!i.ignoreUserActivity){var m=[-1,-1];a(document).on("keyup mouseup mousemove touchend touchmove",function(b){if("mousemove"===b.type){if(b.clientX===m[0]&&b.clientY===m[1])return;m[0]=b.clientX,m[1]=b.clientY}d(),a("#session-timeout-dialog").length>0&&a("#session-timeout-dialog").data("bs.modal")&&a("#session-timeout-dialog").data("bs.modal").isShown&&(a("#session-timeout-dialog").modal("hide"),a("body").removeClass("modal-open"),a("div.modal-backdrop").remove())})}var n=!1;d()}}(jQuery);
