<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240414204218 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE capteurs CHANGE nom nom VARCHAR(255) NOT NULL, CHANGE type type VARCHAR(255) NOT NULL, CHANGE date_installation date_installation VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE donneeshistoriques DROP FOREIGN KEY donneeshistoriques_ibfk_1');
        $this->addSql('ALTER TABLE donneeshistoriques DROP FOREIGN KEY donneeshistoriques_ibfk_1');
        $this->addSql('ALTER TABLE donneeshistoriques CHANGE timestamp timestamp VARCHAR(255) NOT NULL, CHANGE niveau_embouteillage niveau_embouteillage INT NOT NULL, CHANGE alerte alerte VARCHAR(255) NOT NULL, CHANGE conditions_meteo conditions_meteo VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE donneeshistoriques ADD CONSTRAINT FK_193F261685D7BFE5 FOREIGN KEY (id_capteur) REFERENCES capteurs (id)');
        $this->addSql('DROP INDEX donneeshistoriques_ibfk_1 ON donneeshistoriques');
        $this->addSql('CREATE INDEX IDX_193F261685D7BFE5 ON donneeshistoriques (id_capteur)');
        $this->addSql('ALTER TABLE donneeshistoriques ADD CONSTRAINT donneeshistoriques_ibfk_1 FOREIGN KEY (id_capteur) REFERENCES capteurs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tickets ADD wallet_id INT DEFAULT NULL, ADD from_destination VARCHAR(255) NOT NULL, DROP temp_d_arriver, DROP temps_depart, DROP duree_trajet, CHANGE ticket_id ticket_id INT AUTO_INCREMENT NOT NULL, ADD PRIMARY KEY (ticket_id)');
        $this->addSql('ALTER TABLE tickets ADD CONSTRAINT FK_54469DF4FB88E14F FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs (id)');
        $this->addSql('ALTER TABLE tickets ADD CONSTRAINT FK_54469DF4712520F3 FOREIGN KEY (wallet_id) REFERENCES wallet (walletId)');
        $this->addSql('CREATE INDEX IDX_54469DF4FB88E14F ON tickets (utilisateur_id)');
        $this->addSql('CREATE INDEX IDX_54469DF4712520F3 ON tickets (wallet_id)');
        $this->addSql('ALTER TABLE utilisateurs DROP NumTel');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE capteurs CHANGE nom nom VARCHAR(100) NOT NULL, CHANGE type type VARCHAR(100) NOT NULL, CHANGE date_installation date_installation VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE donneeshistoriques DROP FOREIGN KEY FK_193F261685D7BFE5');
        $this->addSql('ALTER TABLE donneeshistoriques DROP FOREIGN KEY FK_193F261685D7BFE5');
        $this->addSql('ALTER TABLE donneeshistoriques CHANGE timestamp timestamp DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CHANGE niveau_embouteillage niveau_embouteillage INT DEFAULT NULL, CHANGE alerte alerte VARCHAR(255) DEFAULT NULL, CHANGE conditions_meteo conditions_meteo VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE donneeshistoriques ADD CONSTRAINT donneeshistoriques_ibfk_1 FOREIGN KEY (id_capteur) REFERENCES capteurs (id) ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_193f261685d7bfe5 ON donneeshistoriques');
        $this->addSql('CREATE INDEX donneeshistoriques_ibfk_1 ON donneeshistoriques (id_capteur)');
        $this->addSql('ALTER TABLE donneeshistoriques ADD CONSTRAINT FK_193F261685D7BFE5 FOREIGN KEY (id_capteur) REFERENCES capteurs (id)');
        $this->addSql('ALTER TABLE tickets MODIFY ticket_id INT NOT NULL');
        $this->addSql('ALTER TABLE tickets DROP FOREIGN KEY FK_54469DF4FB88E14F');
        $this->addSql('ALTER TABLE tickets DROP FOREIGN KEY FK_54469DF4712520F3');
        $this->addSql('DROP INDEX IDX_54469DF4FB88E14F ON tickets');
        $this->addSql('DROP INDEX IDX_54469DF4712520F3 ON tickets');
        $this->addSql('DROP INDEX `primary` ON tickets');
        $this->addSql('ALTER TABLE tickets ADD temp_d_arriver TIME DEFAULT NULL, ADD temps_depart TIME NOT NULL, ADD duree_trajet TIME NOT NULL, DROP wallet_id, DROP from_destination, CHANGE ticket_id ticket_id INT NOT NULL');
        $this->addSql('ALTER TABLE utilisateurs ADD NumTel INT DEFAULT NULL');
    }
}
