<?php namespace Codecycler\Ray\Classes\Payloads;

use Spatie\Ray\Payloads\Payload;
use Spatie\Ray\ArgumentConverter;

class ModelPayload extends Payload
{
    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function getType(): string
    {
        return 'eloquent_model';
    }

    public function getContent(): array
    {
        if (!$this->model) {
            return [];
        }

        $content = [
            'class_name' => get_class($this->model),
            'attributes' => ArgumentConverter::convertToPrimitive($this->model->attributesToArray()),
        ];

        $relations = $this->model->relationsToArray();

        if (count($relations)) {
            $content['relations'] = ArgumentConverter::convertToPrimitive($relations);
        }

        return $content;
    }
}