<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260922120000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Seed nastaveni webu (tabulka config) — nazev, motto, popisek, udaje spolku';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO config (`key`, `value`, `type`) VALUES ('siteTitle', 'SK-Alpin', 'string')");
        $this->addSql("INSERT INTO config (`key`, `value`, `type`) VALUES ('motto', 'Orientační běh, pěší, vodní, lyžařská, letecká i motoristická turistika. Výlety a akce i pro veřejnost.', 'string')");
        $this->addSql("INSERT INTO config (`key`, `value`, `type`) VALUES ('description', 'SK-Alpin z.s. je spolek pro sport a pohyb v přírodě — orientační běh, pěší, vodní, lyžařskou, leteckou i motoristickou turistiku. Pořádáme výlety a akce i pro veřejnost.', 'string')");
        $this->addSql("INSERT INTO config (`key`, `value`, `type`) VALUES ('companyName', 'SK-Alpin z.s.', 'string')");
        $this->addSql("INSERT INTO config (`key`, `value`, `type`) VALUES ('companyAddress', 'Potocká 107/53, Kohoutovice, 623 00 Brno', 'string')");
        $this->addSql("INSERT INTO config (`key`, `value`, `type`) VALUES ('companyIco', '22688391', 'string')");
        $this->addSql("INSERT INTO config (`key`, `value`, `type`) VALUES ('logo', '', 'file')");
        $this->addSql("INSERT INTO config (`key`, `value`, `type`) VALUES ('heroImage', '', 'file')");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM config WHERE `key` IN ('siteTitle', 'motto', 'description', 'companyName', 'companyAddress', 'companyIco', 'logo', 'heroImage')");
    }
}
