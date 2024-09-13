<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240913142353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE animal (id INT NOT NULL, type_id INT DEFAULT NULL, statut_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, age INT NOT NULL, description TEXT NOT NULL, prix_ttc DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6AAB231FC54C8C93 ON animal (type_id)');
        $this->addSql('CREATE INDEX IDX_6AAB231FF6203804 ON animal (statut_id)');
        $this->addSql('CREATE TABLE animal_type (id INT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE animalrace (id INT NOT NULL, type_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_88AE9BBC54C8C93 ON animalrace (type_id)');
        $this->addSql('CREATE TABLE statut_vente (id INT NOT NULL, nom VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE animal ADD CONSTRAINT FK_6AAB231FC54C8C93 FOREIGN KEY (type_id) REFERENCES animal_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE animal ADD CONSTRAINT FK_6AAB231FF6203804 FOREIGN KEY (statut_id) REFERENCES statut_vente (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE animalrace ADD CONSTRAINT FK_88AE9BBC54C8C93 FOREIGN KEY (type_id) REFERENCES animal_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE animal DROP CONSTRAINT FK_6AAB231FC54C8C93');
        $this->addSql('ALTER TABLE animal DROP CONSTRAINT FK_6AAB231FF6203804');
        $this->addSql('ALTER TABLE animalrace DROP CONSTRAINT FK_88AE9BBC54C8C93');
        $this->addSql('DROP TABLE animal');
        $this->addSql('DROP TABLE animal_type');
        $this->addSql('DROP TABLE animalrace');
        $this->addSql('DROP TABLE statut_vente');
    }
}
