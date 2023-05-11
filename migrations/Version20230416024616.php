<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230416024616 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annee (id INT AUTO_INCREMENT NOT NULL, optionn_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, INDEX IDX_DE92C5CF98A88AB3 (optionn_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE correction (id INT AUTO_INCREMENT NOT NULL, module_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, fichier VARCHAR(255) NOT NULL, taille_fichier VARCHAR(255) NOT NULL, remarque LONGTEXT DEFAULT NULL, fichier_nom VARCHAR(255) DEFAULT NULL, date_creation DATETIME NOT NULL, INDEX IDX_A29DA1B8AFC2B591 (module_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE departement (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE examen (id INT AUTO_INCREMENT NOT NULL, module_id INT NOT NULL, nom VARCHAR(255) NOT NULL, fichier VARCHAR(255) NOT NULL, taille_fichier VARCHAR(255) NOT NULL, remarque LONGTEXT DEFAULT NULL, date_creation DATETIME NOT NULL, fichier_nom VARCHAR(255) NOT NULL, INDEX IDX_514C8FECAFC2B591 (module_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE filiere (id INT AUTO_INCREMENT NOT NULL, departement_id INT NOT NULL, nom VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, INDEX IDX_2ED05D9ECCF9E01E (departement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE licence (id INT AUTO_INCREMENT NOT NULL, filiere_id INT NOT NULL, nom VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, INDEX IDX_1DAAE648180AA129 (filiere_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, nom_prenom VARCHAR(255) NOT NULL, numero VARCHAR(255) NOT NULL, sujet VARCHAR(255) DEFAULT NULL, message LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE module (id INT AUTO_INCREMENT NOT NULL, annee_id INT NOT NULL, nom VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, INDEX IDX_C242628543EC5F0 (annee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `option` (id INT AUTO_INCREMENT NOT NULL, licence_id INT NOT NULL, nom VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, INDEX IDX_5A8600B026EF07C9 (licence_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE proposition_correction (id INT AUTO_INCREMENT NOT NULL, module_id INT DEFAULT NULL, nom_prenom VARCHAR(255) NOT NULL, numero VARCHAR(255) NOT NULL, correction VARCHAR(255) NOT NULL, correction_nom VARCHAR(255) NOT NULL, nom_session VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, description VARCHAR(255) NOT NULL, INDEX IDX_8AE9F576AFC2B591 (module_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE proposition_examen (id INT AUTO_INCREMENT NOT NULL, module_id INT DEFAULT NULL, nom_prenom VARCHAR(255) NOT NULL, numero VARCHAR(255) NOT NULL, examen VARCHAR(255) NOT NULL, examen_nom VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, nom_session VARCHAR(255) NOT NULL, INDEX IDX_D7210D8AAFC2B591 (module_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, numero VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, nom_prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, ine VARCHAR(255) DEFAULT NULL, mot_secret VARCHAR(255) NOT NULL, photo VARCHAR(255) DEFAULT NULL, remerciement TINYINT(1) NOT NULL, date_creation DATETIME DEFAULT NULL, photo_nom VARCHAR(255) DEFAULT NULL, sauvegarde_du_mot_de_passe VARCHAR(255) NOT NULL, filiere VARCHAR(255) NOT NULL, specialite VARCHAR(255) DEFAULT NULL, annee VARCHAR(255) NOT NULL, description_remerciement LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649F55AE19E (numero), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE annee ADD CONSTRAINT FK_DE92C5CF98A88AB3 FOREIGN KEY (optionn_id) REFERENCES `option` (id)');
        $this->addSql('ALTER TABLE correction ADD CONSTRAINT FK_A29DA1B8AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
        $this->addSql('ALTER TABLE examen ADD CONSTRAINT FK_514C8FECAFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
        $this->addSql('ALTER TABLE filiere ADD CONSTRAINT FK_2ED05D9ECCF9E01E FOREIGN KEY (departement_id) REFERENCES departement (id)');
        $this->addSql('ALTER TABLE licence ADD CONSTRAINT FK_1DAAE648180AA129 FOREIGN KEY (filiere_id) REFERENCES filiere (id)');
        $this->addSql('ALTER TABLE module ADD CONSTRAINT FK_C242628543EC5F0 FOREIGN KEY (annee_id) REFERENCES annee (id)');
        $this->addSql('ALTER TABLE `option` ADD CONSTRAINT FK_5A8600B026EF07C9 FOREIGN KEY (licence_id) REFERENCES licence (id)');
        $this->addSql('ALTER TABLE proposition_correction ADD CONSTRAINT FK_8AE9F576AFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
        $this->addSql('ALTER TABLE proposition_examen ADD CONSTRAINT FK_D7210D8AAFC2B591 FOREIGN KEY (module_id) REFERENCES module (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annee DROP FOREIGN KEY FK_DE92C5CF98A88AB3');
        $this->addSql('ALTER TABLE correction DROP FOREIGN KEY FK_A29DA1B8AFC2B591');
        $this->addSql('ALTER TABLE examen DROP FOREIGN KEY FK_514C8FECAFC2B591');
        $this->addSql('ALTER TABLE filiere DROP FOREIGN KEY FK_2ED05D9ECCF9E01E');
        $this->addSql('ALTER TABLE licence DROP FOREIGN KEY FK_1DAAE648180AA129');
        $this->addSql('ALTER TABLE module DROP FOREIGN KEY FK_C242628543EC5F0');
        $this->addSql('ALTER TABLE `option` DROP FOREIGN KEY FK_5A8600B026EF07C9');
        $this->addSql('ALTER TABLE proposition_correction DROP FOREIGN KEY FK_8AE9F576AFC2B591');
        $this->addSql('ALTER TABLE proposition_examen DROP FOREIGN KEY FK_D7210D8AAFC2B591');
        $this->addSql('DROP TABLE annee');
        $this->addSql('DROP TABLE correction');
        $this->addSql('DROP TABLE departement');
        $this->addSql('DROP TABLE examen');
        $this->addSql('DROP TABLE filiere');
        $this->addSql('DROP TABLE licence');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE module');
        $this->addSql('DROP TABLE `option`');
        $this->addSql('DROP TABLE proposition_correction');
        $this->addSql('DROP TABLE proposition_examen');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
