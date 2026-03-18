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
    var countEl = document.getElementById('healthcheck-data-count');
    if (countEl) {
      countEl.insertAdjacentHTML('beforeend', '<p>Number of submissions: ' + data.count + '</p>');
    }
  }

  var container = document.getElementById('healthcheck-bar');

  if (typeof (container) != 'undefined' && container != null) {
    var formData = new URLSearchParams();
    formData.append('action', 'getHealthcheckData');
    formData.append('value[submitted]', 'true');

    fetch(tofinoJS.ajaxUrl, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: formData.toString()
    })
    .then(function(res) { return res.json(); })
    .then(function(response) {
      console.log(response);
      var loadingEl = document.getElementById('graph-loading-wrapper');
      if (loadingEl) loadingEl.style.display = 'none';
      plotData(response)
      countData(response)
    })
    .catch(function(err) {
      console.log('Error:', err);
    });
  }
}
