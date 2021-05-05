<?php
/*
    B"H
*/
namespace NFTorah;

class Purchases{

    public static function GetList(){
        global $wpdb;

        $data = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}torah_purchase"), 'ARRAY_A');
        return $data;
    }
    public static function Create($purchase, $letters, $nonce){
        global $wpdb;
    
        $errors = [];
        $results = [];

        // Check that the nonce was set and valid
        if( !wp_verify_nonce($nonce, 'wp_rest') ) {
            throw new Requests_Exception_HTTP_403( 'Did not save because your form seemed to be invalid. Sorry');
        }
    
        $wpdb->insert(
            $wpdb->prefix . 'torah_purchase',
            array(
                'firstName'     => $purchase['firstName'],
                'lastName'      => $purchase['lastName'],
                'email'         => $purchase['email'],
                'phone'         => $purchase['phone'],
                'paid'          => $purchase['paid'],
                'cardNumber'    => is_numeric( $purchase['cardNumber'] ) ? substr($purchase['cardNumber'], -4) : $purchase['cardNumber'],
                'expirationDate'=> $purchase['expirationDate'],
                'cvv'           => $purchase['cvv'],
                'created_at'    => current_time( 'mysql', true ),
            )
        );
        if($wpdb->last_error){
            $errors[] = $wpdb->last_error;
        }
        $results[] = $wpdb->last_result;
        $purchase_id = $wpdb->insert_id;
    

        foreach ($letters as $key => $letter) {
            $wpdb->insert(
                $wpdb->prefix . 'torah_letter',
                array(
                    'purchase_id'   => $purchase_id,
                    'hebrewName'    => $letter['hebrewName'],
                    'secularName'   => $letter['secularName'],
                    'lastName'      => $letter['lastName'],
                    'mothersName'   => $letter['mothersName'],
                    'created_at'    => current_time( 'mysql', true ),
                )
            );
            if($wpdb->last_error){
                $errors[] = $wpdb->last_error;
            }
            $results[] = $wpdb->last_result;
            $letters[$key]["id"] = $wpdb->insert_id;
        }
    
        return [
            "msg" => count($errors) == 0 ? "Saved successfully! :)" : "There were errors saving your purchase",
            "purchase_id" => $purchase_id,
            "letters" => $letters,
            "errors" => $errors,
            "results" => $results
        ];
    }

}