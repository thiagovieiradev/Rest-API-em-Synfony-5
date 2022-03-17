<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220317173636 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE turma_disciplina (turma_id INT NOT NULL, disciplina_id INT NOT NULL, INDEX IDX_590156F1CEBA2CFD (turma_id), INDEX IDX_590156F12A30B056 (disciplina_id), PRIMARY KEY(turma_id, disciplina_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE turma_disciplina ADD CONSTRAINT FK_590156F1CEBA2CFD FOREIGN KEY (turma_id) REFERENCES turma (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE turma_disciplina ADD CONSTRAINT FK_590156F12A30B056 FOREIGN KEY (disciplina_id) REFERENCES disciplina (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE turma_disciplina');
    }
}
