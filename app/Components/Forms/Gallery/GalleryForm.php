<?php

declare(strict_types=1);

namespace App\Components\Forms\Gallery;

use ADT\Forms\Form;
use App\Components\Forms\Base\BaseForm;
use App\Model\Entity\File;
use App\Model\Entity\Gallery;
use App\Model\Entity\Photo;
use App\Model\Query\EventQueryFactory;
use Kdyby\Autowired\Attributes\Autowire;
use Nette\Http\FileUpload;

class GalleryForm extends BaseForm
{
	protected ?Gallery $gallery = null;

	#[Autowire]
	protected EventQueryFactory $eventQueryFactory;

	public function initForm(Form $form): void
	{
		$form->addText('title', 'Název*')
			->setRequired('Vyplňte název');

		$form->addDate('date', 'Datum')
			->setOption('description', 'nepovinné')
			->setRequired(false);

		$form->addSelect('event', 'Akce', $this->eventQueryFactory->create()->fetchPairs('title'))
			->setPrompt('— bez akce —')
			->setRequired(false);

		$form->addMultiUpload('photos', 'Fotografie')
			->addRule(\Nette\Forms\Form::MimeType, 'Lze nahrát pouze soubory typu: ' . implode(', ', static::$imageExtensions), static::$imageMimeTypes)
			->addRule(\Nette\Forms\Form::MaxFileSize, 'Maximální velikost nahrávaného souboru je 10 MB.', 10 * 1024 * 1024);

		$form->addCheckbox('published', 'Zveřejnit na webu');

		$form->addSubmit('submit', 'Uložit')
			->setAttribute('class', 'btn btn-dark border-radius-none');

		$form->setDefaults(
			$this->gallery && !$this->gallery->isNew()
				? [
					'title' => $this->gallery->getTitle(),
					'date' => $this->gallery->getDate(),
					'event' => $this->gallery->getEvent()?->getId(),
					'published' => $this->gallery->isPublished(),
				]
				: []
		);
	}

	public function processForm(array $values): void
	{
		if ($this->gallery && $this->gallery->isNew()) {
			$this->em->persist($this->gallery);
		}

		$this->gallery->setTitle($values['title']);
		$this->gallery->setSlug($values['title']);
		$this->gallery->setDate($values['date'] ? \DateTimeImmutable::createFromInterface($values['date']) : null);
		$this->gallery->setEvent($values['event'] ? $this->eventQueryFactory->create()->byId($values['event'])->fetchOneOrNull() : null);
		$this->gallery->setPublished($values['published']);

		$sort = count($this->gallery->getPhotos());

		/** @var FileUpload $photoUpload */
		foreach ($values['photos'] as $photoUpload) {
			if ($photoUpload && $photoUpload->isOk()) {
				$file = $this->fileService->saveImage($photoUpload, 'g_' . $this->gallery->getSlug());

				if ($file instanceof File) {
					$photo = new Photo($this->gallery, $file);
					$photo->setSort($sort++);
					$this->gallery->addPhoto($photo);
				} elseif (is_string($file)) {
					$this->presenter->flashMessageError($file);
					$this->presenter->redirect('this');
				}
			}
		}

		if (!$this->gallery->getPhotos() && $this->gallery->isPublished()) {
			$this->gallery->setPublished(false);
			$this->presenter->flashMessageWarning('Nelze zveřejnit prázdnou galerii, přidejte alespoň jednu fotografii.');
		}

		$this->em->flush();

		$this->presenter->flashMessageSuccess('Úspěšně uloženo');
		$this->presenter->redirect('default');
	}

	public function setGallery(?Gallery $gallery): GalleryForm
	{
		$this->gallery = $gallery;
		return $this;
	}

	public function render(): void
	{
		$this->template->gallery = $this->gallery;
		parent::render();
	}

}
