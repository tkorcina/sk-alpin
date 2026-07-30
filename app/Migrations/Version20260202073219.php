<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260202073219 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE session_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE config (id SERIAL NOT NULL, "key" VARCHAR(255) NOT NULL, "value" TEXT NOT NULL, "type" VARCHAR(255) DEFAULT \'string\' NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE session (id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, object_id VARCHAR(32) NOT NULL, token VARCHAR(32) NOT NULL, valid_until TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, ip VARCHAR(15) DEFAULT NULL, user_agent VARCHAR(255) DEFAULT NULL, fraud_data JSON DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D044D5D45F37A13B ON session (token)');
        $this->addSql('CREATE INDEX token_idx ON session (token)');
        $this->addSql('COMMENT ON COLUMN session.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN session.valid_until IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE session_storage (id SERIAL NOT NULL, session_id VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, data TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX sessionId ON session_storage (session_id)');
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone_number VARCHAR(255) DEFAULT NULL, password VARCHAR(255) DEFAULT NULL, reset_password_hash VARCHAR(255) DEFAULT NULL, active BOOLEAN DEFAULT true NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6496B01BC5B ON "user" (phone_number)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE session_id_seq CASCADE');
        $this->addSql('DROP TABLE config');
        $this->addSql('DROP TABLE session');
        $this->addSql('DROP TABLE session_storage');
        $this->addSql('DROP TABLE "user"');
    }
}
