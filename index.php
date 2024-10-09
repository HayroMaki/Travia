<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Travia</title>
    <link href="index.css" rel="stylesheet">
</head>
<body>

  <!-- Header bar -->
  <header id="header">
    <p id="mainTitle">Travia</p>
    <a href="">
      <img src="icons/utilisateur.png" id="userImage" alt="user icon">
    </a>
  </header>

  <div class="content">
    <form action="GET">
      <table id="formTable">
        <tr>
          <th>
            <label for="formD">Départ</label>
            <br>
            <select id="formD" name="formD">
              <option value="Gare du Nord">Gare du Nord</option>
              <option value="Gare de l'Est">Gare de l'Est</option>
              <!-- PHP -->
            </select>
          </th>
        </tr>

        <tr>
          <th>
            <label for="formA">Arrivé</label>
            <br>
            <select id="formA" name="formD">
                <option value="Gare du Nord">Gare du Nord</option>
                <option value="Gare de l'Est">Gare de l'Est</option>
              <!-- PHP -->
            </select>
          </th>
        </tr>

        <tr>
          <th>
            <input type="submit" value="OK">
          </th>
        </tr>
      </table>
    </form>
  </div>
</body>
</html>