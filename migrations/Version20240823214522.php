<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240823214522 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE carnet_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE parcela_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE carnet (id INT NOT NULL, valor_total DOUBLE PRECISION NOT NULL, valor_entrada DOUBLE PRECISION DEFAULT NULL, qtd_parcelas INT NOT NULL, periodicidade VARCHAR(255) NOT NULL, data_primeiro_venimento TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN carnet.data_primeiro_venimento IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE parcela (id INT NOT NULL, carnet_id INT NOT NULL, valor DOUBLE PRECISION NOT NULL, numero INT NOT NULL, entrada BOOLEAN NOT NULL, data_vencimento TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_A5CC4446FA207516 ON parcela (carnet_id)');
        $this->addSql('COMMENT ON COLUMN parcela.data_vencimento IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE parcela ADD CONSTRAINT FK_A5CC4446FA207516 FOREIGN KEY (carnet_id) REFERENCES carnet (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE carnet_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE parcela_id_seq CASCADE');
        $this->addSql('ALTER TABLE parcela DROP CONSTRAINT FK_A5CC4446FA207516');
        $this->addSql('DROP TABLE carnet');
        $this->addSql('DROP TABLE parcela');
    }
}
