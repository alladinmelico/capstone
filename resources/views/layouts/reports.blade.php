<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>SSC System</title>
  <style>
    body {
      font-family: Arial, Helvetica, sans-serif;
      text-align: center;
    }
    header {
      background-color: white;
      padding: 20px;
      font-size: 25px;
      color: black;
    }
    section {
      display: -webkit-flex;
      display: flex;
    }
    footer {
      background-color: white;
      padding: 5px;
      text-align: center;
      color: black;
    }
    table,
    td,
    th {
      border: 1px solid black;
      padding: 10px;
    }
    table {
      border-collapse: collapse;
      width: 100%;
    }
    td {
      text-align: center;
      font-size: 1rem;
    }
    table.center {
      margin-left: auto;
      margin-right: auto;
    }
    tr:nth-child(even) {
        background-color: rgb(247, 254, 255);
    }
    th {
        background-color: #4bacb8;
        color: white;
    }
    h2 {
        color: #4bacb8;
    }
    small {
        font-size: 12px;
    }
    h5 {
        margin-top: 1rem;
    }
  </style>
  @stack('styles')
</head>

<body>
  @yield('content')
  <footer>
    <p><small>&copy; <a href="https://www.sscsystem.tech" target="_blank">Safe and Smart Campus</a>, All rights reserved.</small></p>
  </footer>
</body>

</html>
