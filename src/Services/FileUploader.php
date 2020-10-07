<?php

namespace App\Services;

use League\Flysystem\FilesystemInterface;
use Symfony\Component\Asset\Context\RequestStackContext;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Exception;

class FileUploader
{
    private string              $uploadedAssetsBaseUrl;
    private FilesystemInterface $fileSystem;
    private RequestStackContext $requestStackContext;

    /**
     * FileUploader constructor.
     *
     * @param FilesystemInterface $privateUploadsFilesystem
     * @param RequestStackContext $requestStackContext
     * @param string              $uploadedFilesBaseUrl
     */
    public function __construct(FilesystemInterface $privateUploadsFilesystem, RequestStackContext $requestStackContext, string $uploadedFilesBaseUrl)
    {
        $this->uploadedAssetsBaseUrl = $uploadedFilesBaseUrl;
        $this->fileSystem            = $privateUploadsFilesystem;
        $this->requestStackContext   = $requestStackContext;
    }

    /**
     * @param string $prefix
     * @param File   $file
     * @param bool   $useOriginalFileName - set true by Fixtures to retain original filename all others false
     *
     * @return string
     *
     * @throws Exception
     */
    public function uploadFile(string $prefix, File $file, bool $useOriginalFileName = false): string
    {
        $originalFilename = ($file instanceof UploadedFile) ? $file->getClientOriginalName() : $file->getFilename();
        $newFilename      = $useOriginalFileName ?
            $originalFilename :
            uniqid().'.'.$file->guessExtension();

        $path = $this->getRelativePath($prefix, $newFilename);

        // If we're using original file do not overwrite if it exists
        if (!$useOriginalFileName || !$this->has($prefix, $originalFilename)) {
            $stream = fopen($file->getPathname(), 'r');
            $result = $this->fileSystem->writeStream(
                $path,
                $stream
            );

            if (false === $result) {
                throw new Exception(sprintf('Could not upload %s', $originalFilename));
            }

            if (is_resource($stream)) {
                fclose($stream);
            }
        }

        return $path;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function getPublicPath(string $path): string
    {
        return $this->requestStackContext->getBasePath().$this->uploadedAssetsBaseUrl.'/'.$path;
    }

    /**
     * @param string $prefix
     * @param string $filename
     *
     * @return bool
     */
    public function has(string $prefix, string $filename): bool
    {
        return $this->fileSystem->has($this->getRelativePath($prefix, $filename));
    }

    /**
     * @param string $prefix
     * @param string $filename
     *
     * @return string
     */
    protected function getRelativePath(string $prefix, string $filename): string
    {
        return $prefix.'/'.$filename;
    }
}