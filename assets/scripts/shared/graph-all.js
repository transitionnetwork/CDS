var $ = window.jQuery;
import Plotly from 'plotly.js';

export default function() {
  //graph
  function plotData(data) {
    var myPlot = document.getElementById('healthcheck-bar');
    
    var holdData = [
      {
        x: data.averages.reverse(),
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

          console.log(d.text)

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

  function countData(data) {
    $('#healthcheck-data-count').append('<p>Number of submissions: ' + data.count + '</p>')
  }

  var container = document.getElementById('healthcheck-bar');
  
  if (typeof (container) != 'undefined' && container != null) {
    $.ajax({
      url: tofinoJS.ajaxUrl,
      type: 'POST',
      cache: false,
      data: {
        action: 'getHealthcheckData',
        value: {
          submitted: true
        }
      },
      dataType: 'json',
      success: function (response) {
        console.log(response);
        $('#graph-loading-wrapper').hide();
        plotData(response)
        countData(response)
      },
      error: function (jqxhr, status, exception) {
        console.log('JQXHR:', jqxhr);
        console.log('Status:', status);
        console.log('Exception:', exception);
      }
    })
  }
}
