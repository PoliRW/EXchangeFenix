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
        $dotazAdmini = $db->query("SELECT COUNT(*) FROM uzivatel WHERE role = 'admin'");
        $pocetAdminu = $dotazAdmini->fetchColumn();

        if ($pocetAdminu < 2) {
            $dotaz = $db->prepare("INSERT INTO uzivatel SET jmeno=? , heslo = ? , role = 'admin'");
            $dotaz->execute([$this->jmeno, $this->heslo]);
            echo "Admin byl úspěšně registrován.";
        } else {
            echo "Byl dosažen maximální počet adminů.";
        }
    }
}

