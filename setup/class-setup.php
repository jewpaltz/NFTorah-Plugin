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

    public static function update_004_record_which_letter() {
		global $wpdb, $logger;
        $DB_VERSION = 1.34;

        if((float)get_option('NFTorah_db_version') >= $DB_VERSION){
            do_action( 'qm/debug', 'NFTorah DB structure Up To Date!' );
            return;
        }

        $sql =  "ALTER TABLE {$wpdb->prefix}torah_letter "
            .   " ADD `book` VARCHAR(20) NULL, "
            .   " ADD `chapter` INT NULL, "
            .   " ADD `verse` INT NULL, "
            .   " ADD `letter` INT NULL, "
            .   " ADD `hebrew_letter` VARCHAR(20) NULL, "
            .   " ADD `parshah` VARCHAR(50) NULL;";
        
        
        $logger->debug('NFTorah::setup: Executing', [ $sql ]);
        $wpdb->query($sql);

        if($wpdb->last_error){
            $logger->debug('NFTorah::setup: Error', [ $wpdb->last_error ]);
            do_action( 'qm/debug', $wpdb->last_error );
            return;
        }

        update_option( "NFTorah_db_version", $DB_VERSION );
    }

    public static function update_006_create_pesukim_table() {
        $DB_VERSION = 1.36;
            global $wpdb;

            $charset_collate = $wpdb->get_charset_collate();

            $sql = "
            CREATE TABLE  {$wpdb->prefix}torah_pesukim (
                `id` INT NOT NULL AUTO_INCREMENT ,
                `book` VARCHAR(20) NOT NULL ,
                `chapter` INT NOT NULL ,
                `verse` INT NOT NULL ,
                 `length` INT NOT NULL ,
                 `taken` INT NOT NULL ,
                 `text` VARCHAR(500) NOT NULL ,
                 `parshah` VARCHAR(20) NOT NULL ,
                 `parshah_heb` VARCHAR(20) NOT NULL ,
                 PRIMARY KEY (`id`),
                 INDEX `ix_pesukim_natural` (`book`, `chapter`, `verse` )
                 ) $charset_collate;
            ";

        self::do_sql_update($sql, $DB_VERSION, 'update_006_create_pesukim_table');

    }

    public static function update_007_import_pesukim_data() {
        $DB_VERSION = 1.37;

        self::do_update(function(){
            global $wpdb, $logger;

            $sql = file_get_contents( __DIR__ . '/pesukim.sql');
            $header = "INSERT INTO `{$wpdb->prefix}torah_pesukim` ( `id`, `book`, `chapter`, `verse`, `length`, `taken`, `text`, `parshah`, `parshah_heb`) VALUES ";
            //echo $sql;
            $wpdb->query($header . $sql);

            if($wpdb->last_error){
                $logger->debug('NFTorah::setup: Error', [ $wpdb->last_error ]);
                do_action( 'qm/debug', $wpdb->last_error );
                return;
            }
        }, $DB_VERSION, 'update_007_import_pesukim_data');

    }

    public static function do_sql_update($sql, $db_version, $name) {

        self::do_update(function() use ($sql){
            global $wpdb, $logger;

            if(strlen($sql) < 1000){
                $logger->debug('NFTorah::setup: Executing ' . $sql);
            }
            
            $wpdb->query($sql);

            if($wpdb->last_error){
                $logger->debug('NFTorah::setup: Error', [ $wpdb->last_error ]);
                do_action( 'qm/debug', $wpdb->last_error );
                return;
            }
        }, $db_version, $name);
    }

    public static function do_update($fn, $db_version, $name) {
		global $logger;

        if((float)get_option('NFTorah_db_version') >= $db_version){
            do_action( 'qm/debug', 'NFTorah DB structure Up To Date!' );
            return;
        }
        $logger->debug('NFTorah::setup: Executing ' . $name);

        $fn();

        update_option( "NFTorah_db_version", $db_version );
    }
}