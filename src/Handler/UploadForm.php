<?php

namespace Iwgb\Media\Handler;

use voku\helper\UTF8;

class UploadForm extends ViewHandler {

    use SpacesActionTrait;

    /**
     * {@inheritdoc}
     */
    public function __invoke(array $args): void {

        $objects = $this->store->listObjects([
            'Bucket' => $this->settings['spaces']['bucket'],
            'Prefix' => $this->getRoot(),
        ])->toArray()['Contents'];

        $folders = [];
        foreach ($objects as $object) {
            if (substr($object['Key'], -1) === '/') {
                $folders[] = str_replace($this->getRoot(false), '', $object['Key']);
            }
        }

        usort($folders, fn(string $a, string $b): int => UTF8::strcasecmp($a, $b));

        $this->render('upload/upload.html.twig', 'Upload', [
            'folders' => $folders,
        ]);
    }
}