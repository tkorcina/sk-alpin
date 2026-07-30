<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260730104424 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact_message (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, date_from DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', date_to DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', place VARCHAR(255) NOT NULL, `type` VARCHAR(255) DEFAULT \'other\' NOT NULL, description LONGTEXT NOT NULL, is_public TINYINT(1) DEFAULT 1 NOT NULL, published TINYINT(1) DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE file (id INT AUTO_INCREMENT NOT NULL, file_name VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, content_type VARCHAR(255) DEFAULT NULL, `type` VARCHAR(255) DEFAULT NULL, active TINYINT(1) DEFAULT 1 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE gallery (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, main_image_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', published TINYINT(1) DEFAULT 0 NOT NULL, INDEX IDX_472B783A71F7E88B (event_id), INDEX IDX_472B783AE4873418 (main_image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE news (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, published_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', content LONGTEXT NOT NULL, published TINYINT(1) DEFAULT 0 NOT NULL, INDEX IDX_1DD399503DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE page (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, internal_name VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_140AB620B2A3EFCD (internal_name), INDEX IDX_140AB6203DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photo (id INT AUTO_INCREMENT NOT NULL, gallery_id INT NOT NULL, file_id INT NOT NULL, sort INT DEFAULT 0 NOT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_14B784184E7AF8F (gallery_id), INDEX IDX_14B7841893CB796C (file_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE gallery ADD CONSTRAINT FK_472B783A71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE gallery ADD CONSTRAINT FK_472B783AE4873418 FOREIGN KEY (main_image_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE news ADD CONSTRAINT FK_1DD399503DA5256D FOREIGN KEY (image_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE page ADD CONSTRAINT FK_140AB6203DA5256D FOREIGN KEY (image_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B784184E7AF8F FOREIGN KEY (gallery_id) REFERENCES gallery (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE photo ADD CONSTRAINT FK_14B7841893CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gallery DROP FOREIGN KEY FK_472B783A71F7E88B');
        $this->addSql('ALTER TABLE gallery DROP FOREIGN KEY FK_472B783AE4873418');
        $this->addSql('ALTER TABLE news DROP FOREIGN KEY FK_1DD399503DA5256D');
        $this->addSql('ALTER TABLE page DROP FOREIGN KEY FK_140AB6203DA5256D');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B784184E7AF8F');
        $this->addSql('ALTER TABLE photo DROP FOREIGN KEY FK_14B7841893CB796C');
        $this->addSql('DROP TABLE contact_message');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP TABLE gallery');
        $this->addSql('DROP TABLE news');
        $this->addSql('DROP TABLE page');
        $this->addSql('DROP TABLE photo');
    }
}
