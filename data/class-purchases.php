<?php
/*
    B"H
*/
namespace NFTorah;

class Purchases{

    public static function GetList(){
        global $wpdb;

        $data = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}torah_purchase ORDER BY id"), 'ARRAY_A');
        $letters = $wpdb->get_results($wpdb->prepare("SELECT * FROM {$wpdb->prefix}torah_letter  ORDER BY purchase_id"), 'ARRAY_A');
        $l_count = count($letters);
        $l_index = 0;
        foreach ($data as $key => $row) {
            $data[$key]['letters'] = [];
        //print_r($row);
            while ($l_index < $l_count && $letters[$l_index]['purchase_id'] == $row['id']) {
                $data[$key]['letters'][] = $letters[$l_index];
                $l_index++;
            }
        }
        return $data;
    }

    public static function GetLetter($id){
        global $wpdb;

        $letter = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}torah_letter WHERE purchase_id=%d", $id), 'ARRAY_A');
        return $letter;
    }

    public static function Create($purchase, $letters, $nonce){
        global $wpdb, $logger;
    
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
                'publicAddress' => $purchase['publicAddress'],
                'created_at'    => current_time( 'mysql', true ),
            )
        );
        if($wpdb->last_error){
            $errors[] = $wpdb->last_error;
        }
        $results[] = $wpdb->last_result;
        $purchase_id = $wpdb->insert_id;
    

        foreach ($letters as $key => $letter) {
            //TODO: find actual letter and record details about it. letter, parshah, etc.
            $sql = "
            SELECT * FROM {$wpdb->prefix}torah_pesukim WHERE taken < length ORDER BY id LIMIT 1
            ";
            $posuk = $wpdb->get_row($wpdb->prepare($sql), 'ARRAY_A');

            $wpdb->query( "UPDATE {$wpdb->prefix}torah_pesukim SET taken=taken+1 WHERE id=$posuk[id]" );

            $taken = $posuk['taken'];
            $posukText = $posuk['text'];

            $newLetter = [
                    'purchase_id'   => $purchase_id,
                    'hebrewName'    => $letter['hebrewName'],
                    'secularName'   => $letter['secularName'],
                    'lastName'      => $letter['lastName'],
                    'mothersName'   => $letter['mothersName'],
                    
                    'book'          => $posuk['book'],
                    'chapter'       => $posuk['chapter'],
                    'verse'         => $posuk['verse'],
                    'letter'        => $taken,
                    'hebrew_Letter'  => mb_substr($posukText, $taken, 1),
                    'parshah'       => $posuk['parshah_heb'],
                    
                    'created_at'    => current_time( 'mysql', true ),
            ];
            
            $wpdb->insert( $wpdb->prefix . 'torah_letter', $newLetter);

            if($wpdb->last_error){
                $errors[] = $wpdb->last_error;
                $logger->error($wpdb->last_error);
            }
            $results[] = $wpdb->last_result;
            $newLetter["id"] = $wpdb->insert_id;

            $newLetter["certificateUrl"] = self::CreateCertificate($purchase, $newLetter);  // Saves an image and returns the url
        
            $letters[$key] = $newLetter;

            //$logger->debug('NFTorah::CreatePurchase: ClientLetter',  $letters[$key]);
            //$logger->debug('NFTorah::CreatePurchase: DBLetter',  $newLetter);
            
        }

        return [
            "msg" => count($errors) == 0 ? "Saved successfully! :)" : "There were errors saving your purchase",
            "purchase_id" => $purchase_id,
            "letters" => $letters,
            "errors" => $errors,
            "results" => $results
        ];
    }
    public static function CreateCertificate($purchase, $letter){

            $upload_dir   = wp_upload_dir();
            $certificate_dir = "{$upload_dir['basedir']}/torah-certificates";
            $certificate_url = "{$upload_dir['baseurl']}/torah-certificates";
            if ( ! file_exists( $certificate_dir ) ) {
                wp_mkdir_p( $certificate_dir );
            }
            
            $img = imagecreatefrompng(__DIR__ . '/../assets/blank-certificate.png');

            $black = imagecolorallocate($img, 0, 0, 0);
            $font = __DIR__ . "/../assets/Courgette-Regular.ttf"; 
            $hebrewFont = __DIR__ . "/../assets/DavidLibre-Bold.ttf"; 

            $parshah_name = self::mb_strrev($letter['parshah']);
            $txt = hebrev($letter['hebrew_Letter'] . ' in the Parshah ' . $parshah_name);
            $centerX = self::centerTextX( $hebrewFont, 48, $txt, imagesx($img) );
            imagettftext($img, 48, 0, $centerX, 550, $black, $hebrewFont, $txt);

            $txt = $letter['hebrewName'] ? $letter['hebrewName'] : 'Anonymous';
            $centerX = self::centerTextX( $font, 48, $txt, imagesx($img) );
            imagettftext($img, 48, 0, $centerX, 740, $black, $font, $txt);

            $txt = '#' . $letter['id'];
            $centerX = self::centerTextX( $font, 72, $txt, imagesx($img) ); //    Centered 175 pixels off the right edge
            imagettftext($img, 72, 0, $centerX + 460, 1150, $black, $font, $txt);

            imagepng($img, "$certificate_dir/{$letter['id']}.png");

            return "$certificate_url/{$letter['id']}.png";        
    }

    public static function PayStripe($paymentMethodId, $amount, $currency = 'usd'){
        \Stripe\Stripe::setApiKey(getenv('STRIPE_SECRET_KEY'));
        $transaction = [
            "amount" => $amount * 100,
            "currency" => $currency,
            "payment_method" => $paymentMethodId,
            "confirmation_method" => "manual",
            "confirm" => true,
            "use_stripe_sdk" => true,
        ];

        //  Errors processing transaction are thrown to calling code i.e. REST controller to be returned as an error to the client.
        $intent = \Stripe\PaymentIntent::create($transaction);

        return $intent;
    }

    private static function centerTextX($font, $size, $txt, $image_width){
        $text_size = imagettfbbox($size, 0, $font, $txt);
        $text_width = max([$text_size[2], $text_size[4]]) - min([$text_size[0], $text_size[6]]);
        //$text_height = max([$text_size[5], $text_size[7]]) - min([$text_size[1], $text_size[3]]);
        return CEIL(($image_width - $text_width) / 2);
    }

    static function mb_strrev($str){
        $r = '';
        for ($i = mb_strlen($str); $i>=0; $i--) {
            $r .= mb_substr($str, $i, 1);
        }
        return $r;
    }

    public static function GetMetaData($id){
        $upload_dir   = wp_upload_dir();
        $certificate_url = "{$upload_dir['baseurl']}/torah-certificates";

        $letter = self::GetLetter($id);
        return [
            "name" => "NFTorah Letter #" . $id,
            "description" => 'This NFT represents one letter written in a physical Torah for the original owner of this token. '
                        .    'The last name of the owner and the exact letter are not kept on chain to increase the anonymity of all involved. '
                        .    'However the NFTorah authority keeps records for this letter #' . $id,
            "image" => "$certificate_url/$id.png",
            "external_link" => site_url( 'letters/' . $id),
            "attributes" => [
                [ 
                    "hebrew name" => $letter['hebrewName'],
                    "hebrew letter" => $letter['hebrew_Letter'],
                    'book' => $letter['book'],
                    'parshah' => $letter['parshah'],
                ]
            ]
        ];
    }
}