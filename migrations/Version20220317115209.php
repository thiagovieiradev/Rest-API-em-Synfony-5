<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220317115209 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE usuario (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(45) NOT NULL, email VARCHAR(45) NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('ALTER TABLE colaborador ADD senioridade_id INT NOT NULL, CHANGE deleted_at deleted_at DATETIME NOT NULL');

        $this->addSql('ALTER TABLE colaborador ADD CONSTRAINT FK_D2F80BB3CA95E016 FOREIGN KEY (senioridade_id) REFERENCES senioridade (id)');

        $this->addSql('CREATE INDEX IDX_D2F80BB3CA95E016 ON colaborador (senioridade_id)');
        
        $this->addSql('ALTER TABLE colaborador_competencia RENAME INDEX fk_842c498a7e7357942 TO IDX_C3AA52F69980C34D');
        $this->addSql('ALTER TABLE colaborador_competencia RENAME INDEX fk_842c498a7e7357941 TO IDX_C3AA52F6F1CB264E');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE usuario');
        $this->addSql('ALTER TABLE colaborador DROP FOREIGN KEY FK_D2F80BB3A0F18E1F');
        $this->addSql('ALTER TABLE colaborador DROP FOREIGN KEY FK_D2F80BB3CA95E016');
        $this->addSql('DROP INDEX IDX_D2F80BB3A0F18E1F ON colaborador');
        $this->addSql('DROP INDEX IDX_D2F80BB3CA95E016 ON colaborador');
        $this->addSql('ALTER TABLE colaborador DROP senioridade_id, CHANGE deleted_at deleted_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE colaborador_competencia DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE colaborador_competencia RENAME INDEX idx_c3aa52f6f1cb264e TO FK_842C498A7E7357941');
        $this->addSql('ALTER TABLE colaborador_competencia RENAME INDEX idx_c3aa52f69980c34d TO FK_842C498A7E7357942');
    }
}
