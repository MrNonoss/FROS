<?php
session_start();
require __DIR__ . "/vendor/autoload.php";

//Securize user inputs
function securize($p)
{
    $v = htmlspecialchars($p); //Convertir les caractères spéciaux
    $v = rtrim($v); //Supprimer les espaces à la fin de la requête
    $v = strtolower($v); //Tout mettre en minuscule
    $v = strip_tags($v); //Supprimer les balises html dans la requête
    $v = stripslashes($v); //Supprimer les slash dans la requête
    $v = stripcslashes($v); //Supprimer les backslash dans la requête
    $v = escapeshellcmd($v); //Protège les caractères spéciaux du Shell
    return $v;
}

//Mail validation
function mailValidate($p)
{
    if (filter_var($p, FILTER_VALIDATE_EMAIL)) {
        $v = true;
    } else {
        $v = false;
    }
    return $v;
}

//Phone validation
function tphValidate($p)
{
    try {
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        $phoneInfo = $phoneUtil->parse($p, null);
        $isValid = $phoneUtil->isValidNumber($phoneInfo);
		$cc = $phoneInfo->getCountryCode();
		$natNumber = $phoneInfo->getNationalNumber();
		$intNumber = $phoneUtil->format($phoneInfo, \libphonenumber\PhoneNumberFormat::E164);
		$v = ["$isValid", "$cc", "$natNumber", "$intNumber"];
        return $v;
    } catch (\libphonenumber\NumberParseException $npe) {
        return null;
    }
}

//Command line crafting
function craft($port, $loc, $tool, $term)
{   
    $ttyd = "ttyd -o -R -t rendererType=webgl -t disableLeaveAlert=true -t disableReconnect=true -p $port";
	$v = "$loc && $ttyd $tool $term";
	exec($v);
}

//Tool selector
if (isset($_POST["search"])) {
    $term = securize($_POST["search"]);
    if (!empty($term)) {
		if (isset($_POST["tool"])) {
			if ($_POST["tool"] == "ghunt") {
				if (mailValidate($term)) {
					$location = "cd /home/www-data/GHunt";
					$tool = "python3 ghunt.py email";
					$cli = (craft($_SESSION["ttyd"], $location, $tool, $term));
					die();
				} else {
					echo "<script type='text/javascript'>alert('$term n\'est pas une adresse mail valide');</script>";
					die();
				}
			} elseif ($_POST["tool"] == "holehe") {
				if (mailValidate($term)) {
					$location = "cd /home";
					$tool = "/home/www-data/.local/bin/holehe --only-used";
					$cli = (craft($_SESSION["ttyd"], $location, $tool, $term));
					die();
				} else {
					echo "<script type='text/javascript'>alert('$term n\'est pas une adresse mail valide');</script>";
					die();
				}
			} elseif ($_POST["tool"] == "nexfil") {
				$location = "cd /home/www-data/nexfil";
				$tool = "python3 nexfil.py -u";
				$cli = (craft($_SESSION["ttyd"], $location, $tool, $term));
				die();
			} elseif ($_POST["tool"] == "maigret") {
				$location = "cd /home";
				$tool = "/home/www-data/.local/bin/maigret --folderoutput /home/www-data/maigret --no-recursion --no-extracting --timeout 10 --retries 0";
				$cli = (craft($_SESSION["ttyd"], $location, $tool, $term));
				die();
			} elseif ($_POST["tool"] == "phoneinfoga") {
				$tph = tphValidate($term);
				if ($tph[0]) {
					$location = "cd /usr/bin";
					$tool = "phoneinfoga scan -n";
					craft($_SESSION["ttyd"], $location, $tool, $tph[3]);
					die();
				} else {
					echo "<script type='text/javascript'>alert('$term n\'est pas un numéro de téléphone valide');</script>";
					die();
				}
			} elseif ($_POST["tool"] == "ignorant") {
				$tph = tphValidate($term);
				if ($tph[0]) {
					$location = "cd /home";
					$tool = "/home/www-data/.local/bin/ignorant --only-used";
					$term = "$tph[1] $tph[2]";
					$cli = (craft($_SESSION["ttyd"], $location, $tool, $term));
					die();
				} else {
					echo "<script type='text/javascript'>alert('$term n\'est pas un numéro de téléphone valide');</script>";
					die();
				}
			} elseif ($_POST["tool"] == "hipb") {
				echo "<script type='text/javascript'>alert('Ce service n\'est pas encore disponible');</script>";
				die();
			}elseif ($_POST["tool"] == "dehashed") {
				echo "<script type='text/javascript'>alert('Ce service n\'est pas encore disponible');</script>";
				die();
			}
		} else {
        echo "<script type='text/javascript'>alert('On ne fais pas de magie. Choisissez un outil !');</script>";
        die();
		}
    } else {
        echo "<script type='text/javascript'>alert('La nature a horreur du vide, entrez un terme de recherche');</script>";
        die();
    }
}

?>
