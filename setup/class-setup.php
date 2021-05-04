<?php
/*  B"H
*/

class NFTorah_setup {
	public static function update_001_create_original_tables() {
		global $wpdb;
        $DB_VERSION = 1.2;

        if((float)get_option('NFTorah_db_version') >= $DB_VERSION){
            do_action( 'qm/debug', 'NFTorah DB structure Up To Date!' );
            return;
        }

        $charset_collate = $wpdb->get_charset_collate();
        
        // add "if not exists" if not executing through dbDelta
        $sql = "
        CREATE TABLE  {$wpdb->prefix}torah_purchase (
            id  bigint(20) UNSIGNED NOT NULL auto_increment,
            created_at datetime NOT NULL,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP
                        ON UPDATE CURRENT_TIMESTAMP,
            firstName varchar(200) NOT NULL,
            lastName varchar(200) NOT NULL,
            email varchar(200) NOT NULL,
            phone varchar(200) NOT NULL,
            paid DECIMAL(13,2) NOT NULL,
            cardNumber varchar(200) NOT NULL,
            expirationDate varchar(200) NOT NULL,
            cvv varchar(5) NOT NULL,
        
            cryptoNetwork varchar(50)  NOT NULL DEFAULT 'ETH-test',
            publicAddress  varchar(200) NULL,
            privateKey varbinary(500) NULL,
            seedPhrase varchar(500) NULL,
        
            PRIMARY KEY  (id)
        ) $charset_collate;
        
        CREATE TABLE {$wpdb->prefix}torah_letter (
            id  bigint(20) UNSIGNED NOT NULL auto_increment,
            created_at datetime NOT NULL,
            updated_at datetime DEFAULT CURRENT_TIMESTAMP
                        ON UPDATE CURRENT_TIMESTAMP,
            purchase_id bigint(20) UNSIGNED NOT NULL,
            hebrewName varchar(200) NULL,
            secularName varchar(200) NULL,
            lastName varchar(200) NOT NULL,
            mothersName varchar(200) NOT NULL,
        
            PRIMARY KEY  (id),
            
            index idx_purchase (purchase_id),
            FOREIGN KEY (purchase_id)
                REFERENCES {$wpdb->prefix}torah_purchase(id)
                ON DELETE CASCADE
        ) $charset_collate;
        ";

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );


        $dbDelta_results = dbDelta( $sql );
        do_action( 'qm/debug', $dbDelta_results );
        do_action( 'qm/debug', $wpdb->last_error );

        update_option( "NFTorah_db_version", $DB_VERSION );
    }

    public static function update_003_allow_exp_and_cvv_null() {
		global $wpdb;
        $DB_VERSION = 1.31;

        if((float)get_option('NFTorah_db_version') >= $DB_VERSION){
            do_action( 'qm/debug', 'NFTorah DB structure Up To Date!' );
            return;
        }

        $sql =  "ALTER TABLE {$wpdb->prefix}torah_purchase "
            .   " MODIFY  expirationDate varchar(200) NULL, "
            .   " MODIFY  cvv varchar(5) NULL ";

        $wpdb->query($sql);

        do_action( 'qm/debug', $wpdb->last_error );

        update_option( "NFTorah_db_version", $DB_VERSION );
    }
}