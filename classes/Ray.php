<?php namespace Codecycler\Ray\Classes;

use Spatie\Ray\Client;
use Spatie\Ray\Ray as BaseRay;
use October\Rain\Database\Model;
use Spatie\Ray\Settings\Settings;
use Codecycler\Ray\Classes\Payloads\ModelPayload;

class Ray extends BaseRay
{
    public function __construct(Settings $settings, Client $client = null, string $uuid = null) {
        $enabled = static::$enabled;
        parent::__construct($settings, $client, $uuid);
        static::$enabled = $enabled;
    }

    public function model(...$model): self
    {
        $models = [];

        foreach ($model as $passedModel) {
            if (is_null($passedModel)) {
                $models[] = null;
                continue;
            }

            if ($passedModel instanceof Model) {
                $models[] = $passedModel;
                continue;
            }

            if (is_iterable($model)) {
                foreach($passedModel as $item) {
                    $models[] = $item;
                    continue;
                }
            }
        }

        $payloads = array_map(function ($model) {
            return new ModelPayload($model);
        }, $models);

        foreach ($payloads as $payload) {
            ocray()->sendRequest($payload);
        }

        return $this;
    }
}