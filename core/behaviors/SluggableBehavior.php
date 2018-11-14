<?php

namespace core\behaviors;


use yii\helpers\ArrayHelper;
use Zelenin\yii\behaviors\Slug;

class SluggableBehavior extends Slug
{
    public $slugLength = 100;
    public $transliterateOptions = 'Russian-Latin/BGN; Any-Latin; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFC;';

    /**
     * {@inheritdoc}
     */
    protected function getValue($event)
    {
        if (!$this->isNewSlugNeeded()) {
            return $this->owner->{$this->slugAttribute};
        }

        if ($this->attribute !== null) {
            $slugParts = [];
            foreach ((array) $this->attribute as $attribute) {
                $part = ArrayHelper::getValue($this->owner, $attribute);
                if ($this->skipOnEmpty && $this->isEmpty($part)) {
                    return $this->owner->{$this->slugAttribute};
                }
                $slugParts[] = $part;
            }
            $slug = substr($this->generateSlug($slugParts), 0, $this->slugLength);
            if (substr($slug, -1) == '-') {
                $slug = substr($slug, 0, -1);
            }
        } else {
            $slug = parent::getValue($event);
        }

        return $this->ensureUnique ? $this->makeUnique($slug) : $slug;
    }

    /*protected function generateSlug($slugParts)
    {
        return
    }*/
}