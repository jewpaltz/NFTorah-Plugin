<?php
/*
    B"H
*/
namespace NFTorah;

class Torah {

    public static function GetParshiot(){
        $filename = __DIR__ ."/torah/parshiot.csv";
        //$strJsonFileContents = file_get_contents($filename);
        //$data = json_decode($strJsonFileContents, true);

        $data = array_map('str_getcsv', file($filename));
        
        $parshiot = array_map(function($x) {
            $ref = explode(' ', $x[2]);
            $book = $ref[0];
            $ref = explode('-', $ref[1]);
            $start = explode(':', $ref[0]);
            $end = explode(':', $ref[1]);
            //if(count($start) != 2) var_dump($x); - Error missing perek
            //if(count($end) != 2) var_dump($x); - Error missing perek

            return ['eng' => $x[0], 'heb' => $x[1], 'pesukim' => $x[2], 'book' => $book, 'start' => $start, 'end' => $end];
        }, $data);
        return $parshiot;
    }

    public static function GetPesukimInsertSQL($book){
        $filename = __DIR__ ."/torah/$book.json";
        $strJsonFileContents = file_get_contents($filename);
        $data = json_decode($strJsonFileContents, true);

        $parshiot = self::GetParshiot();
        
        $pesukim = [];
        foreach ($data['text'] as $chapter_0 => $value) {
            $chapter = $chapter_0 + 1;
            foreach ($value as $verse_0 => $text) {
                $verse = $verse_0 + 1;

                $parshah = array_search_func(
                    fn($x)=>
                        $x['book'] == $book &&
                        ($x['start'][0] < $chapter || ($x['start'][0] == $chapter && $x['start'][1] <= $verse)) &&
                        ($x['end'][0] > $chapter || ($x['end'][0] == $chapter && $x['end'][1] >= $verse)),
                    $parshiot
                );


                $x = str_replace(' ', '', $text);
                $eng = str_replace('\'', '\\\'', $parshah['eng']);
                $heb = str_replace('\'', '\\\'', $parshah['heb']);
                $len = mb_strlen($x);

                $pesukim[] = "( null, '$book', '$chapter', '$verse', $len, 0, '$x', '$eng', '$heb' ) ";
            }
        }
        
        return $pesukim;
    }
    public static function GetAllPesukimInsertSQL(){
        return array_merge( self::GetPesukimInsertSQL('Genesis'),
                            self::GetPesukimInsertSQL('Exodus'),
                            self::GetPesukimInsertSQL('Leviticus'),
                            self::GetPesukimInsertSQL('Numbers'),
                            self::GetPesukimInsertSQL('Deuteronomy') );
    }
}

function array_search_func($func, array $arr)
{
    foreach ($arr as $key => $v)
        if ($func($v))
            return $v;

    return false;
}

/*
header('Content-Type: text/plain; charset=UTF-8');

$header = 'INSERT INTO `pesukim` ( `id`, `book`, `chapter`, `verse`, `length`, `taken`, `text`, `parshah`, `parshah_heb`) VALUES ';
$pesukim = Torah::GetAllPesukimInsertSQL();

//echo count($pesukim) . ' Pesukim'. PHP_EOL . PHP_EOL;
echo $header . PHP_EOL . PHP_EOL . implode(',' . PHP_EOL, $pesukim);

//var_dump(Torah::GetParshiot());

*/