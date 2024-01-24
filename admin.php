<?php
session_start();
require_once 'stranky.php';
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
            echo "<div class= 'styleBox color'>Přihlášen uživatel: {$_SESSION["prihlasenyUzivatel"]}";
            ?>

            <form method='post' class="padding">
                <button name='odhlasit'>Odhlásit</button>
            </form>
            </div>

            <!-- editace listek -->
            <form method="post" class='link padding'>
                <button name='listek'><a href="editaceTabulky.php">listek</a></button>
            </form>

            <div class='link'>
                <ul>
                    <?php
                    //vypiseme seznam stranek, ktere lze editovat
                    foreach ($seznamStranek as $klic => $instanceStranky) {
                        //<a href='pobocky'> fungue pomoci friendly url conf.nastaveni apche v slozka .htaccess
                        echo "
                        <li> $instanceStranky->id

                            <a href='?stranka=$instanceStranky->id'><i class='fa-regular fa-pen-to-square'></i></a>
                            <a href='$instanceStranky->id' target='_blank'><i class='fa-regular fa-eye'></i></a>
                            <a id = 'mazat' href='?stranka=$instanceStranky->id&smazat'><i class='fa-regular fa-trash-can'></i></a>
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

                    <main>
</body>

</html>