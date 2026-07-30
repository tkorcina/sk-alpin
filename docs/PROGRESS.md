# SK-Alpin — postup implementace

Průběžný log toho, co je hotové, co se dělá a co je dál. Zadání viz [zadani.md](zadani.md).

## Stav

- Projekt běží lokálně na http://sk-alpin.loc/ (nginx + MySQL, DB `sk-alpin_local`).
- Nette skeleton dle firemní šablony už byl založen (composer, gulp, config, moduly Public/Admin, přihlašování do administrace, Config/User agenda).

## Rozhodnutí

- **Jednojazyčný obsah (čeština)** — entity nemají `*En` sloupce (na rozdíl od referenčního projektu ondrejzamecnik.loc). Web je pro český spolek, zadání vícejazyčnost nepožaduje.
- **Fotky přes entitu `File`** — soubory na disku (`www/data/upload`), metadata v tabulce `file` (konvence firemní šablony, `FileService`). `Photo` = vazba galerie ↔ file + pořadí + popisek.
- **Statické stránky (`Page`)** se identifikují přes `internalName` (`home`, `about`, `contact`) — konstanty v `App\Model\Entity\Enum\Page`.
- **Typ akce** je string sloupec s konstantami v `App\Model\Entity\Enum\EventType` (pěší, voda, lyže, letecká, moto, orienťák, jiné) — stejný styl jako `ConfigType`.

## Hotovo

- [x] Zadání a progress log v `docs/`.
- [x] Entity dle datového modelu: `Event`, `News`, `Gallery`, `Photo`, `Page`, `File`, `ContactMessage` + trait `TPublished`, enumy `EventType`, `Enum\Page`.
- [x] Query objekty + factory: Event, News, Gallery, Page, ContactMessage (published/slug/upcoming/past filtry, výchozí řazení).
- [x] `FileService` (upload do `www/data/upload`, registrace v `config.neon`).

- [x] Migrace DB (`Version20260730104424` — nové tabulky, `Version20260730104500` — seed stránek home/about/contact), spuštěno na lokální DB, `orm:validate-schema` OK.

- [x] Kostra veřejné části: presentery + šablony (Homepage, Akce, Aktuality, Fotogalerie, O nás, Kontakt), navigace v layoutu, hezké routy (`/akce`, `/aktuality`, `/fotogalerie`, `/o-nas`, `/kontakt`).
- [x] Kontaktní formulář — uložení `ContactMessage` + odeslání e-mailu (parametry `contactEmail` / `noReplyEmail` v configu, honeypot antispam).

- [x] Administrace: CRUD pro akce, aktuality a fotogalerie — gridy (ublaboo přes `BaseGrid`), formuláře (CKEditor na popisech, upload obrázku u aktuality, hromadný upload fotek u galerie + mazání jednotlivých fotek), menu v layoutu. Otestováno přes přihlášení: vytvoření/editace/smazání akce, aktualita s obrázkem, galerie se 2 fotkami, smazání fotky.

- [x] Stylování veřejné části dle zadání (cílovka 40–65 let): přírodní paleta (lesní zelená `#2c5e3f`, jezerní modrá, zemitá hnědá, krémové pozadí), základní písmo 17 px / řádkování 1.65, hero s horami (SVG silueta), karty s datem/typem akce, aktivní položka v menu, sticky patička. SCSS v `www/src/scss` (hlavně `_page.scss`), build `./node_modules/.bin/gulp css` (funguje bez sudo, když `www/dist/css` vlastní korca).

- [x] Administrace: editace stránek (grid bez mazání/přidávání — fixní home/about/contact, WYSIWYG, obrázek) a přehled zpráv z kontaktního formuláře (grid s mailto odkazy, mazání). Menu administrace doplněno.
- [x] Kontaktní formulář: selhání SMTP nepoloží odeslání — zpráva se vždy uloží do DB, chyba se loguje do `log/mailer.log` (nesmí se logovat s prioritou exception, Tracy by ji zkoušela poslat tímtéž rozbitým SMTP).
- [x] Lightbox ve fotogalerii — GLightbox (yarn balíček, zapojený do gulp js/css, init v `www/src/js/base.js`, třída `.glightbox` v šabloně detailu galerie).
- [x] Responsivní hamburger menu pod 991 px — animovaná ikonka (3 čáry → křížek), bootstrap collapse, odkazy pod sebou.

## Rozpracováno / další kroky

- [ ] Skutečná hero fotka z hor místo CSS přechodu (až budou podklady).
- [ ] Responsivní kontrola na reálném mobilu (mechanika collapse ověřena přes JS, vizuálně neověřeno — resize okna přes automatizaci nefungoval).
- [ ] Resize/náhledy obrázků při uploadu (Nette Image) — zbytek Fáze 3.
- [ ] Fáze 5: SEO (sitemap už má routu, meta popisky), favicon, GDPR lišta, nasazení.
- [ ] Resize obrázků + náhledy (Nette Image) a lightbox v galerii — Fáze 3.
- [ ] Hero fotka, styly a „turistický“ vzhled (Fáze 2/5) — teď je jen minimální markup nad firemní šablonou.
- [ ] Stránkování aktualit doladit (komponenta Paginator je zapojená, chybí ověření s více záznamy).

## Přístupy (lokální)

- Admin: `tomask@appsdevteam.com` / `admin123` (uživatel vložen ručně do lokální DB, v produkci nahradit).

## Log

- **2026-07-30** — Založen progress log, uloženo zadání. Prozkoumána firemní šablona a referenční projekt `ondrejzamecnik.loc`, odsouhlasen rozsah kostry: entity + model + migrace + kostra veřejné části.
- **2026-07-30** — Modelová vrstva: entity, enumy, trait TPublished, query objekty s factory, FileService.
- **2026-07-30** — Migrace vygenerovány a spuštěny na lokální DB (pozn.: `temp/cache` vlastní www-data, CLI příkazy je potřeba pouštět přes `sudo make m-diff` / `m-migrate`, nebo s vlastním temp adresářem).
- **2026-07-30** — Veřejná část: presentery, šablony, routy, kontaktní formulář. Ověřeno na http://sk-alpin.loc/ (všechny stránky 200, 404 funguje, formulář se vykresluje, `/administrace` přesměruje na login). Pozn.: kvůli neznámým třídám v RobotLoader cache přejmenován `temp/cache` → `temp/cache.old` (nešlo smazat bez sudo) — **smazat ručně: `sudo rm -rf temp/cache.old temp/cache.old2`**.
- **2026-07-30** — Administrace: CRUD akce/aktuality/galerie, vytvořen lokální admin uživatel, vše otestováno end-to-end přes HTTP (login, uložení, upload, mazání). Testovací data po ověření smazána.
- **2026-07-30** — Stylování veřejné části, ověřeno v Chrome (úvod, akce, galerie, kontakt). V DB nechána ukázková data (5 akcí, 3 aktuality, 2 galerie s placeholder obrázky `www/data/upload/sample_*.png`) — před produkcí smazat. Úklid pro sudo: `sudo rm -rf temp/cache.old temp/cache.old2 temp/cache.old3 www/dist/css.old`.
- **2026-07-30** — Dokončena Fáze 4 (stránky + zprávy), GLightbox, hamburger menu. Vše otestováno (uložení stránky se propíše na web, zpráva z formuláře se uloží i při nefunkčním SMTP, lightbox se otevírá, collapse menu funguje). V přehledu zpráv nechány 1–2 testovací zprávy. Úklid pro sudo navíc: `sudo rm -rf temp/cache.old4 temp/cache.old5 www/dist/css.old-root www/dist/js.old`.
