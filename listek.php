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
    function ulozit()
    {
        global $db;
        $dotaz = $db->prepare("UPDATE listek SET cenaNakup = ?, cenaProdej= ? WHERE id = ?");
        $dotaz->execute([$this->cenaNakup, $this->cenaProdej, $this->id]);
    }
}

$seznamListek = [];
$dotaz = $db->prepare("SELECT * FROM listek ");
$dotaz->execute();
$listek = $dotaz->fetchAll();
foreach ($listek as $klicRadek) {
    $seznamListek[$klicRadek['mena_kod']] = new Radek($klicRadek['id'], $klicRadek['zeme_obrazek'], $klicRadek['mena_nazev'], $klicRadek['mena_kod'], $klicRadek['mnozstvi'], $klicRadek['cena_nakup'], $klicRadek['cena_prodej']);
}
// $pokus="<img src='./image/logo.png' alt='usa-flag' width='70'>";
// $obraz = $db->prepare("UPDATE `listek` SET `zeme_obrazek` = '<img src=\'./image/GBP.png\' alt=\'vlajka\' width=\'80\'>' WHERE `listek`.`id` = 4");
// $obraz->execute();
