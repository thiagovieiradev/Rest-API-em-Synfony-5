<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220317173735 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE turma_colaborador (turma_id INT NOT NULL, colaborador_id INT NOT NULL, INDEX IDX_CA649EB8CEBA2CFD (turma_id), INDEX IDX_CA649EB8F1CB264E (colaborador_id), PRIMARY KEY(turma_id, colaborador_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE turma_colaborador ADD CONSTRAINT FK_CA649EB8CEBA2CFD FOREIGN KEY (turma_id) REFERENCES turma (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE turma_colaborador ADD CONSTRAINT FK_CA649EB8F1CB264E FOREIGN KEY (colaborador_id) REFERENCES colaborador (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE turma_colaborador');
    }
}
