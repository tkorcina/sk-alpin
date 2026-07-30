<?php

namespace App\Components\Forms\Base;

use ADT\DoctrineForms\Entity;
use ADT\DoctrineForms\EntityFormMapper;
use ADT\DoctrineForms\Form;
use ADT\Files\Entities\IFileEntity;
use ADT\Utils\Translatable\TranslatableControlTrait;
use App\Model\Translator;
use Exception;
use Nette\Forms\Container;
use Nette\Forms\Controls;
use Nette\Forms\Controls\UploadControl;
use Nette\Http\FileUpload;
use ReflectionClass;

/**
 * @method Translator getTranslator()
 * @property Form $form
 */
class EntityForm extends Form
{
	use TranslatableControlTrait;

	public function addMultiSelect2(string $name, $label = null, ?array $items = null, ?int $size = null): Controls\MultiSelectBox
	{
		return $this->addMultiSelect($name, $label, $items, $size)->setHtmlAttribute('data-adt-select2');
	}

	public function addUpload(string $name, $label = null): UploadControl
	{
		$upload = parent::addUpload($name, $label);

		$this->setComponentFormMapper($upload, function (EntityFormMapper $mapper, UploadControl $upload, Entity $entity) {
			})
			->setComponentEntityMapper($upload, function (EntityFormMapper $mapper, UploadControl $upload, Entity $entity) {
				if (!$upload->isFilled()) {
					return;
				}

				$name = $upload->getName();

				$fileEntityName = null;
				$reflect = new ReflectionClass($entity);
				foreach ($reflect->getProperties() as $prop) {
					if ($prop->getName() == $name) {
						$fileEntityName = $prop->getType()?->getName();
						break;
					}
				}

				if (!$fileEntityName) {
					throw new Exception("Entity has now property '$name'");
				}

				$file = new $fileEntityName();
				if (! ($file instanceof IFileEntity)) {
					throw new Exception("Property '$name' is not instance of " . IFileEntity::class);
				}

				/** @var FileUpload $fileTmp */
				$fileTmp = $upload->getValue();
				if ($fileTmp->hasFile()) {
					$file->setTemporaryFile($fileTmp->getTemporaryFile(), $fileTmp->getUntrustedName());
					$this->entityManager->persist($file);
				} else {
					$file = null;
				}

				$setter = 'set' . ucfirst($name);
				$entity->$setter($file);
			});

		return $upload;
	}

	public function addTranslationBlock(callable $containerForm, string $blockName = 'translations'): void
	{
		$this->addTranslation($this->form, $blockName, function (Container $container) use ($containerForm) {
			$defaultLocale = $this->getTranslator()->getDefaultLocale();
			$locales = $this->getTranslator()->getAvailableLocales();
			$locales = array_combine($locales, $locales);

			if (isset($locales[$defaultLocale])) {
				unset($locales[$defaultLocale]);
			}

			$container->addSelect('locale', 'Language')
				->setRequired()
				->setPrompt('---')
				->setItems($locales);

			$containerForm($container);
		});
	}
}