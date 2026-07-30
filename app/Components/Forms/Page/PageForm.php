<?php

declare(strict_types=1);

namespace App\Components\Forms\Page;

use ADT\Forms\Form;
use App\Components\Forms\Base\BaseForm;
use App\Model\Entity\File;
use App\Model\Entity\Page;
use Nette\Http\FileUpload;

class PageForm extends BaseForm
{
	protected ?Page $item = null;

	public function initForm(Form $form): void
	{
		$form->addText('title', 'Název*')
			->setRequired('Vyplňte název');

		$form->addTextArea('content', 'Text stránky*')
			->setHtmlAttribute('class', 'editor')
			->setRequired('Vyplňte text');

		$form->addUpload('image', 'Obrázek')
			->addRule(\Nette\Forms\Form::MimeType, 'Lze nahrát pouze soubory typu: ' . implode(', ', static::$imageExtensions), static::$imageMimeTypes)
			->addRule(\Nette\Forms\Form::MaxFileSize, 'Maximální velikost nahrávaného souboru je 10 MB.', 10 * 1024 * 1024);

		$form->addSubmit('submit', 'Uložit')
			->setAttribute('class', 'btn btn-dark border-radius-none');

		$form->setDefaults(
			$this->item
				? [
					'title' => $this->item->getTitle(),
					'content' => $this->item->getContent(),
				]
				: []
		);
	}

	public function processForm(array $values): void
	{
		$this->item->setTitle($values['title']);
		$this->item->setContent($values['content']);

		/** @var FileUpload $image */
		if (($image = $values['image']) && $image->isOk()) {
			$imageEntity = $this->fileService->saveImage($image, 'p_' . $this->item->getInternalName());

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

	public function setItem(?Page $item): PageForm
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
