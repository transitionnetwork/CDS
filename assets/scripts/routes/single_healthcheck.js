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
      var formData = new URLSearchParams();
      formData.append('action', 'getSingleHealthcheckData');
      formData.append('value[submitted]', 'true');
      formData.append('value[post_id]', document.body.dataset.pid);

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
      })
      .catch(function(err) {
        console.log('Error:', err);
      });
    }

  }
}
