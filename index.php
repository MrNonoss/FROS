<?php
// Start the session and set a random port in the "ttyd" variable
session_start();
$_SESSION["ttyd"] = rand(5000, 6000);
$server_ip = gethostbyname($_SERVER['SERVER_NAME']);
?>

<!DOCTYPE html>
<html>

  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Title -->
    <title>FROS (Facilitateur de Recherches OpenSources)</title>
    <!-- Links -->
    <link rel="shortcut icon" type="image/x-icon" href="ressources/icon.ico" />
    <link rel="stylesheet" type="text/css" href="ressources/index.css">
  </head>

  <body>
    <h1>FROS (Facilitateur de Recherches OpenSources)</h1>
    <script>var site = '<?php echo "http://$server_ip:$_SESSION[ttyd]"; ?>'</script> <!-- Grab session "ttyd" var in JavaScript -->
    <script src="ressources/js.js"></script> <!-- Include JS script file -->
    <form name="osint" method="POST" action="search.php" target="response">
      <fieldset>
        <legend>Recherchez un mail, un téléphone ou un pseudo...</legend>
        <input type="search" placeholder="mail, téléphone ou pseudo" name="search">
        <br>
        <hr />
        <p> Sur quel outil souhaitez-vous lancer votre recherche? (un seul a la fois...) <i>Soyez cohérents... ne cherchez pas un téléphone sur un outil dédié aux mails... Et inversement...</i>
          <input type="button" class="button" onclick="printHelper()" value="En savoir plus?">
        </p>
        <div class="fields">
          <fieldset>
            <legend>Email</legend>
            <input type="radio" name="tool" value="ghunt">
            <label for="ghunt"><b>
                <div class="tooltip">Ghunt<span class="tooltiptext">Informations d'un compte google</span></div>
              </b></label>
            <br>
            <input type="radio" name="tool" value="holehe">
            <label for="holehe"><b>
                <div class="tooltip">Holehe<span class="tooltiptext">Sites sur lesquels un email est inscrit</span></div>
              </b></label>
          </fieldset>
          <fieldset>
            <legend>Pseudo</legend>
            <input type="radio" name="tool" value="nexfil">
            <label for="nexfil"><b>
                <div class="tooltip">NExfil<span class="tooltiptext">Recherche de pseudo sur plusieurs sites (rapide...)</span></div>
              </b></label>
            <br>
            <input type="radio" name="tool" value="maigret">
            <label for="nexfil"><b>
                <div class="tooltip">Maigret<span class="tooltiptext">Recherche de pseudo sur plusieurs sites</span></div>
              </b></label>
          </fieldset>
          <fieldset>
            <legend>Téléphone</legend>
            <input type="radio" name="tool" value="phoneinfoga">
            <label for="phoneinfoga"><b>
                <div class="tooltip">PhoneInfoGa<span class="tooltiptext">Informations de bases sur un numéro de téléphone</span></div>
              </b></label>
            <br>
            <input type="radio" name="tool" value="ignorant">
            <label for="ignorant"><b>
                <div class="tooltip">Ignorant<span class="tooltiptext">Sites sur lequel un numéro de téléphone est inscrit</span></div>
              </b></label>
          </fieldset>
          <fieldset>
            <legend>Leaks</legend>
            <input type="radio" name="tool" value="hipb">
            <label for="hipb"><b>
                <div class="tooltip">Have I Been Pwned<span class="tooltiptext">Cette adresse fait-elle partie d'une fuite de données?</span></div>
              </b></label>
            <br>
            <input type="radio" name="tool" value="dehashed">
            <label for="dehashed"><b>
                <div class="tooltip">DeHashed<span class="tooltiptext">Cet élément (pseudo, mail, IP, téléphone....) fait-il partie d'une fuite de données?</span></div>
              </b></label>
          </fieldset>
        </div>
        <p> sources des outils:
          <a href="https://github.com/mxrch/GHunt" target="_blank">GHunt</a>,
          <a href="https://github.com/megadose/holehe" target="_blank">Holehe</a>,
          <a href="https://github.com/thewhiteh4t/nexfil" target="_blank">NExfil</a>,
          <a href="https://github.com/soxoj/maigret" target="_blank">Maigret</a>,
          <a href="https://github.com/sundowndev/phoneinfoga" target="_blank">PhoneInfoGa</a>,
          <a href="https://github.com/megadose/ignorant" target="_blank">Ignorant</a>,
          <a href="https://haveibeenpwned.com/" target="_blank">HIBP</a>,
          <a href="https://dehashed.com/" target="_blank">Dehashed</a>
        </p>
        <hr />
        <p><input type="button" class="button" onclick="bind();" value="Seek!"></p>
      </fieldset>
    </form>
	<iframe name="response" style="display: none;"> </iframe>  <!-- Hidden iframe to avoid redirection -->
	<iframe class="iframe" name="iframe"> </iframe>
	
  </body>

</html>