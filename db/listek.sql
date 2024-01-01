    CREATE TABLE listek(
        id int unsigned primary key AUTO_INCREMENT,
        zeme_obrazek VARCHAR(255),
        mena_nazev VARCHAR(255),
        mena_kod VARCHAR(255),
        mnozstvi INT,
	    cena_nakup double,
        cena_prodej double
    );
    DROP Table listek;
    INSERT INTO listek SET
      zeme_obrazek = "USA.png",
      mena_nazev = "americký dolar",
      mena_kod = "USD",
      mnozstvi = 1,
      cena_nakup = 20.5,
      cena_prodej = 22
      ;
     INSERT INTO listek SET
      zeme_obrazek = "EUR.png",
      mena_nazev = "euro",
      mena_kod = "EUR",
      mnozstvi = 1,
      cena_nakup = 24.5,
      cena_prodej = 26.2
      ;
        INSERT INTO listek SET
      zeme_obrazek = "GBP.png",
      mena_nazev = "britská libra",
      mena_kod = "GBP",
      mnozstvi = 1,
      cena_nakup = 29.5,
      cena_prodej = 30.2
      ;
           INSERT INTO listek SET
      zeme_obrazek = "CHF.png",
      mena_nazev = "švýcarský frank",
      mena_kod = "CHF",
      mnozstvi = 1,
      cena_nakup = 24.5,
      cena_prodej = 26.2
      ;
            INSERT INTO listek SET
      zeme_obrazek = "DKK.png",
      mena_nazev = "dánská koruna",
      mena_kod = "DKK",
      mnozstvi = 1,
      cena_nakup = 03.5,
      cena_prodej = 03.2
      ;
         INSERT INTO listek SET
      zeme_obrazek = "NOK.png",
      mena_nazev = "norská koruna",
      mena_kod = "NOK",
      mnozstvi = 1,
      cena_nakup = 02.5,
      cena_prodej = 03.2
      ;
        INSERT INTO listek SET
      zeme_obrazek = "SEK.png",
      mena_nazev = "švédská koruna",
      mena_kod = "SEK",
      mnozstvi = 1,
      cena_nakup = 02.30,
      cena_prodej = 02.60
      ;
              INSERT INTO listek SET
      zeme_obrazek = "HUF.png",
      mena_nazev = "maďarský forint",
      mena_kod = "HUF",
      mnozstvi = 100,
      cena_nakup = 06.30,
      cena_prodej = 06.90
      ;
          INSERT INTO listek SET
      zeme_obrazek = "PLN.png",
      mena_nazev = "polský zlotý",
      mena_kod = "PLN",
      mnozstvi = 1,
      cena_nakup = 05.30,
      cena_prodej = 05.90
      ;
        INSERT INTO listek SET
      zeme_obrazek = "CAD.png",
      mena_nazev = "kanadský dolar",
      mena_kod = "CAD",
      mnozstvi = 1,
      cena_nakup = 17.60,
      cena_prodej = 17.90
      ;