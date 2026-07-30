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

## Rozpracováno / další kroky

- [ ] Administrace: CRUD agendy (akce, aktuality, galerie s hromadným uploadem, stránky WYSIWYG, přehled zpráv) — Fáze 4.
- [ ] Resize obrázků + náhledy (Nette Image) a lightbox v galerii — Fáze 3.
- [ ] Hero fotka, styly a „turistický“ vzhled (Fáze 2/5) — teď je jen minimální markup nad firemní šablonou.
- [ ] Stránkování aktualit doladit (komponenta Paginator je zapojená, chybí ověření s více záznamy).

## Log

- **2026-07-30** — Založen progress log, uloženo zadání. Prozkoumána firemní šablona a referenční projekt `ondrejzamecnik.loc`, odsouhlasen rozsah kostry: entity + model + migrace + kostra veřejné části.
- **2026-07-30** — Modelová vrstva: entity, enumy, trait TPublished, query objekty s factory, FileService.
- **2026-07-30** — Migrace vygenerovány a spuštěny na lokální DB (pozn.: `temp/cache` vlastní www-data, CLI příkazy je potřeba pouštět přes `sudo make m-diff` / `m-migrate`, nebo s vlastním temp adresářem).
- **2026-07-30** — Veřejná část: presentery, šablony, routy, kontaktní formulář. Ověřeno na http://sk-alpin.loc/ (všechny stránky 200, 404 funguje, formulář se vykresluje, `/administrace` přesměruje na login). Pozn.: kvůli neznámým třídám v RobotLoader cache přejmenován `temp/cache` → `temp/cache.old` (nešlo smazat bez sudo) — **smazat ručně: `sudo rm -rf temp/cache.old`**.
