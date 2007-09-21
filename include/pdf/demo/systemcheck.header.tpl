<html>
<head>
<title>html2ps/html2pdf &mdash; checking your system configuration</title>
<style>
body {
  color:#000;
  background-color:#fff;
  margin:10px;
  font-family:arial, helvetica, sans-serif;
  color:#000;
  font-size:12px;
  line-height:18px;
}

p,td {
  color:#000;
  font-size:12px;
  line-height:18px;
  margin-top:3px;
  vertical-align: top;
}

h1 {
  font-family:arial, helvetica, sans-serif;
  color:#669;
  font-size:27px;
  letter-spacing:-1px;
  margin-top:12px;
  margin-bottom:12px;
}

.check .title {
  font-weight: bold;
  padding: 0.2em;
}

.check .message {
  padding: 0.2em;
}

.check .title.failed {
  background-color: #fdd;
}

.check .title.warning {
  background-color: #ffd;
}

.check .title.success {
  background-color: #dfd;
}

.check .title.unknown {
  background-color: #eee;
}

</style>
</head>
<body>
<h1>Checking your system configuration</h1>

<p>This script will attempt to check your system settings and detect
most obvious problems which could prevent you from using html2ps:
missing PHP extensions, invalid permissions on files used by the
script, missing font files and so on. Please note that if list may be
incomplete; please visit <a title="Opens in new window"
target="_blank" href="http://www.tufat.com/forum/forumdisplay.php?f=58">html2ps
support forum</a> in case you've encountered an unknown issue.
</p>
