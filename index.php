<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="UTF-8">
    <title>Fusion Tables Layer Example: Mouseover Map Styles</title>
  </head>
  <body>

<style type="text/css">
	html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map-canvas {
        height: 100%;
      }
    </style>
    <script type="text/javascript"
        src="https://maps.google.com/maps/api/js?sensor=false"></script>

    <script type="text/javascript">
      var colors = ['#FF0000', '#00FF00', '#0000FF', '#FFFF00'];
      var map;

      function initialize() {
        var myOptions = {
          zoom: 2,
          center: new google.maps.LatLng(10, 0),
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById('map-canvas'),
            myOptions);

        // Initialize JSONP request
        var script = document.createElement('script');
        var url = ['https://www.googleapis.com/fusiontables/v1/query?'];
        url.push('sql=');
        var query = 'SELECT name, kml_4326 FROM ' +
            '1foc3xO9DyfSIF6ofvN0kp2bxSfSeKog5FbdWdQ';
        var encodedQuery = encodeURIComponent(query);
        url.push(encodedQuery);
        url.push('&callback=drawMap');
        url.push('&key=AIzaSyAm9yWCV7JPCTHCJut8whOjARd7pwROFDQ');
        script.src = url.join('');
        var body = document.getElementsByTagName('body')[0];
        body.appendChild(script);
      }

      function drawMap(data) {
        var rows = data['rows'];
        for (var i in rows) {
          if (rows[i][0] != 'Antarctica') {
            var newCoordinates = [];
            var geometries = rows[i][1]['geometries'];
            if (geometries) {
              for (var j in geometries) {
                newCoordinates.push(constructNewCoordinates(geometries[j]));
              }
            } else {
              newCoordinates = constructNewCoordinates(rows[i][1]['geometry']);
            }
            var randomnumber = Math.floor(Math.random() * 4);
            var country = new google.maps.Polygon({
              paths: newCoordinates,
              strokeColor: colors[randomnumber],
              strokeOpacity: 0,
              strokeWeight: 1,
              fillColor: colors[randomnumber],
              fillOpacity: 0.3
            });
            google.maps.event.addListener(country, 'mouseover', function() {
              this.setOptions({fillOpacity: 1});
            });
            google.maps.event.addListener(country, 'mouseout', function() {
              this.setOptions({fillOpacity: 0.3});
            });

            country.setMap(map);
          }
        }
      }

      function constructNewCoordinates(polygon) {
        var newCoordinates = [];
        var coordinates = polygon['coordinates'][0];
        for (var i in coordinates) {
          newCoordinates.push(
              new google.maps.LatLng(coordinates[i][1], coordinates[i][0]));
        }
        return newCoordinates;
      }

      google.maps.event.addDomListener(window, 'load', initialize);
    </script>
<div id="map-canvas"></div>