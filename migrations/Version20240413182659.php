<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240413182659 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE station ADD station_id INT AUTO_INCREMENT NOT NULL, DROP Address, CHANGE Name station_name VARCHAR(255) NOT NULL, CHANGE id station_order INT NOT NULL, ADD PRIMARY KEY (station_id)');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs (ID)');
        $this->addSql('CREATE INDEX IDX_97A0ADA3FB88E14F ON ticket (utilisateur_id)');
        $this->addSql('ALTER TABLE tickets ADD wallet_id INT DEFAULT NULL, ADD from_destination VARCHAR(255) NOT NULL, DROP temp_d_arriver, DROP temps_depart, DROP duree_trajet, CHANGE ticket_id ticket_id INT AUTO_INCREMENT NOT NULL, ADD PRIMARY KEY (ticket_id)');
        $this->addSql('ALTER TABLE tickets ADD CONSTRAINT FK_54469DF4FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE tickets ADD CONSTRAINT FK_54469DF4712520F3 FOREIGN KEY (wallet_id) REFERENCES wallet (walletId)');
        $this->addSql('CREATE INDEX IDX_54469DF4FB88E14F ON tickets (utilisateur_id)');
        $this->addSql('CREATE INDEX IDX_54469DF4712520F3 ON tickets (wallet_id)');
        $this->addSql('ALTER TABLE utilisateurs ADD mot_de_passe VARCHAR(255) NOT NULL, ADD role VARCHAR(255) NOT NULL, DROP Mot de passe, DROP Rôle, DROP NumTel, CHANGE isValid is_valid TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE station MODIFY station_id INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON station');
        $this->addSql('ALTER TABLE station ADD Address VARCHAR(255) DEFAULT NULL, DROP station_id, CHANGE station_name Name VARCHAR(255) NOT NULL, CHANGE station_order id INT NOT NULL');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3FB88E14F');
        $this->addSql('DROP INDEX IDX_97A0ADA3FB88E14F ON ticket');
        $this->addSql('ALTER TABLE tickets MODIFY ticket_id INT NOT NULL');
        $this->addSql('ALTER TABLE tickets DROP FOREIGN KEY FK_54469DF4FB88E14F');
        $this->addSql('ALTER TABLE tickets DROP FOREIGN KEY FK_54469DF4712520F3');
        $this->addSql('DROP INDEX IDX_54469DF4FB88E14F ON tickets');
        $this->addSql('DROP INDEX IDX_54469DF4712520F3 ON tickets');
        $this->addSql('DROP INDEX `primary` ON tickets');
        $this->addSql('ALTER TABLE tickets ADD temp_d_arriver TIME DEFAULT NULL, ADD temps_depart TIME NOT NULL, ADD duree_trajet TIME NOT NULL, DROP wallet_id, DROP from_destination, CHANGE ticket_id ticket_id INT NOT NULL');
        $this->addSql('ALTER TABLE utilisateurs ADD Mot de passe VARCHAR(255) NOT NULL, ADD Rôle VARCHAR(255) NOT NULL, ADD NumTel INT DEFAULT NULL, DROP mot_de_passe, DROP role, CHANGE is_valid isValid TINYINT(1) DEFAULT NULL');
    }
}
