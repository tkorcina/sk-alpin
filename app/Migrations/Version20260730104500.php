<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260730104500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Seed vychozich stranek (home, about, contact)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO page (internal_name, title, content) VALUES ('home', 'SK-Alpin z.s.', '<p>SK-Alpin z.s. je spolek pro sport a pohyb v přírodě — orientační běh, pěší, vodní, lyžařskou, leteckou i motoristickou turistiku. Pořádáme výlety a akce i pro veřejnost.</p>')");
        $this->addSql("INSERT INTO page (internal_name, title, content) VALUES ('about', 'O nás', '<p>Poslání a činnost spolku doplníme.</p>')");
        $this->addSql("INSERT INTO page (internal_name, title, content) VALUES ('contact', 'Kontakt', '<p>SK-Alpin z.s.<br>Potocká 107/53, Kohoutovice, 623 00 Brno<br>IČO: 22688391</p>')");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM page WHERE internal_name IN ('home', 'about', 'contact')");
    }
}
