var $ = window.jQuery;
import Plotly from 'plotly.js'

export default {
  loaded() {

  function plotData(data) {
    var myPlot = document.getElementById('healthcheck-bar');

    var holdData = [
      {
        x: data.data.reverse(),
        y: data.questions.reverse(),
        type: 'bar',
        orientation: 'h',
        customLabel: 'potato',
        hovermode: false
      }
    ];

    var layout = {
      height: 2000
    }

    Plotly.newPlot('healthcheck-bar', holdData, layout);

    myPlot.on('plotly_afterplot', function () {
      Plotly.d3.selectAll(".yaxislayer-above").selectAll('text')
        .on("click", function (d) {

          console.log(d)

          var found = [],          // an array to collect the strings that are found
            rxp = /{([^}]+)}/g,
            str = d.text,
            curMatch;

          while (curMatch = rxp.exec(str)) {
            found.push(curMatch[1]);
          }

          alert(found);
        })
    });
  }

    var container = document.getElementById('healthcheck-bar');

    if (typeof (container) != 'undefined' && container != null) {
      $.ajax({
        url: tofinoJS.ajaxUrl,
        type: 'POST',
        cache: false,
        data: {
          action: 'getSingleHealthcheckData',
          value: {
            submitted: true,
            post_id: $('body').data('pid')
          }
        },
        dataType: 'json',
        success: function (response) {
          console.log(response);
          $('#graph-loading-wrapper').hide();
          plotData(response)
        },
        error: function (jqxhr, status, exception) {
          console.log('JQXHR:', jqxhr);
          console.log('Status:', status);
          console.log('Exception:', exception);
        }
      })
    }

  }
}
