<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220316164123 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE colaborador (id INT AUTO_INCREMENT NOT NULL, nome VARCHAR(45) NOT NULL, sobrenome VARCHAR(45) DEFAULT NULL, cpf VARCHAR(15) NOT NULL, email VARCHAR(45) NOT NULL, data_nascimento DATE NOT NULL, data_admissao DATE NOT NULL, deleted_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE colaborador_competencia (colaborador_id INT NOT NULL, competencia_id INT NOT NULL) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE colaborador_competencia');
        $this->addSql('DROP TABLE colaborador');
    }
}
