<?php
declare(strict_types=1);


namespace App\Model\Service;

use App\Model\Database\EntityManager;
use App\Model\Entity\File;
use Nette\Http\FileUpload;
use Nette\Utils\Random;

class FileService extends BaseService
{

	public function __construct(
		protected string $uploadDir,
		protected string $uploadDirPart,
		protected EntityManager $em
	) {
		parent::__construct($this->em);
	}

	public function saveImage(FileUpload $fileUpload, ?string $filenamePrefix = null): string|File
	{
		if (!$fileUpload->isOk()) {
			return 'Obrázek se nepodařilo nahrát.';
		}

		$imageEntity = new File;
		$imageEntity->setFileName($this->getFileNameFromUploadedFile($fileUpload));
		$imageEntity->setPath($this->getPathForFile($imageEntity->getFileName(), $filenamePrefix));
		$imageEntity->setContentType($fileUpload->getContentType());

		if (!is_dir($this->uploadDir)) {
			@mkdir($this->uploadDir, 0777, TRUE);
		}

		$success = @file_put_contents($this->getAbsolutePath($imageEntity, $filenamePrefix), $fileUpload->getContents());

		if (!$success) {
			return 'Chyba při ukládání obrázku.';
		}

		$this->em->persist($imageEntity);
		$this->em->flush($imageEntity);

		return $imageEntity;
	}

	protected function getAbsolutePath(File $file, ?string $filenamePrefix = null): string
	{
		return rtrim($this->uploadDir, '/')
			. '/'
			. ($filenamePrefix ? ($filenamePrefix . '_') : '')
			. $file->getFileName();
	}

	protected function getPathForFile(string $fileName, ?string $filenamePrefix = null): string
	{
		return rtrim($this->uploadDirPart, '/')
			. '/'
			. ($filenamePrefix ? ($filenamePrefix . '_') : '')
			. $fileName;
	}

	protected function getFileNameFromUploadedFile(FileUpload $fileUpload): string
	{
		return Random::generate(8, '0-9a-zA-Z') . '_' . $fileUpload->getSanitizedName();
	}

}
