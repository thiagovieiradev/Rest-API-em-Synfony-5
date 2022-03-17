<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220317111326 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs        
        $this->addSql('CREATE TABLE senioridade (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(45) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE colaborador CHANGE deleted_at deleted_at DATETIME DEFAULT NULL');

        $this->addSql('ALTER TABLE colaborador_competencia ADD CONSTRAINT FK_842C498A7E7357941 FOREIGN KEY (colaborador_id) REFERENCES colaborador (id)');

        $this->addSql('ALTER TABLE colaborador_competencia ADD CONSTRAINT FK_842C498A7E7357942 FOREIGN KEY (competencia_id) REFERENCES competencia (id)');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs        
        $this->addSql('DROP TABLE senioridade');
        $this->addSql('ALTER TABLE colaborador CHANGE deleted_at deleted_at DATETIME DEFAULT NULL');        
    }
}
