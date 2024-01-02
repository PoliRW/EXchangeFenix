<?php

    require_once "listek.php";

    // $dotaz = $db->prepare("SELECT * FROM listek ");
    // $dotaz->execute();
    // $tabulka = $dotaz->fetchAll();

    ?>

  <table>
     <tr class="thead italic">
         <th> </th>
         <th>Název měny</th>
         <th>Kód </th>
         <th>Nas.</th>
         <th>Nákup/Buy</th>
         <th>Prodej/Sell</th>
     </tr>
     <!-- instance listek = radek v tabulka mena a info  -->

     <?php foreach ($seznamListek as $klicRadek => $instanceRadek) {
            //podminka pro nacitane tmavi a svetle radki

            $color = "";
            if (($instanceRadek->id % 2) == 0) {
                $color = "dark";
                echo "<tr class='$color'>" ?>

             <td><?php echo $instanceRadek->zemeObrazek; ?></td>

             <td><?php echo $instanceRadek->menaNazev; ?></td>

             <td><?php echo $instanceRadek->menaKod; ?></td>

             <td><?php echo $instanceRadek->mnozstvi; ?></td>

             <td><?php echo $instanceRadek->cenaNakup; ?></td>

             <td><?php echo $instanceRadek->cenaProdej ?> </td>
             </tr>

         <?php
            } else {
                echo "<tr class='$color'>" ?>
             <td><?php echo $instanceRadek->zemeObrazek; ?></td>

             <td><?php echo $instanceRadek->menaNazev; ?></td>

             <td><?php echo $instanceRadek->menaKod; ?></td>

             <td><?php echo $instanceRadek->mnozstvi; ?></td>

             <td><?php echo $instanceRadek->cenaNakup; ?></td>

             <td><?php echo $instanceRadek->cenaProdej ?> </td>
             </tr>

     <?php }
        } ?>
 </table>