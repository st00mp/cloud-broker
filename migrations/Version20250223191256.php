<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250223191256 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX idx_gpu_model ON instance_detail');
        $this->addSql('ALTER TABLE instance_detail ADD number_of_gpus INT DEFAULT 0 NOT NULL');
        $this->addSql('DROP INDEX idx_spot_price ON instance_spot');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE instance_detail DROP number_of_gpus');
        $this->addSql('CREATE INDEX idx_gpu_model ON instance_detail (gpu_model)');
        $this->addSql('CREATE INDEX idx_spot_price ON instance_spot (spot_price)');
    }
}
