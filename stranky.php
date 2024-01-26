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
class Stranka
{
    public $id;
    public $titulek;
    public $menu;

    function __construct($idArg, $titulekArg, $menuArg)
    {
        $this->id = $idArg;
        $this->titulek = $titulekArg;
        $this->menu = $menuArg;
    }

    function getObsah()
    {
        // return file_get_contents("$this->id.html");
        //nacteni obsahu stranky z databaze
        global $db;
        $dotaz = $db->prepare("SELECT  obsah  FROM stranka WHERE id = ?");
        $dotaz->execute([$this->id]);
        $vysledek = $dotaz->fetch();
        //pokud by databaze nic nevratila, tak vratime prazdny obsah
        if ($vysledek == false) {
            return "";
        } else {
            return $vysledek["obsah"];
        }
    }

    //pro kazda instace -stranka se nacete vlastni obsah
    //$obsah bereme z admin formulare pro ulozeni
    function setObsah($obsah)
    {
        // file_put_contents("$this->id.html", $obsah);
        //ulozit do databaze
        global $db;
        $dotaz = $db->prepare("UPDATE stranka SET obsah = ? WHERE id = ?");
        $dotaz->execute([$obsah, $this->id]);
    }
    //ulozit id ,titulek, menu
    function ulozit($puvodniId)
    {
        global $db;
        if ($puvodniId != "") {
            // jde o aktualizaci existujici stranky

            $dotaz = $db->prepare("UPDATE stranka SET id = ?, titulek = ? , menu = ? WHERE id = ?");
            $dotaz->execute([$this->id, $this->titulek, $this->menu, $puvodniId]);
        } else {
            // jde o pridavani nove stranky
            // zjisteni maximalniho poradi
            $dotaz = $db->prepare("SELECT MAX(poradi) AS poradi FROM stranka");
            $dotaz->execute();
            $vysledek = $dotaz->fetch();
            // vezmeme nejvysi poradi ktere je v tabulce a navysime o 1
            $poradi = $vysledek["poradi"] + 1;

            $dotaz = $db->prepare("INSERT INTO stranka SET id = ?, titulek = ? , menu = ?, poradi = ?");
            $dotaz->execute([$this->id, $this->titulek, $this->menu, $poradi]);
        }
    }
    function smazat()
    {
        global $db;
        $dotaz = $db->prepare("DELETE FROM stranka WHERE id = ?");
        $dotaz->execute([$this->id]);
    }

    static function nastavitPoradi($poradi)
    {
        global $db;
        foreach ($poradi as $cisloPoradi => $idStranky) {

            $dotaz = $db->prepare("UPDATE stranka set poradi = ? WHERE id =?");
            $dotaz->execute([$cisloPoradi, $idStranky]);
        }
    }
}

$seznamStranek = [];
//pole se seznamem stranek naplnime dynamicky z databaze

$dotaz = $db->prepare("SELECT id, titulek, menu FROM stranka ORDER BY poradi");
//posilam dotaz na db
$dotaz->execute();
$stranky = $dotaz->fetchAll();

// vezmeme pole radek, ktere nam vratila databaze a postupne
// nakrmime pole seznamStranek jednotlivymi instancemi tridy Stranka
foreach ($stranky as $stranka) {
    // pridame do pole novou instanci tridy Stranka
    $seznamStranek[$stranka["id"]] = new Stranka($stranka["id"], $stranka["titulek"], $stranka["menu"]);
}
// $seznamStranek = [
//     "uvod" => new Stranka("uvod", "EXchangeFenix - domu", "DOMŮ"),
//     "pobocky" => new Stranka("pobocky", "EXchangeFenix - pobocky", "POBOČKY"),
//     "prevody" => new Stranka("prevody", "EXchangeFenix - prevody", "PŘEVODY PENĚZ"),
//     "vip-Kupon" => new Stranka("vip-Kupon", "EXchangeFenix - kupon", "V.I.P KUPONY"),
//     "404" => new Stranka("404", "Stránka nenalezena", "")
// ];
