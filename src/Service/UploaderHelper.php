<?php


namespace App\Service;


use Gedmo\Sluggable\Util\Urlizer;
use League\Flysystem\FilesystemInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Asset\Context\RequestStackContext;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use League\Flysystem\FileNotFoundException;

class UploaderHelper
{
    const ARTICLE_IMAGE = 'articles';
    const ARTICLE_REFERENCE = 'article_reference';
    const TICKET_IMAGE='tickets';
    const PVCE_PDF='pvcses';
    const PARTNER_IMAGE='partners';

    /**
     * @var RequestStackContext
     */
    private $requestStackContext;
    /**
     * @var FilesystemInterface
     */
    private $filesystem;
    /**
     * @var LoggerInterface
     */
    private $logger;

    private $publicAssetBaseUrl;
    /**
     * @var FilesystemInterface
     */
    private $privateFilesystem;


    public function __construct(FilesystemInterface $publicUploadsFilesystem, RequestStackContext $requestStackContext,LoggerInterface $logger,string $uploadedAssetsBaseUrl,FilesystemInterface $privateUploadsFilesystem)
    {

        $this->publicAssetBaseUrl = $uploadedAssetsBaseUrl;
        $this->requestStackContext = $requestStackContext;
        $this->filesystem = $publicUploadsFilesystem;
        $this->logger = $logger;
        $this->privateFilesystem= $privateUploadsFilesystem;
    }

    public function uploadArticleImage(File $file,?string $existingFilename):string
    {

        $newFilename=$this->uploadFile($file,self::ARTICLE_IMAGE,true);
        if ($existingFilename) {
            try {
                $result = $this->filesystem->delete(self::ARTICLE_IMAGE.'/'.$existingFilename);
                if ($result === false) {
                    throw new \Exception(sprintf('Could not delete old uploaded file "%s"', $existingFilename));
                }
            } catch (FileNotFoundException $e) {
                $this->logger->alert(sprintf('Old uploaded file "%s" was missing when trying to delete', $existingFilename));
            }
        }

        return  $newFilename;
    }
    public function getPublicPath(string $path): string
    {
        return $this->requestStackContext
                ->getBasePath().$this->publicAssetBaseUrl.'/'.$path;
    }

    private function uploadFile(File $file, string $directory, bool $isPublic)
    {
        if ($file instanceof UploadedFile) {
            $originalFilename = $file->getClientOriginalName();
        } else {
            $originalFilename = $file->getFilename();
        }
        $newFilename = Urlizer::urlize(pathinfo($originalFilename, PATHINFO_FILENAME)).'-'.uniqid().'.'.$file->guessExtension();

        $filesystem = $isPublic ? $this->filesystem : $this->privateFilesystem;

        $stream = fopen($file->getPathname(), 'r');
        $result = $filesystem->writeStream(
            $directory.'/'.$newFilename,
            $stream
        );
        if ($result === false) {
            throw new \Exception(sprintf('Could not write uploaded file "%s"', $newFilename));
        }
        if (is_resource($stream)) {
            fclose($stream);
        }
        return $newFilename;
    }
    public function uploadArticleReference(File $file): string
    {
        return $this->uploadFile($file, self::ARTICLE_REFERENCE, false);
    }

    public function readStream(string $path, bool $isPublic)
    {
        $filesystem = $isPublic ? $this->filesystem : $this->privateFilesystem;
        $resource = $filesystem->readStream($path);
        if ($resource === false) {
            throw new \Exception(sprintf('Erreur d\'ouverture du fichier "%s"', $path));
        }
        return $resource;
    }
    public function deleteFile(string $path, bool $isPublic)
    {
        $filesystem = $isPublic ? $this->filesystem : $this->privateFilesystem;
        $result= $filesystem->delete($path);
        if ($result === false) {
            throw new \Exception(sprintf('Erreur de suppression de fichier "%s"', $path));
        }
    }
    public function uploadTicketImage(File $file,?string $existingFilename):string
    {

        $newFilename=$this->uploadFile($file,self::TICKET_IMAGE,true);
        if ($existingFilename) {
            try {
                $result = $this->filesystem->delete(self::TICKET_IMAGE.'/'.$existingFilename);
                if ($result === false) {
                    throw new \Exception(sprintf('Impossible de supprimer le fichier "%s"', $existingFilename));
                }
            } catch (FileNotFoundException $e) {
                $this->logger->alert(sprintf('Old uploaded file "%s" was missing when trying to delete', $existingFilename));
            }
        }

        return  $newFilename;
    }
    public function uploadPvcePdf(File $file,?string $existingFilename):string
    {

        $newFilename=$this->uploadFile($file,self::PVCE_PDF,true);
        if ($existingFilename) {
            try {
                $result = $this->filesystem->delete(self::PVCE_PDF.'/'.$existingFilename);
                if ($result === false) {
                    throw new \Exception(sprintf('Impossible de supprimer le fichier "%s"', $existingFilename));
                }
            } catch (FileNotFoundException $e) {
                $this->logger->alert(sprintf('Old uploaded file "%s" was missing when trying to delete', $existingFilename));
            }
        }

        return  $newFilename;
    }
    public function uploadPartnerImage(File $file,?string $existingFilename):string
    {

        $newFilename=$this->uploadFile($file,self::PARTNER_IMAGE,true);
        if ($existingFilename) {
            try {
                $result = $this->filesystem->delete(self::PARTNER_IMAGE.'/'.$existingFilename);
                if ($result === false) {
                    throw new \Exception(sprintf('Impossible de supprimer le fichier "%s"', $existingFilename));
                }
            } catch (FileNotFoundException $e) {
                $this->logger->alert(sprintf('Old uploaded file "%s" was missing when trying to delete', $existingFilename));
            }
        }

        return  $newFilename;
    }
}