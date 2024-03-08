<?php
require_once 'connect.php';

class Uzivatel
{
    public $id;
    public $jmeno;
    public $heslo;


    function __construct($jmenoArg, $hesloArg)
    {
        $this->jmeno = $jmenoArg;
        $this->heslo = $hesloArg;
    }
    function registrovat()
    {
        global $db;

        // Kontrola počtu registrovaných adminů
        $dotazAdmini = $db->query("SELECT COUNT(*) FROM uzivatel ");
        $pocetAdminu = $dotazAdmini->fetchColumn();

        if ($pocetAdminu < 2) {
            $dotaz = $db->prepare("INSERT INTO uzivatel SET jmeno=? , heslo = ? ");
            $dotaz->execute([$this->jmeno, $this->heslo]);
            //Zprávu uložit do proměnné a pomocí podmínky v admin.ph vypsat pro uživatele
            echo "Admin byl úspěšně registrován.";
        } else {
            echo "Byl dosažen maximální počet adminů.";
        }
    }
}
//V aplikaci nebudou jiní uživatelé než uživatel-administrátor. Proto tam nedávám roli “admin”.

