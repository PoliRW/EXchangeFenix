<?php
if (array_key_exists("cookie-bnt", $_GET)) {
    setcookie("souhlasim", "ano");
    header("Location: ?");
}
require "vendor/autoload.php";
require_once 'stranky.php';



if (array_key_exists("stranka", $_GET)) {

    $aktualniStranka = $_GET["stranka"];

    if (array_key_exists($aktualniStranka, $seznamStranek) == false) {
        http_response_code(404);
        $aktualniStranka = "404";
    }
} else {

    $aktualniStranka = "uvod";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css/style.css">
    <meta name="description" content="Oblíbená směnárna v centru města s výhodnými  kurzy pro směnu valut. VIP kurz dostane každý s využitím slevového kupónu, který je ke zobrazení na webových stránkách. Najdete zde i on-line srovnání kurzů bank. Veškeré směny jsou samozřejmě bez poplatků.">
    <link rel="stylesheet" href="style.css/listekStyle.css">
    <link rel="shortcut icon" href="image/change.png" type="image/x-icon">
    <title><?php echo $seznamStranek[$aktualniStranka]->titulek; ?></title>

</head>

<body>
    <main>
        <header>
            <div class="header">
                <div class="headerLine">
                    <div class="chenageKontaks">
                        <img src="./image/logo.png" class="logo" alt="logo-fenix" width="70">
                        <a href="tel:+420606123456"> (+420) 606 123 456</a>
                    </div>
                    <div class="moneyTransfer">

                        <a href="./" class="moneyTransfer">Převody peněz </a>
                    </div>
                </div>

                <div class="title">
                    <h1>EXchange Fenix </h1>
                </div>

                <div class="menu">
                    <ul>
                        <?php
                        foreach ($seznamStranek as $klic => $instanceStranky) {
                            //<a href='?stranka=$instanceStranky->id'>  /<a href='pobocky'> fungue pomoci friendly url conf.nastaveni apche v slozka .htaccess 

                            if ($instanceStranky->menu != "") {
                                echo "<li><a href='$instanceStranky->id'>$instanceStranky->menu</a></li>";
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </header>




        <section>
            <?php

            //zobrazit  vybrana stranka
            $obsah = $seznamStranek[$aktualniStranka]->getObsah();
            // call library function
            echo primakurzy\Shortcode\Processor::process('shortcodes', $obsah);
            echo "</section>";
            if (!array_key_exists("souhlasim", $_COOKIE)) {
            ?>

                <div class="cookie">
                    <h4 class="cookieTitel">
                        <b>O cookies</b>
                    </h4>
                    <p>
                        Tento web používá soubory cookies. Ty mají dvě funkce: na jedné straně poskytují základní funkčnost pro tento web. Na druhou stranu nám umožňují vylepšovat obsah zaznamenáváním a analýzou anonymních uživatelských dat. Svůj souhlas s používáním těchto cookies můžete kdykoli odvolat. Více informací o cookies najdete na stránce <a href="#" target="blank">Pravidla ochrany osobních údajů</a>
                        včetně Prohlášení o ochraně osobních údajů.
                    </p>
                    <form action="" method="get">
                        <button class="btnCookie" name="cookie-bnt">Přijmout všechny</button>
                    </form>
                </div>
            <?php
            }
            ?>
            <footer>

                <div class="footer">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2560.139003578325!2d14.416671188854988!3d50.083684299999994!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x470b9576590d8e0d%3A0xcbd360f6a29778d1!2sPerlov%C3%A1%20Exchange!5e0!3m2!1sen!2scz!4v1701697221511!5m2!1sen!2scz" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    <div class="footerFlex">
                        <div class="flex1">
                            <ul>
                                <li>Kontakkt</li>
                                <li><a href="tel:+420606123456"> (+420) 606 123 456</a></li>
                                <li> <a href="mailto:">smenarnaperlova@gmail.com</a></li>
                            </ul>
                        </div>
                        <div class="flex2">
                            <ul>
                                <li> Pracovna doba</li>
                                <li> Po-pá 9.00 - 19.00</li>
                                <li> So - ne 10.00 - 18.00 </li>

                            </ul>
                        </div>
                        <div class="flex2">
                            <ul>
                                <li> FENIX PARTERS s.r.o.</li>
                                <li> Harlaherova 3321/10 </li>
                                <li> Praha 10 106 00 </li>
                            </ul>
                        </div>

                    </div>
                    <p> Copyright &copy; 2022 PERLOVA EXCHANGE s.r.o. Všechny práva vyhrazena.</p>
                </div>
            </footer>
    </main>
</body>

</html>