$(function() {
  $('.jqueryOptions').hide();

  $('#choose').change(function () {
      $('#progresswraper').css('display', 'block');
      myProgressbar('pb');
  });
});

// Creates and animates the progressbar
function myProgressbar(id) {
    var obj = {
            outer: [
                document.getElementById(id),
                {
                    display: 'inline-block',
                    width: '450px',
                    background: '#00afcf',
                    borderRadius: '8px'
                }
            ],
            inner: [
                document.createElement('div'),
                {
                    margin: '10px',
                    height: '30px',
                    background: '#eee'
                }
            ]
        },
        i,
        m;

    obj.outer[0].appendChild(obj.inner[0]);

    for (i in obj) {
        for (m in obj[i][1]) {
            obj[i][0].style[m] = obj[i][1][m];
        }
    }

    var width = obj.inner[0].offsetWidth;

    obj.outer[0].style.height = obj.outer[0].offsetHeight;

    var progress = function(m) {
            if (m <= 100 && m >= 0) {
                obj.inner[0].style.width = Math.round(width / 100 * m) + 'px';
            }
        },
        p = 0,
        direction,
        interval = setInterval(function() {
            //p = p || 0;
            if (p <= 1) {
                direction = '+';
                obj.outer[0].setAttribute('align', 'left');
            }

            if (p >= 100) {
                direction = '-';
                obj.outer[0].setAttribute('align', 'right');
            }
            direction === '+' ? p++ : p--;
            progress(p);
        }, 0);
}


