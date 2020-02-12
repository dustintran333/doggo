<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <% base_tag %>
  <title>$SiteConfig.Title</title>
  <meta name="description" content="$SiteConfig.MetaDescription"/>
  <link href="https://api.tiles.mapbox.com/mapbox-gl-js/v1.7.0/mapbox-gl.css" rel="stylesheet"/>
</head>
<body data-app-settings="$appSettings">
  <% include Header %>
  <noscript>
      <strong>We're sorry but doggo doesn't work properly without JavaScript enabled. Please enable it to continue.</strong>
  </noscript>
  <div id="app" role="main"></div>
  <% include Footer %>
</body>
</html>
