<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260922160000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Nastaveni: Google Analytics ID (cookie lista)';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO config (`key`, `value`, `type`) VALUES ('analyticsId', '', 'string')");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM config WHERE `key` = 'analyticsId'");
    }
}
