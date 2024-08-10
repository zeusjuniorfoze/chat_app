<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="UTF-8" />
    <title>Navbar Vertical</title>
    <link rel="stylesheet" href="style.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <style type="text/css">
      body {
  margin: 0;
  font-family: Arial, sans-serif;
}

.navbar {
  width: 200px;
  height: 100vh;
  background-color: #333;
  padding-top: 20px;
  box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
  position: fixed;
}

.navbar a {
  display: block;
  padding: 15px;
  color: #fff;
  text-decoration: none;
  transition: background-color 0.3s;
}

.navbar a:hover {
  background-color: #555;
}

.content {
  margin-left: 200px;
  padding: 20px;
}

    </style>
  </head>
  <body>
    <div class="navbar">
      <a href="#" onclick="openPage('page1.html')">Page 1</a>
      <a href="#" onclick="openPage('page2.html')">Page 2</a>
      <a href="#" onclick="openPage('page3.html')">Page 3</a>
      <a href="#" onclick="openPage('page4.html')">Page 4</a>
    </div>

    

    <script type="text/javascript">
    function openPage(page) {
  document.getElementById('mainContent').innerHTML = '';
  fetch(page)
    .then(response => response.text())
    .then(data => {
      document.getElementById('mainContent').innerHTML = data;
    });
}
</script>
  </body>
</html>
