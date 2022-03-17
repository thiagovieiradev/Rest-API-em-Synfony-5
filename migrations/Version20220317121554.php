<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220317121554 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE colaborador_competencia ADD PRIMARY KEY (competencia_id, colaborador_id)');
        $this->addSql('ALTER TABLE senioridade ADD deleted_at DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE colaborador DROP FOREIGN KEY FK_D2F80BB3A0F18E1F');
        $this->addSql('DROP INDEX IDX_D2F80BB3A0F18E1F ON colaborador');
        
        $this->addSql('ALTER TABLE colaborador_competencia DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE senioridade DROP deleted_at');
    }
}
