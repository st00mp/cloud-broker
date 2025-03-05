<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250213205323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE instance_detail (id INT AUTO_INCREMENT NOT NULL, provider_id INT NOT NULL, instance_type VARCHAR(50) NOT NULL, gpu_model VARCHAR(50) NOT NULL, vram INT NOT NULL, vcpu INT NOT NULL, ram INT NOT NULL, network_performance VARCHAR(20) NOT NULL, os_supported LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_6D6308E8A4D407E2 (instance_type), INDEX IDX_6D6308E8A53A8AA (provider_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE instance_spot (id INT AUTO_INCREMENT NOT NULL, instance_detail_id INT NOT NULL, spot_price NUMERIC(10, 5) NOT NULL, availability_zone VARCHAR(50) NOT NULL, timestamp DATETIME NOT NULL, INDEX IDX_91382AB8DDD2FEAF (instance_detail_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE provider (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_92C4739C5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE instance_detail ADD CONSTRAINT FK_6D6308E8A53A8AA FOREIGN KEY (provider_id) REFERENCES provider (id)');
        $this->addSql('ALTER TABLE instance_spot ADD CONSTRAINT FK_91382AB8DDD2FEAF FOREIGN KEY (instance_detail_id) REFERENCES instance_detail (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE instance_detail DROP FOREIGN KEY FK_6D6308E8A53A8AA');
        $this->addSql('ALTER TABLE instance_spot DROP FOREIGN KEY FK_91382AB8DDD2FEAF');
        $this->addSql('DROP TABLE instance_detail');
        $this->addSql('DROP TABLE instance_spot');
        $this->addSql('DROP TABLE provider');
    }
}
