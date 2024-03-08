<?php
session_start();
require_once 'listek.php';
// vyreset otvirane stranku s editaceTabula otvira se i bez prihlaseni

//  zpracovani vyberu aktualni radek
$idRadek = "";
$instanceAktualniRadek = "";
if (array_key_exists("radek", $_GET)) {
    $idRadek = $_GET["radek"];
    $instanceAktualniRadek = $seznamListek[$idRadek];
}

// zpracovani formulare pro ulozeni cena prodej nebo a cena nakup
//pro kazda instace - radek se nacete vlastni cena nakup a cena prodej
//$obsah bereme z admin formulare pro ulozeni

if (array_key_exists("ulozit", $_POST)) {
    // Pokuste se přečíst vlastnost "menaKod" na řetězci 
    $instanceAktualniRadek->menaKod;
    $instanceAktualniRadek->cenaNakup;
    $instanceAktualniRadek->ulozitCena();
    var_dump($instanceAktualniRadek->ulozitCena());
}
// $instanceAktualniRadek->cenaNakup
// $puvodniCenaProdej = $instanceAktualniRadek->cenaProdej;
// $instanceAktualniRadek->tcenaProdej = $_GET['cenaProdej'];
// echo $puvodniCenaNakup ;




//         $obsah = $_POST["obsah"];
//         $instanceAktualniStranky->setObsah($obsah);
//         // presmerujeme se na url s editaci stranky s novym id
//         // (kdyby se id zmenilo tak nesmime zustat na puvodni url)
//         //urlencode prepise url  kvuli neplatni znaci v url
//         header("Location: ?stranka=" . urlencode($instanceAktualniStranky->id));
//         //poznamenat provedena zmena
//     }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css/styleAdmin.css">
    <title>Document</title>

</head>

<body>

    <main class='padding'>
        <form method="post" class="styleBox">
            <!-- <label id="nazevMena"> Název měny </label> -->

            <!-- <label id=""> Kód </label>
        <label id=""> Nas.</label><label id=""> Nákup/</label><label id=""> Prodej/Sell</label> -->

            <?php if (array_key_exists("prihlasenyUzivatel", $_SESSION) == false) {

                // Zpracování chyb
                echo " Chybně zadané heslo nebo uživatelské jméno" . "<br/>" ."<br/>".
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
                        }
                    } else {
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
                    <?Php    }
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