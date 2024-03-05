<?php
session_start();
require_once 'stranky.php';
require_once 'listek.php';
require_once 'uzivatel.php';
$jmeno = " ";
$heslo = " ";
$heslo_znovu = " ";
$chyba = [];
// Zpracování registračního formuláře
//-------------------------------------------------------------------------------------------------------------------------------------
if (array_key_exists("registraceFormular", $_POST)) {
    $registrovat = 1;}

    if (array_key_exists("registrovat", $_POST)) {
        $jmeno = $_POST["jmeno"];
        $heslo = $_POST["heslo"];
        $znovu_heslo = $_POST["heslo_znovu"];
        $admin = new Uzivatel($jmeno, $heslo);
        // Kontrola délky jména
        if (strlen($jmeno) < 3) {
            $chyba[] = "Jméno musí být minimálně 3 znaky.";
        }

        // Kontrola velkých písmen v hesle
        if (!preg_match('/[A-Z]/', $heslo)) {
            $chyba[] = "Heslo musí obsahovat alespoň 1 velké písmeno.";
        }

        // Kontrola speciálních znaků v hesle
        if (!preg_match('/[^a-zA-Z0-9]/', $heslo)) {
            $chyba[] = "Heslo musí obsahovat alespoň 1 speciální znak.";
        }

        // Kontrola délky hesla
        if (strlen($heslo) < 8) {
            $chyba[] = "Heslo musí být minimálně 8 znaků dlouhé.";
        }

        // Kontrola shody hesel
        if ($heslo !== $znovu_heslo) {
            $chyba[] = "Hesla se neshodují.";
        }

        if (empty($chyba)) {
            $admin->registrovat();
        } else {
            $_SESSION["chyba"] = $chyba;
        }
    }

//konec zpracování registračního formuláře
//-------------------------------------------------------------------------------------------------------------------------------------



//zpracovani prihl. formulare
if (array_key_exists("prihlasit", $_POST)) {
    $jmeno = $_POST["jmeno"];
    $heslo = $_POST["heslo"];

    if ($jmeno == "admin" && $heslo == "1234") {

        // uzivatel. zadal platne prihlasovaci udaje
        $_SESSION["prihlasenyUzivatel"] = $jmeno;
    } else {
        //-neplatne zadani udaje - presmerujeme stranka aby takto kod fungoval v miste kde je zobrazeni 
        $chyba[] = " Chybně zadané heslo nebo uživatelské jméno";
        $_SESSION["chyba"] = $chyba;
        //// Přesměrování zpět na přihlašovací formulář pomocí header Location
        header("Location: ?");
        exit;
    }
}

// zpracování odhl. formuláře
if (array_key_exists("odhlasit", $_POST)) {
    unset($_SESSION["prihlasenyUzivatel"]);
    header("Location: ?");
}

// zpracovani akci v administraci je pouze pro PRIHLASENE uzivatele
if (array_key_exists("prihlasenyUzivatel", $_SESSION)) {

    // promenna predstavujici stranku s kterou zrovna editujeme
    $instanceAktualniStranky = null;

    //  zpracovani vyberu aktualni stranky

    if (array_key_exists("stranka", $_GET)) {
        $idStranky = $_GET["stranka"];
        $instanceAktualniStranky = $seznamStranek[$idStranky];
    }

    // zpracovani tlacitka Pridat
    if (array_key_exists("pridat", $_GET)) {
        $instanceAktualniStranky = new Stranka("", "", "");
    }
    //zpracovani mazani
    if (array_key_exists("smazat", $_GET)) {

        $instanceAktualniStranky->smazat();
        header("Location: ?");
    }

    // zpracovani formulare pro ulozeni
    if (array_key_exists("ulozit", $_POST)) {
        $puvodniId = $instanceAktualniStranky->id;
        $instanceAktualniStranky->id = $_POST['id'];
        $instanceAktualniStranky->titulek = $_POST['titulek'];
        $instanceAktualniStranky->menu = $_POST['menu'];
        $instanceAktualniStranky->ulozit($puvodniId);
        $obsah = $_POST["obsah"];
        $instanceAktualniStranky->setObsah($obsah);
        // presmerujeme se na url s editaci stranky s novym id
        // (kdyby se id zmenilo tak nesmime zustat na puvodni url)
        //urlencode prepise url  kvuli neplatni znaci v url
        header("Location: ?stranka=" . urlencode($instanceAktualniStranky->id));
        //poznamenat provedena zmena
    }

    // zpracovani pozadavku zmeny poradi stranek z javascriptu (ajaxem)
    if (array_key_exists("poradi", $_GET)) {
        $poradi = $_GET["poradi"];

        // zavolani funkce pro nastaveni poradi a ulozeni do db
        Stranka::nastavitPoradi($poradi);

        echo "Odpoved pro JS - OK";
        // skript ukoncime aby do javascriptu se negeneroval zbytek
        // html stranky
        exit;
    }
    //zpracovani listek
    //  zpracovani vyberu aktualni radek
    $idRadek = "";
    $instanceAktualniRadek = null;
    if (array_key_exists("radek", $_GET)) {
        $idRadek = $_GET["radek"];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css/styleAdmin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>Administration</title>
    <script src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

</head>

<body>
    <main class='padding'>
        <?php
        if (isset($registrovat) == 1) {

            //Sekce pro registraci uživatele
            //------------------------------------------------------------------------------------------------------------
        ?>
            <form method="post" class="styleBox">
                <label for="jmeno">Jmeno</label>
                <input type="text" name="jmeno" id="jmeno">
                <label for="heslo">Heslo</label>
                <input type="password" name="heslo" id="heslo">
                <label for="heslo2">Heslo znovu</label>
                <input type="password" name="heslo_znovu" id="heslo2">
                <button name="registrovat"> Registrovat </button>
            </form>
            <?php
            // Zpracování chyb

            if (isset($_SESSION["chyba"])) {
                foreach ($_SESSION["chyba"] as $chyba) {
                    echo "<p>$chyba</p>";
    // Registrační sekce pro nepřihlášené a nepodařenou registraci uživatele
                }
                // unset($_SESSION["chyba"]);
                
            }


            //Konec Sekce pro registraci uživatele
            //----------------------------------------------------------------------------------------------------------------------
            exit;
        }


        if (array_key_exists("prihlasenyUzivatel", $_SESSION) == false) {
            // Sekce pro neprihlasene uzivatele
            //-----------------------------------------------------------------------------------------------------------------
            ?>
            <form method="post" class="styleBox">
                <label for="jmeno">Jmeno</label>
                <input type="text" name="jmeno" id="jmeno">
                <label for="heslo">Heslo</label>
                <input type="password" name="heslo" id="heslo">
                <button name="prihlasit"> Přihlásit se </button>
                <button name="registraceFormular"> Registrovat </button>
            </form>
            <?php

            //chyba

            if (isset($_SESSION["chyba"])) {
                foreach ($_SESSION["chyba"] as $chyba) {
                    echo "<p>$chyba</p>";
                }
                unset($_SESSION["chyba"]);
            }
        } else {
            // sekce pro prihlasene uzivatele
            // podminka pro zobrazeni listek
            if (isset($_GET['radek']) == false) {

                echo "<div class='styleBox color'>Přihlášen uživatel: {$_SESSION["prihlasenyUzivatel"]}";
            ?>
                <form method='post' class="padding">
                    <button name='odhlasit'>Odhlásit</button>
                </form>
                </div>
                <!-- editace listek -->
                <form method="get" class='link padding'>
                    <button name='radek'>Listek</button>
                </form>
                <!--  listek k editace -->
                <div class='link'>
                    <ul id="stranky">
                        <?php
                        //vypiseme seznam stranek, ktere lze editovat
                        foreach ($seznamStranek as $klic => $instanceStranky) {
                            //<a href='pobocky'> fungue pomoci friendly url conf.nastaveni apche v slozka .htaccess
                            echo "
                        <li id='$instanceStranky->id'> 
                            <a href='?stranka=$instanceStranky->id'><i class='fa-regular fa-pen-to-square'></i></a>
                            <a href='$instanceStranky->id' target='_blank'><i class='fa-regular fa-eye'></i></a>
                            <a id = 'mazat' class = 'smazat' href='?stranka=$instanceStranky->id&smazat'><i class='fa-regular fa-trash-can'></i></a>
                            $instanceStranky->id
                        </li>";
                        }
                        echo "</ul>
            </div>";
                        ?>

                        <!-- formular s tlacitkem pro pridani stranky -->
                        <form class='padding'>
                            <button name='pridat'>Přidat</button>
                        </form>

                        <!-- editacni formular -->
                        <?php if ($instanceAktualniStranky != null) {
                            echo "<div class ='styleBox color'><h3>";
                            if ($instanceAktualniStranky->id == "") {
                                echo "Přidávání stránky";
                            } else {
                                echo "Editace stránky $instanceAktualniStranky->id ";
                            }
                            echo "</h3></div>";
                        ?>
                            <form method='post'>
                                <div class="styleBox">
                                    <label for='id'>Id:</label>
                                    <input type='text' name='id' id='id' value='<?php echo htmlspecialchars($instanceAktualniStranky->id) ?>'>
                                    <label for='titulek'>Titulek:</label>
                                    <input type='text' name='titulek' id='titulek' value='<?php echo htmlspecialchars($instanceAktualniStranky->titulek) ?>'>
                                    <label for='menu'>Menu:</label>
                                    <input type='text' name='menu' id='menu' value='<?php echo htmlspecialchars($instanceAktualniStranky->menu) ?>'>
                                </div>

                            <?php echo "<textarea name='obsah' id='obsah'>";

                            echo htmlspecialchars($instanceAktualniStranky->getObsah());
                            echo "</textarea >        
                      <button name='ulozit'>Ulozit</button>
                 </form>";
                        }
                    } else { ?>
                            <form method="get" class="styleBox" action="editaceTabulky.php">
                                <!-- <label id="nazevMena"> Název měny </label> -->
                                <!-- <label id=""> Kód </label>
        <label id=""> Nas.</label><label id=""> Nákup/</label><label id=""> Prodej/Sell</label> -->
                                <table>
                                    <tr>
                                        <!-- <th> </th> -->
                                        <th>Název měny</th>
                                        <th>Kód </th>
                                        <th>Nas.</th>
                                        <th>Nákup/Buy</th>
                                        <th>Prodej/Sell</th>
                                    </tr>
                                    <?php
                                    foreach ($seznamListek as $klicRadek => $instanceRadek) { ?>
                                        <tr>
                                            <td>
                                                <a <?php
                                                    echo "href='?radek=$instanceRadek->menaKod'" ?>><?php echo $instanceRadek->menaNazev; ?></a>

                                            </td>
                                            <td>
                                                <input type="text" name="menaKod" value="<?php echo htmlspecialchars($instanceRadek->menaKod); ?>" />
                                            </td>
                                            <td>
                                                <input type="text" name="mnozstvi" value="<?php echo htmlspecialchars($instanceRadek->mnozstvi); ?>" />
                                            </td>
                                            <td>
                                                <input type="text" name="cenaNakup" required value="<?php echo htmlspecialchars($instanceRadek->cenaNakup); ?>" />
                                            </td>
                                            <td>
                                                <input type="text" name="cenaProdej" required value="<?php echo htmlspecialchars($instanceRadek->cenaProdej); ?>" />
                                            </td>
                                        </tr>

                                    <?php
                                        $instanceRadek->cenaNakup = $_GET['cenaNakup'];
                                        var_dump($instanceRadek->cenaNakup);
                                    }
                                    ?>


                                </table>
                                <button name='ulozitListek'>Uložit</button>
                            </form>
                    <?php echo "<div><a href='?'>Zpět</a></div>";
                    }
                }
                    ?>
                    <!-- script pro prace s timymce -->
                    <script src='vendor\tinymce\tinymce\tinymce.min.js' referrerpolicy='origin'></script>
                    <script>
                        tinymce.init({
                            selector: '#obsah',
                            language: 'cs',
                            language_url: '<?php echo dirname($_SERVER["PHP_SELF"]); ?>/vendor/tweeb/tinymce-i18n/langs/cs.js',
                            height: '50vh',
                            entity_encoding: "raw",
                            plugins: 'advlist anchor autolink charmap code colorpicker contextmenu directionality emoticons fullscreen hr image imagetools insertdatetime link lists nonbreaking noneditable pagebreak paste preview print save searchreplace tabfocus table textcolor textpattern visualchars',
                            toolbar1: "insertfile undo redo | styleselect | fontselect | fontsizeselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | forecolor backcolor",
                            toolbar2: "link unlink anchor | fontawesome | image media | responsivefilemanager | preview code",
                        });
                    </script>
                    <script src="js/admin.js"></script>

                    <main>
</body>

</html>