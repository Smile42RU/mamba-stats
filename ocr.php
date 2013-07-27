<?php
set_time_limit(0);

ini_set("display_errors",1);
error_reporting(E_ALL);

require_once './tesseract/tesseract-ocr-for-php/tesseract_ocr/tesseract_ocr.php';

$files = scandir(__DIR__ . DIRECTORY_SEPARATOR . 'images');
$files = array_slice($files, 2, sizeof($files));

$dbh = new PDO('mysql:host=localhost;port=3306;dbname=mamba', 'root', '', array( PDO::ATTR_PERSISTENT => false));

foreach($files as $file){
   if(is_file(__DIR__ . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . $file)){

        // Обрезаем картинку и кладем в tmp
        $image = new Imagick(__DIR__ . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . $file);
        $image->cropimage('32', '21', '24', '56');

        file_put_contents(__DIR__ . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . $file, $image);

        $tesseract = new TesseractOCR();
        $output = $tesseract->recognize(__DIR__ . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . $file, range(0,9));

        if($output !== false){

            $basename = explode('.', $file);
            $basename = $basename[0];

            $stmt = $dbh->prepare('INSERT INTO results SET `id` = '.$basename.', `value` = '.$output);
            $stmt->execute();

            // Удаляем обрезанную картинку
            unlink(__DIR__ . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . $file);
        }
   }
}