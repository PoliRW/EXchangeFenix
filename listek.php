<?php
//vytvoreni pripojeni na databazi
$db = new PDO(
    "mysql:host = localhostl;dbname=exchange;charset=utf8",
    "root",
    "",
    array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ),
);


class Radek
{
    public $id;
    public $zemeObrazek;
    public $menaNazev;
    public $menaKod;
    public $mnozstvi;
    public $cenaNakup;
    public $cenaProdej;

    function __construct(
        $idArg,
        $zemeObrazekArg,
        $menaNazevArg,
        $menaKodArg,
        $mnozstviArg,
        $cenaNakupArg,
        $cenaProdejArg
    ) {
        $this->id = $idArg;
        $this->zemeObrazek = $zemeObrazekArg;
        $this->menaNazev = $menaNazevArg;
        $this->menaKod = $menaKodArg;
        $this->mnozstvi = $mnozstviArg;
        $this->cenaNakup = $cenaNakupArg;
        $this->cenaProdej = $cenaProdejArg;
    }
    function ulozitCena($cena)
    {
        global $db;
        $dotaz = $db->prepare("UPDATE listek SET cena_nakup = ? WHERE mena_kod = ?");
        $dotaz->execute([$cena, $this->menaKod]);
    }
}

$seznamListek = [];
$dotaz = $db->prepare("SELECT * FROM listek ");
$dotaz->execute();
$listek = $dotaz->fetchAll();
foreach ($listek as $radek) {
    // pridame do pole novou instanci tridy Radek
    $seznamListek[$radek['mena_kod']] = new Radek($radek['id'], $radek['zeme_obrazek'], $radek['mena_nazev'], $radek['mena_kod'], $radek['mnozstvi'], $radek['cena_nakup'], $radek['cena_prodej']);
}
// $pokus="<img src='./image/logo.png' alt='usa-flag' width='70'>";
// $obraz = $db->prepare("UPDATE `listek` SET `zeme_obrazek` = '<img src=\'./image/GBP.png\' alt=\'vlajka\' width=\'80\'>' WHERE `listek`.`id` = 4");
// $obraz->execute();
