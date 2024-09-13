<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240913145421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE animalrace_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE animal_photo_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE animal_race_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE animal_photo (id INT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE animal_race (id INT NOT NULL, type_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9D42FA9BC54C8C93 ON animal_race (type_id)');
        $this->addSql('ALTER TABLE animal_race ADD CONSTRAINT FK_9D42FA9BC54C8C93 FOREIGN KEY (type_id) REFERENCES animal_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE animalrace DROP CONSTRAINT fk_88ae9bbc54c8c93');
        $this->addSql('DROP TABLE animalrace');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE animal_photo_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE animal_race_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE animalrace_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE animalrace (id INT NOT NULL, type_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_88ae9bbc54c8c93 ON animalrace (type_id)');
        $this->addSql('ALTER TABLE animalrace ADD CONSTRAINT fk_88ae9bbc54c8c93 FOREIGN KEY (type_id) REFERENCES animal_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE animal_race DROP CONSTRAINT FK_9D42FA9BC54C8C93');
        $this->addSql('DROP TABLE animal_photo');
        $this->addSql('DROP TABLE animal_race');
    }
}
