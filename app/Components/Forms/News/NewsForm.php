<?php

declare(strict_types=1);

namespace App\Components\Forms\News;

use ADT\Forms\Form;
use App\Components\Forms\Base\BaseForm;
use App\Model\Entity\File;
use App\Model\Entity\News;
use Nette\Http\FileUpload;

class NewsForm extends BaseForm
{
	protected ?News $item = null;

	public function initForm(Form $form): void
	{
		$form->addText('title', 'Titulek*')
			->setRequired('Vyplňte titulek');

		$form->addDate('publishedAt', 'Datum*')
			->setRequired('Vyplňte datum');

		$form->addTextArea('content', 'Text*')
			->setHtmlAttribute('class', 'editor')
			->setRequired('Vyplňte text');

		$form->addUpload('image', 'Obrázek')
			->addRule(\Nette\Forms\Form::MimeType, 'Lze nahrát pouze soubory typu: ' . implode(', ', static::$imageExtensions), static::$imageMimeTypes)
			->addRule(\Nette\Forms\Form::MaxFileSize, 'Maximální velikost nahrávaného souboru je 10 MB.', 10 * 1024 * 1024);

		$form->addCheckbox('published', 'Zveřejnit na webu');

		$form->addSubmit('submit', 'Uložit')
			->setAttribute('class', 'btn btn-dark border-radius-none');

		$form->setDefaults(
			$this->item && !$this->item->isNew()
				? [
					'title' => $this->item->getTitle(),
					'publishedAt' => $this->item->getPublishedAt(),
					'content' => $this->item->getContent(),
					'published' => $this->item->isPublished(),
				]
				: []
		);
	}

	public function processForm(array $values): void
	{
		if ($this->item && $this->item->isNew()) {
			$this->em->persist($this->item);
		}

		$this->item->setTitle($values['title']);
		$this->item->setSlug($values['title']);
		$this->item->setPublishedAt(\DateTimeImmutable::createFromInterface($values['publishedAt']));
		$this->item->setContent($values['content']);
		$this->item->setPublished($values['published']);

		/** @var FileUpload $image */
		if (($image = $values['image']) && $image->isOk()) {
			$imageEntity = $this->fileService->saveImage($image, 'n_' . $this->item->getSlug());

			if ($imageEntity instanceof File) {
				$this->item->getImage()?->setActive(false);
				$this->item->setImage($imageEntity);
			} elseif (is_string($imageEntity)) {
				$this->presenter->flashMessageError($imageEntity);
				$this->presenter->redirect('this');
			}
		}

		$this->em->flush();

		$this->presenter->flashMessageSuccess('Úspěšně uloženo');
		$this->presenter->redirect('default');
	}

	public function setItem(?News $item): NewsForm
	{
		$this->item = $item;
		return $this;
	}

	public function render(): void
	{
		$this->template->item = $this->item;
		parent::render();
	}

}
