<?php
session_start();
require "listek.php";
// vyreset otvirane stranku s editaceTabula otvira se i bez prihlaseni

//  zpracovani vyberu aktualni radek
$idRadek = "";
$instanceAktualniRadek = "";
$novaCenaNakup = "";
if (array_key_exists("radek", $_GET)) {
    $idRadek = $_GET["radek"];
    $instanceAktualniRadek = $seznamListek[$idRadek];
}
// zpracováním dat /ulozit nova data ziskani s tabulki
// Tento kód hledá a ukládá novou cenu nákupu pro vybranou měnu po stisknutí tlačítka "uložit" ve formuláři. Pole "menaKod" a "cenaNakup" v HTML formuláři jsou nastavena jako pole v atributu "name", což umožňuje snadnější získání indexu těchto polí v PHP skriptu pro další zpracování.
if (array_key_exists('ulozit', $_POST)) {
    $indexMenaKod = array_search($idRadek, $_POST['menaKod']);
    $poleCenaNakup = ($_POST['cenaNakup']);
    foreach ($poleCenaNakup as $indexCenaNakup => $cenaNakup) {
        // Pokud se index měny shoduje s indexem ceny nákupu, uloží se nová cena nákupu do proměnné $novaCenaNakup.
        if ($indexMenaKod === $indexCenaNakup) {
            $novaCenaNakup = $cenaNakup;
            if($novaCenaNakup!=0){
             $instanceAktualniRadek->ulozitCenaNakup($novaCenaNakup,$idRadek);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css/styleAdmin.css">
    <link rel="icon" type="image/png" href="image/admin.png">
    <title>Document</title>

</head>

<body>

    <main class='padding'>
        <form method="post" class="styleBox">

            <?php if (array_key_exists("prihlasenyUzivatel", $_SESSION) == false) {

                // Zpracování chyb
                echo " Chybně zadané heslo nebo uživatelské jméno" . "<br/>" . "<br/>" .
                    "<a href='http://localhost/EXchangeFenix/admin.php'>➡️ Návrat zpět do přihlašovacího formuláře</a>";
            }
            // sekce pro prihlaseni uživatele 

            else { ?>
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
                    if (!(array_key_exists("ulozit", $_POST))) {
                        //Vypsaný lístek z databáze
                        foreach ($seznamListek as $klicRadek => $instanceRadek) { ?>
                            <tr>
                                <td>
                                    <a <?php
                                        echo "href='?radek=$instanceRadek->menaKod'" ?>><?php echo $instanceRadek->menaNazev; ?></a>

                                </td>
                                <td>
                                    <!--  pomoci "menaKod[]" nastavuje pole  ve formuláři -->
                                    <form>
                                        <input type="text" name="menaKod[]" value="<?php echo htmlspecialchars($instanceRadek->menaKod); ?>" />
                                </td>
                                <td>
                                    <input type="text" name="mnozstvi" value="<?php echo htmlspecialchars($instanceRadek->mnozstvi); ?>" />
                                </td>
                                <td>
                                    <!--  pomoci "cenaNakup[]" nastavuje pole  ve formuláři -->
                                    <input type="text" name="cenaNakup[]" required value="<?php echo htmlspecialchars($instanceRadek->cenaNakup); ?>" />
                                </td>
                                <td>
                                    <input type="text" name="cenaProdej" required value="<?php echo htmlspecialchars($instanceRadek->cenaProdej); ?>" />
                                </td>
                            </tr>

                        <?php
                        }
                    } else {
                        //Vypsaný lístek s aktualizovanými daty, která již byla uložena do databáze
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
                    <?php }
                    }
                    ?>
                </table>
                <button name='ulozit'>Uložit</button>
        </form>
        <a href='http://localhost/EXchangeFenix/admin.php'>edit</a>
    <?php    //"konec podmínka, která určuje, zda je uživatel přihlášen"
            } ?>



    </main>

</body>

</html>