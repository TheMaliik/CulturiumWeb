<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240412185415 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE events DROP FOREIGN KEY fk_key');
        $this->addSql('CREATE TABLE historique (id INT AUTO_INCREMENT NOT NULL, etat VARCHAR(255) NOT NULL, date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE museum (IdM INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, localisation VARCHAR(255) NOT NULL, PRIMARY KEY(IdM)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE musee');
        $this->addSql('ALTER TABLE adresse ADD codePostal INT NOT NULL, DROP code_postal, CHANGE adresse adresse VARCHAR(50) NOT NULL, CHANGE id_utilisateur idCommande INT NOT NULL');
        $this->addSql('ALTER TABLE categorie CHANGE id_oeuvre id_oeuvre INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commande ADD status VARCHAR(255) NOT NULL, DROP Date, CHANGE addresse_livraison contenue VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY fff_key');
        $this->addSql('DROP INDEX fff_key ON commentaire');
        $this->addSql('ALTER TABLE commentaire CHANGE date date DATE DEFAULT \'CURRENT_TIMESTAMP\' NOT NULL, CHANGE description descreption VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX fff_key ON commentaire (id_post)');
        $this->addSql('ALTER TABLE events MODIFY id INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON events');
        $this->addSql('ALTER TABLE events ADD nbr_place_dispo INT NOT NULL, ADD lieu VARCHAR(255) NOT NULL, ADD note INT NOT NULL, CHANGE id_musee id_musee INT DEFAULT NULL, CHANGE id IdE INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT FK_5387574A66018943 FOREIGN KEY (id_musee) REFERENCES museum (IdM)');
        $this->addSql('ALTER TABLE events ADD PRIMARY KEY (IdE)');
        $this->addSql('ALTER TABLE livraison ADD jourDeLivraison VARCHAR(50) NOT NULL, DROP dateDeLivraison, CHANGE idAdresse idAdresse INT DEFAULT NULL');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT FK_A60C9F1FD3E663E4 FOREIGN KEY (idAdresse) REFERENCES adresse (id)');
        $this->addSql('CREATE INDEX ffk_key ON livraison (idAdresse)');
        $this->addSql('ALTER TABLE oeuvre ADD nom_oeuvre VARCHAR(255) NOT NULL, ADD type_oeuvre VARCHAR(255) NOT NULL, ADD LinkHttp VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY fffff_key');
        $this->addSql('DROP INDEX fffff_key ON panier');
        $this->addSql('ALTER TABLE panier ADD id_oeuvre INT DEFAULT NULL, ADD id_command INT DEFAULT NULL, DROP id_commande, DROP article');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF213C99B13 FOREIGN KEY (id_oeuvre) REFERENCES oeuvre (id)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF2505743A4 FOREIGN KEY (id_command) REFERENCES commande (id)');
        $this->addSql('CREATE INDEX keyss ON panier (id_oeuvre)');
        $this->addSql('CREATE INDEX fffff_key ON panier (id_command)');
        $this->addSql('ALTER TABLE post ADD image_path VARCHAR(200) DEFAULT NULL, DROP description, CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE user ADD is_blocked TINYINT(1) NOT NULL, ADD is_approved TINYINT(1) DEFAULT 1 NOT NULL, ADD role JSON NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE events DROP FOREIGN KEY FK_5387574A66018943');
        $this->addSql('CREATE TABLE musee (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, image VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, localisation VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, id_musee INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE historique');
        $this->addSql('DROP TABLE museum');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE adresse ADD id_utilisateur INT NOT NULL, ADD code_postal VARCHAR(255) NOT NULL, DROP idCommande, DROP codePostal, CHANGE adresse adresse VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE categorie CHANGE id_oeuvre id_oeuvre INT NOT NULL');
        $this->addSql('ALTER TABLE commande ADD Date DATE NOT NULL, ADD addresse_livraison VARCHAR(255) NOT NULL, DROP contenue, DROP status');
        $this->addSql('DROP INDEX fff_key ON commentaire');
        $this->addSql('ALTER TABLE commentaire CHANGE date date INT NOT NULL, CHANGE descreption description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT fff_key FOREIGN KEY (id_post) REFERENCES post (id)');
        $this->addSql('CREATE INDEX fff_key ON commentaire (id_post)');
        $this->addSql('ALTER TABLE events MODIFY IdE INT NOT NULL');
        $this->addSql('DROP INDEX `PRIMARY` ON events');
        $this->addSql('ALTER TABLE events DROP nbr_place_dispo, DROP lieu, DROP note, CHANGE id_musee id_musee INT NOT NULL, CHANGE IdE id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT fk_key FOREIGN KEY (id_musee) REFERENCES musee (id)');
        $this->addSql('ALTER TABLE events ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY FK_A60C9F1FD3E663E4');
        $this->addSql('DROP INDEX ffk_key ON livraison');
        $this->addSql('ALTER TABLE livraison ADD dateDeLivraison DATE NOT NULL, DROP jourDeLivraison, CHANGE idAdresse idAdresse INT NOT NULL');
        $this->addSql('ALTER TABLE oeuvre DROP nom_oeuvre, DROP type_oeuvre, DROP LinkHttp');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF213C99B13');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF2505743A4');
        $this->addSql('DROP INDEX keyss ON panier');
        $this->addSql('DROP INDEX fffff_key ON panier');
        $this->addSql('ALTER TABLE panier ADD id_commande INT NOT NULL, ADD article VARCHAR(255) NOT NULL, DROP id_oeuvre, DROP id_command');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT fffff_key FOREIGN KEY (id_commande) REFERENCES commande (id)');
        $this->addSql('CREATE INDEX fffff_key ON panier (id_commande)');
        $this->addSql('ALTER TABLE post ADD description VARCHAR(255) NOT NULL, DROP image_path, CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE user DROP is_blocked, DROP is_approved, DROP role');
    }
}
