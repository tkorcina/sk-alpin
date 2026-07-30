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

## Rozpracováno / další kroky

- [ ] Entity dle datového modelu (Event, News, Gallery, Photo, Page, File, ContactMessage) + traity/enumy.
- [ ] Query objekty + factory pro nové entity, FileService.
- [ ] Migrace DB (diff + seed výchozích stránek).
- [ ] Kostra veřejné části: presentery + šablony (Akce, Aktuality, Fotogalerie, O nás, Kontakt) + hezké routy.
- [ ] Kontaktní formulář (uložení ContactMessage + odeslání e-mailu).
- [ ] Stub presenterů administrace + menu (CRUD až ve Fázi 4).

## Log

- **2026-07-30** — Založen progress log, uloženo zadání. Prozkoumána firemní šablona a referenční projekt `ondrejzamecnik.loc`, odsouhlasen rozsah kostry: entity + model + migrace + kostra veřejné části.
