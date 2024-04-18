<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240408112623 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categorie DROP FOREIGN KEY ffff_key');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY fff_key');
        $this->addSql('ALTER TABLE events DROP FOREIGN KEY fk_key');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY fffff_key');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE events');
        $this->addSql('DROP TABLE musee');
        $this->addSql('DROP TABLE oeuvre');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE adresse CHANGE id id VARCHAR(255) NOT NULL, CHANGE idCommande idcommande VARCHAR(255) NOT NULL, CHANGE adresse adresse VARCHAR(255) NOT NULL, CHANGE codePostal codepostal VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY ffk');
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY ffk');
        $this->addSql('ALTER TABLE livraison CHANGE id id VARCHAR(255) NOT NULL, CHANGE dateDeLivraison datedelivraison VARCHAR(255) NOT NULL, CHANGE idAdresse idAdresse VARCHAR(255) DEFAULT NULL, CHANGE statut statut VARCHAR(255) NOT NULL, CHANGE depot depot VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT FK_A60C9F1FD3E663E4 FOREIGN KEY (idAdresse) REFERENCES adresse (id)');
        $this->addSql('DROP INDEX ffk ON livraison');
        $this->addSql('CREATE INDEX IDX_A60C9F1FD3E663E4 ON livraison (idAdresse)');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT ffk FOREIGN KEY (idAdresse) REFERENCES adresse (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, id_oeuvre INT NOT NULL, type_oeuvre VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX ffff_key (id_oeuvre), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, Date DATE NOT NULL, montant_totale DOUBLE PRECISION NOT NULL, addresse_livraison VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, id_post INT NOT NULL, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date INT NOT NULL, INDEX fff_key (id_post), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE events (id INT AUTO_INCREMENT NOT NULL, id_musee INT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, image VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date DATE NOT NULL, INDEX fk_key (id_musee), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE musee (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, image VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, localisation VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, id_musee INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE oeuvre (id INT AUTO_INCREMENT NOT NULL, image VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, nom_artiste VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date_creation DATE NOT NULL, reference VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, prix DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, id_commande INT NOT NULL, article VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX fffff_key (id_commande), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE post (id INT NOT NULL, titre VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, contenu VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, fullName VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, mdp VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, tel VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, image VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE categorie ADD CONSTRAINT ffff_key FOREIGN KEY (id_oeuvre) REFERENCES oeuvre (id)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT fff_key FOREIGN KEY (id_post) REFERENCES post (id)');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT fk_key FOREIGN KEY (id_musee) REFERENCES musee (id)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT fffff_key FOREIGN KEY (id_commande) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE adresse CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE idcommande idCommande INT NOT NULL, CHANGE adresse adresse VARCHAR(50) NOT NULL, CHANGE codepostal codePostal INT NOT NULL');
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY FK_A60C9F1FD3E663E4');
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY FK_A60C9F1FD3E663E4');
        $this->addSql('ALTER TABLE livraison CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE datedelivraison dateDeLivraison DATE NOT NULL, CHANGE statut statut VARCHAR(50) NOT NULL, CHANGE depot depot VARCHAR(50) NOT NULL, CHANGE idAdresse idAdresse INT NOT NULL');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT ffk FOREIGN KEY (idAdresse) REFERENCES adresse (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_a60c9f1fd3e663e4 ON livraison');
        $this->addSql('CREATE INDEX ffk ON livraison (idAdresse)');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT FK_A60C9F1FD3E663E4 FOREIGN KEY (idAdresse) REFERENCES adresse (id)');
    }
}
