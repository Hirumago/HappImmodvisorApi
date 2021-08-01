<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210801195727 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE category_event (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, link_image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_category_event (event_id INT NOT NULL, category_event_id INT NOT NULL, INDEX IDX_CD9F39D71F7E88B (event_id), INDEX IDX_CD9F39DC68D6CF0 (category_event_id), PRIMARY KEY(event_id, category_event_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event_category_event ADD CONSTRAINT FK_CD9F39D71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_category_event ADD CONSTRAINT FK_CD9F39DC68D6CF0 FOREIGN KEY (category_event_id) REFERENCES category_event (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE event_category_event DROP FOREIGN KEY FK_CD9F39DC68D6CF0');
        $this->addSql('DROP TABLE category_event');
        $this->addSql('DROP TABLE event_category_event');
    }
}
