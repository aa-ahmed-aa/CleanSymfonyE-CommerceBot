<?php
namespace App\Helpers;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUpload
{
    /**
     * @param $form
     * @param $item
     */
    public function uploadImage($form, $item)
    {
        /** @var UploadedFile $image */
        $image = $form['image']->getData();

        if ($image) {
            $upload_directory = 'uploads';
            $newFilename = md5(uniqid()) .'.'.$image->guessExtension();

            try {
                $image->move(
                    $upload_directory,
                    $newFilename
                );
            } catch (FileException $e) {
                die($e);
            }

            $item->setImage($newFilename);
        }
    }
}