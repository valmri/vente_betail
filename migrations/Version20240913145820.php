<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240913145820 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animal_photo ADD animal_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE animal_photo ADD CONSTRAINT FK_35445DEC8E962C16 FOREIGN KEY (animal_id) REFERENCES animal (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_35445DEC8E962C16 ON animal_photo (animal_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE animal_photo DROP CONSTRAINT FK_35445DEC8E962C16');
        $this->addSql('DROP INDEX IDX_35445DEC8E962C16');
        $this->addSql('ALTER TABLE animal_photo DROP animal_id');
    }
}
