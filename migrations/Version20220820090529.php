<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220820090529 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE car (id INT NOT NULL, seats INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "gang" (id INT NOT NULL, car_id INT DEFAULT NULL, people INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E6080363C3C6F69F ON "gang" (car_id)');
        $this->addSql('COMMENT ON COLUMN "gang".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE "gang" ADD CONSTRAINT FK_E6080363C3C6F69F FOREIGN KEY (car_id) REFERENCES car (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "gang" DROP CONSTRAINT FK_E6080363C3C6F69F');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE "gang"');
    }
}
