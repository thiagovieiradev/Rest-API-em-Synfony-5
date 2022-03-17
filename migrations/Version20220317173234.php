<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220317173234 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE turma (id INT AUTO_INCREMENT NOT NULL, status_id INT NOT NULL, nome VARCHAR(45) NOT NULL, descricao VARCHAR(255) DEFAULT NULL, data_inicio DATE NOT NULL, data_termino DATE DEFAULT NULL, INDEX IDX_2B0219A66BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE turma ADD CONSTRAINT FK_2B0219A66BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE colaborador CHANGE deleted_at deleted_at DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE turma');
        $this->addSql('ALTER TABLE colaborador CHANGE deleted_at deleted_at DATETIME DEFAULT NULL');
    }
}
