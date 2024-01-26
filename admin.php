<?php
session_start();
require_once 'stranky.php';
require_once 'listek.php';
//zpracovani prihl. formulare
if (array_key_exists("prihlasit", $_POST)) {
    $jmeno = $_POST["jmeno"];
    $heslo = $_POST["heslo"];


    if ($jmeno == "admin" && $heslo == "1234") {

        // uzivatel. zadal platne prihlasovaci udaje
        $_SESSION["prihlasenyUzivatel"] = $jmeno;
    } else {
        //-neplatne zadani udaje - presmerujeme stranka aby takto kod fungoval v miste kde je zobrazeni 
        // chyba pridame podminka   ----if (array_key_exists('chyba', $_SESSION)) {
        //     echo $_SESSION['chyba'];
        //     unset($_SESSION['chyba']);}

        $_SESSION["chyba"] = " Chybně zadané heslo nebo uživatelské jméno";
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

    //zpracovani listek
    //  zpracovani vyberu aktualni radek
    $idRadek = "";
    $instanceAktualniRadek = "";
    if (array_key_exists("radek", $_GET)) {
        $idRadek = $_GET["radek"];
        $instanceAktualniRadek = $seznamListek[$idRadek];
        $pomocnaPromenaKvuliPodminki;
    }
    // zpracovani pozadavku zmeny poradi stranek z javascriptu (ajaxem)
    if (array_key_exists("poradi", $_GET)) {
        $poradi = $_GET["poradi"];
        var_dump($_GET);

        // zavolani funkce pro nastaveni poradi a ulozeni do db
        Stranka::nastavitPoradi($poradi);

        echo "Odpoved pro JS - OK";
        // skript ukoncime aby do javascriptu se negeneroval zbytek
        // html stranky
        exit;
    }
}
// pri vybrani mena z listku se vracim zpet na ?
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
        if (array_key_exists("prihlasenyUzivatel", $_SESSION) == false) {
            // sekce pro neprihlasene uzivatele
        ?>
            <form method="post" class="styleBox">
                <label for="jmeno">Jmeno</label>
                <input type="text" name="jmeno" id="jmeno">
                <label for="heslo">Heslo</label>
                <input type="password" name="heslo" id="heslo">
                <button name="prihlasit"> Přihlásit se </button>
            </form>
            <?php

            //chyba

            if (array_key_exists('chyba', $_SESSION)) {
                echo $_SESSION['chyba'];
                unset($_SESSION['chyba']);
            }
        } else {
            // sekce pro prihlasene uzivatele
            // podminka pro zobrazeni listek
            if (!(array_key_exists('radek', $_GET))) {

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
                            <form method="get" class="styleBox">
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

                                                <!-- link nefunguje pri method post nachradim button
                            <button name="radek" <php echo "value= '$instanceRadek->menaKod'" ?>><php echo $instanceRadek->menaNazev; ?></button>  -->

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
                                    } ?>


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