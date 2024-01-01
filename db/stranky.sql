CREATE DATABASE exchange COLLATE utf8mb4_czech_ci;
CREATE TABLE stranka (
    id VARCHAR(100) primary key,
    titulek text,
    menu text,
    obsah text,
    poradi int
) ;
INSERT INTO stranka SET
    id = "uvod",
    titulek = "EXchangeFenix - domu",
    menu = "DOMŮ",
    obsah = "...",
    poradi = 1;
INSERT INTO stranka SET
    id = "kontakt",
    titulek = "EXchangeFenix - kontakt",
    menu = "KONTAKT",
    obsah = "...",
    poradi = 2;
INSERT INTO stranka SET
    id = "prevody",
    titulek = "EXchangeFenix - prevody",
    menu = "PŘEVODY PENĚZ",
    obsah = "...",
    poradi = 3;
INSERT INTO stranka SET
    id = "kupon",
    titulek = "EXchangeFenix - kupon",
    menu = "V.I.P KUPONY",
    obsah = "...",
    poradi = 4;
INSERT INTO stranka SET
    id = "404",
    titulek = "Stránka nenalezena",
    menu = "",
    obsah = "...",
    poradi = 5;