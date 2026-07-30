# SK-Alpin z.s. — návrh prezentačního webu

## 1. Základní informace

- **Název:** SK-Alpin z.s.
- **Sídlo:** Potocká 107/53, Kohoutovice, 623 00 Brno
- **IČO:** 22688391 ([rejstřík](https://rejstrik-firem.kurzy.cz/…-s/))
- **Zaměření:** sport a pohyb v přírodě — orientační běh, pěší / vodní / lyžařská / letecká / motoristická turistika; výlety a akce i pro veřejnost.
- **Cílová skupina webu:** cca 50–60 let → důraz na čitelnost, jednoduchost a přehlednost, ne na efektní moderní prvky.

## 2. Cíl webu

Jednoduchý prezentační web, který:

1. představí spolek a jeho činnost,
2. zobrazí **kalendář akcí** (i pro veřejnost),
3. umožní publikovat **fotografie z akcí**,
4. publikuje **aktuality**,
5. nabídne **kontakt** (včetně kontaktního formuláře → e-mailová schránka spolku),
6. má **oddělenou administraci** pro správu veškerého obsahu.

## 3. Struktura veřejné části

### 3.1 Úvodní stránka
- Hlavička s logem/názvem spolku a jednoduchou navigací.
- Úvodní fotka z přírody/hor (hero) s krátkým mottem.
- Krátké představení spolku (2–3 odstavce).
- **Nejbližší akce** (3 nadcházející z kalendáře).
- **Poslední aktuality** (2–3 nejnovější).
- Upoutávka na fotogalerii (poslední galerie).

### 3.2 O nás
- Poslání a činnost spolku (text z účelu založení).
- Historie, členové/vedení (volitelně).
- Informace o členství — případně jak se stát členem.

### 3.3 Akce (kalendář)
- **Seznam akcí** — nadcházející + archiv proběhlých.
- Detail akce: název, datum (od–do), místo, popis, typ akce (pěší, voda, lyže, …), určeno pro veřejnost ano/ne, případně pokyny/mapka.
- Po akci lze k akci připojit fotogalerii.
- Volitelně: jednoduché přihlášení na akci formulářem (jméno, e-mail, telefon) → e-mail organizátorovi.

### 3.4 Fotogalerie
- Přehled galerií (náhledová fotka + název + datum).
- Galerie typicky vázaná na akci, ale může být i samostatná.
- Prohlížení fotek v lightboxu - otevírací "prohlížeč" fotek (velké náhledy, šipky — jednoduché ovládání).

### 3.5 Aktuality
- Krátké zprávy: titulek, datum, text, případně fotka.
- Detail aktuality + výpis s stránkováním.

### 3.6 Kontakt
- Sídlo, IČO, e-mail, telefon, případně č. účtu pro členské příspěvky.
- Kontaktní formulář (jméno, e-mail, zpráva) → odesílá na e-mail spolku.
- Volitelně: Mapa sídla (vložená mapa Mapy.cz / Google Maps).

### 3.7 Patička
- Kontaktní údaje, případně GDPR/cookies info.

## 4. Administrace (oddělená, `/admin`)

- Přihlášení jménem a heslem (role stačí jedna — správce; volitelně druhá role „redaktor“).
- **Správa akcí** — CRUD: název, termín, místo, typ, popis, pro veřejnost, zveřejněno ano/ne.
- **Správa aktualit** — CRUD: titulek, datum, text (jednoduchý WYSIWYG editor), obrázek.
- **Správa fotogalerií** — vytvoření galerie, hromadný upload fotek (automatické zmenšení/náhledy), přiřazení k akci, řazení, mazání.
- **Správa obsahu stránek** — editovatelné texty (úvod, o nás, kontakt) přes WYSIWYG.
- **Přehled zpráv z kontaktního formuláře** (volitelně — primárně chodí e-mailem).
- Ovládání administrace maximálně jednoduché — přehledné tabulky (datagrid), velká tlačítka.

## 5. Design

- **Styl:** klasický, čistý, mírně retro „turistický” vzhled — přírodní barvy (zelená/modrá/zemité tóny), případně motiv hor/vody/lesa.
- **Čitelnost:** větší základní písmo (17–18 px), vysoký kontrast, jasné nadpisy — přizpůsobeno cílové skupině 40–65 let.
- **Jednoduchá navigace:** max. 6 položek v menu, vždy viditelná, žádné skryté vychytávky.
- **Responzivita:** funkční na mobilu i počítači.
- **Fotky jako jeden z hlavních vizuálních prvků** — fotografie z akcí, design má být decentní podklad.

Inspirace vzhledu:
- https://prague.eu/cs/objevujte/sportovni-klub-motorlet/
- https://skzraloci.cz/
- https://skveska.cz/

## 6. Technické řešení

*(návrh — finální stack upřesníme před Fází 1)*

- **Backend:** PHP / Nette 3 + Latte, Doctrine ORM, migrace.
- **Frontend:** Bootstrap 5, lightbox pro galerie (např. GLightbox / Lightbox2), minimum JavaScriptu.
- **Admin datagridy:** `ublaboo/datagrid`.
- **Formuláře:** Nette Forms + `adt/doctrine-forms`, antispam (honeypot / časový limit, případně Turnstile).
- **Obrázky:** upload s automatickým resize (Nette Image) — originál + náhled + web verze; úložiště na disku (`www/upload/…`).
- **E-mail:** `nette/mail` (SMTP schránky spolku) — kontaktní formulář, případně přihlášky na akce.
- **DB:** MySQL.

### 6.1 Datový model (návrh entit)

| Entita | Hlavní pole |
|---|---|
| `Event` | title, slug, dateFrom, dateTo, place, type (enum), description, isPublic, isPublished |
| `News` | title, slug, publishedAt, content, image, isPublished |
| `Gallery` | title, slug, date, event (FK, nullable), isPublished |
| `Photo` | gallery (FK), filename, sort, description |
| `Page` | key (home/about/contact), title, content |
| `User` | email, passwordHash, role |
| `ContactMessage` (volitelně) | name, email, message, createdAt |

## 7. Náměty navíc (k rozhodnutí)

- **Přihlašování na akce** přes formulář (bez registrace) — organizátor dostane e-mail se seznamem.
- **Dokumenty ke stažení** — stanovy, přihláška ke členství, pokyny k akcím (PDF).

## 8. Fáze implementace

### Fáze 0 — Příprava (mimo kód)
- [ ] Doména (např. `sk-alpin.cz`) + hosting VEDOS (PHP 8.2+, MySQL).
- [ ] E-mailová schránka spolku (např. `info@sk-alpin.cz`).
- [ ] Shromáždění podkladů: texty (o nás, kontakty), logo, výběr úvodních fotek, seznam prvních akcí.

### Fáze 1 — Základ projektu
- [ ] Nette skeleton dle firemní šablony (composer, gulp, konfigurace, prostředí).
- [ ] DB + migrace, entity dle datového modelu.
- [ ] Layout veřejné části (hlavička, menu, patička) + základní styly.

### Fáze 2 — Veřejná část
- [ ] Úvodní stránka (hero, představení, nejbližší akce, aktuality).
- [ ] Akce: výpis (nadcházející/archiv) + detail.
- [ ] Aktuality: výpis + detail.
- [ ] Statické stránky: O nás, Kontakt (formulář + mapa + odeslání e-mailu).

### Fáze 3 — Fotogalerie
- [ ] Entity + upload s resize a generováním náhledů.
- [ ] Veřejný přehled galerií + detail s lightboxem.
- [ ] Propojení galerie ↔ akce.

### Fáze 4 — Administrace
- [ ] Přihlášení do `/admin`.
- [ ] CRUD akce, aktuality, galerie (hromadný upload fotek), editace stránek (WYSIWYG).

### Fáze 5 — Dokončení a nasazení
- [ ] Naplnění reálného obsahu.
- [ ] SEO základ (titulky, popisky, sitemap.xml), favicon, OG obrázky.
- [ ] GDPR/cookies lišta (pokud bude analytika).
- [ ] Testování (mobil, starší prohlížeče, čitelnost), nasazení na produkci, záloha DB.

### Fáze 6 — Volitelná rozšíření
- [ ] Přihlašování na akce, dokumenty ke stažení.

## 9. Odhad rozsahu

Jednoduchý web tohoto typu = cca **5 hlavních obrazovek veřejné části + 5 agend v administraci**. Při využití stávající firemní šablony a komponent (datagrid, doctrine-forms) jde o malý projekt realizovatelný po výše uvedených fázích, kde každá fáze je samostatně předveditelná.
