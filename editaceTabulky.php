<?php require_once 'listek.php';?>
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
        <form class="table">
            <!-- <label id="nazevMena"> Název měny </label> -->

            <!-- <label id=""> Kód </label>
        <label id=""> Nas.</label>
        <label id=""> Nákup/</label>
        <label id=""> Prodej/Sell</label> -->

            <!-- instance listek = radek v tabulka mena a info  -->
            <table>
                <tr>
                    <!-- <th> </th> -->
                    <th>Název měny</th>
                    <th>Kód </th>
                    <th>Nas.</th>
                    <th>Nákup/Buy</th>
                    <th>Prodej/Sell</th>
                </tr>

                <?php foreach ($seznamListek as $klicRadek => $instanceRadek) { ?>
                    <tr>
                        <td>
                            <input type="text" name="menaNazev" value="<?php echo htmlspecialchars($instanceRadek->menaNazev); ?>"></input>
                        </td>
                        <td>
                            <input type="text" name="menaKod" value="<?php echo htmlspecialchars($instanceRadek->menaKod); ?>"></input>
                        </td>
                        <td>
                            <input type="text" name="mnozstvi" value="<?php echo htmlspecialchars($instanceRadek->mnozstvi); ?>"></input>
                        </td>
                        <td>
                            <input type="text" name="cenaNakup" value="<?php echo htmlspecialchars($instanceRadek->cenaNakup); ?>"></input>
                        </td>
                        <td><input type="text" name="cenaProdej" value="<?php echo htmlspecialchars($instanceRadek->cenaProdej); ?>"></input></td>
                    </tr>
                <?php
                }
                ?>

            </table>
            <button name='ulozit'>Uložit</button>
        </form>
        <a href='http://localhost/EXchangeFenix/admin.php'>edit</a>
    </main>
</body>

</html>