<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240329071314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaire CHANGE id_post id_post INT DEFAULT NULL');
        $this->addSql('ALTER TABLE events CHANGE id_musee id_musee INT DEFAULT NULL');
        $this->addSql('ALTER TABLE livraison CHANGE idAdresse idAdresse INT DEFAULT NULL');
        $this->addSql('ALTER TABLE museum ADD image_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE panier CHANGE id_commande id_commande INT DEFAULT NULL');
        $this->addSql('ALTER TABLE post CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE is_blocked is_blocked TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE commentaire CHANGE id_post id_post INT NOT NULL');
        $this->addSql('ALTER TABLE events CHANGE id_musee id_musee INT NOT NULL');
        $this->addSql('ALTER TABLE livraison CHANGE idAdresse idAdresse INT NOT NULL');
        $this->addSql('ALTER TABLE museum DROP image_name');
        $this->addSql('ALTER TABLE panier CHANGE id_commande id_commande INT NOT NULL');
        $this->addSql('ALTER TABLE post CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE is_blocked is_blocked TINYINT(1) DEFAULT 0 NOT NULL');
    }
}
