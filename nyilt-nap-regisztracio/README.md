# NTSZKI – nyílt napi regisztráció

Letisztult, kékes-lilás, kiemelten tabletbarát regisztrációs oldal natív PHP-val, PDO-val, MySQL/MariaDB-vel, HTML-lel, CSS-sel és kevés JavaScripttel. Nem igényel keretrendszert vagy buildlépést.

## Funkciók

- Egyszerű, űrlapközpontú kezdőlap az NTSZKI arculatával; a saját logó automatikusan megjelenik.
- Teljes név és iskola megadása, több szakma kiválasztása, bejárós/kollégista státusz és végzős státusz.
- Letisztult, egyedi ikonokkal ellátott szakmaválasztó és opciók, nagy érintési célokkal.
- A továbbtanulásról szóló rövid tippek automatikusan, 7,5 másodpercenként váltanak; egérrel rámutatva vagy a tipppanelre fókuszálva a váltás szünetel, a tippek kézzel is kiválaszthatók.
- Az azonos nevű diákok jelentkezései külön rekordként mentődnek el.
- Védett adminfelület összesítő statisztikákkal, szakmánkénti végzős/kollégista bontással, kereséssel, lapozható jelentkezési listával és törléssel.
- CSRF-védelem, adatbázis-lekérdezések előkészítve, kimenetek HTML-escape-elése és jelszóhash használata.

## Telepítés XAMPP alatt

1. Másold a `nyilt-nap-regisztracio` mappát a XAMPP `htdocs` könyvtárába.
2. Indítsd el az Apache és a MySQL szolgáltatást a XAMPP vezérlőpultján.
3. Nyisd meg a `http://localhost/phpmyadmin` címet, majd importáld a `database/schema.sql` fájlt. A fájl létrehozza a `nyilt_nap` adatbázist és a kért hat szakmát is. Már telepített adatbázis esetén az oldal automatikusan felveszi a hat szakmát, és csak ezeket mutatja a jelentkezési űrlapon.
4. Ellenőrizd az `app/config.php` adatbázis-beállításait. Alapértelmezés: host `127.0.0.1`, adatbázis `nyilt_nap`, felhasználó `root`, üres jelszó.
5. Nyisd meg a `http://localhost/nyilt-nap-regisztracio/` oldalt.
6. Az adminfelület a `http://localhost/nyilt-nap-regisztracio/admin/login.php` címen található. Nincs külön adminfiók-létrehozási telepítőlépés.

### Előre beállított admin-belépési adatok

- **Felhasználónév:** `admin`
- **Belépési kód:** `NTSZKI-nyiltnap-2026`

A belépési kód hash-ként szerepel az `app/config.php` fájlban, nem olvasható jelszóként. A felhasználónév és a hash környezeti változókkal is felülírható (`ADMIN_USERNAME`, `ADMIN_PASSWORD_HASH`). Nyilvános, éles környezetben feltétlenül cseréld le az alapértelmezett belépési adatokat, és használj HTTPS-t.

## Saját logó és az iskola neve

- Másold az iskola logóját `logo.png` néven az `assets/` mappába: `nyilt-nap-regisztracio/assets/logo.png`.
- Az oldal tetején a logó az **NTSZKI** felirat mellett jelenik meg. Ha a fájl még nincs ott, visszafogott grafikus helyettesítő látható.
- Az iskola nevét az `app/config.php` `SCHOOL_NAME` értékével tudod módosítani. Az alapértelmezett név NTSZKI.

## Saját szakmalista

Az űrlap a következő hat szakmát mutatja: CNC Programozó; Mechatronikai technikus; Mechatronikus karbantartó; Pénzügyi-számviteli ügyintéző; Szerszám- és készülékgyártó; Szoftverfejlesztő és -tesztelő. A szakmák az adatbázisban is létrejönnek. Az ikonok a felületen belüli SVG-ikonok, ezért külső ikonletöltés nem szükséges.

Ne törölj olyan szakmát, amelyhez már jelentkezések kapcsolódnak; inkább nevezd át, vagy előbb kezeld a kapcsolódó jelentkezéseket.

## Környezet és éles használat

- PHP 8.1 vagy újabb
- MySQL 5.7+ vagy kompatibilis MariaDB
- `PDO` és `pdo_mysql` PHP-bővítmény
- Használj HTTPS-t, erős admin-belépési kódot és külön adatbázis-felhasználót, valamint készíts biztonsági mentéseket.
- A hozzájárulási szöveget igazítsd az iskola tényleges adatkezelési tájékoztatójához. A jelenlegi szöveg kiindulópont, nem helyettesíti az adatkezelési tájékoztatót.

## Projektstruktúra

```text
nyilt-nap-regisztracio/
├── index.php                 # Nyilvános oldal és szakmalista betöltése
├── submit.php                # Jelentkezés validálása és mentése
├── app/
│   ├── bootstrap.php         # Session és közös betöltések
│   ├── config.php            # Adatbázis, NTSZKI név, admin belépés
│   ├── database.php          # PDO-kapcsolat
│   └── functions.php         # Közös segédfüggvények
├── admin/
│   ├── login.php             # Előre beállított admin belépés
│   ├── index.php             # Admin vezérlő és statisztikák
│   ├── delete.php            # Jelentkezés törlése
│   └── logout.php            # Kijelentkezés
├── views/
│   ├── home.php              # Nyilvános űrlap és tipppanel
│   └── admin-dashboard.php   # Admin frontend
├── assets/
│   ├── home.css              # Tabletbarát nyilvános űrlapstílusok
│   ├── app.css               # Adminfelület és belépőoldal stílusai
│   ├── app.js                # Űrlap és tippek viselkedése
│   └── logo.png              # Saját logó (ezt neked kell bemásolnod)
└── database/schema.sql       # Adatbázis-séma és minta szakmalista
```
