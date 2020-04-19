<?php

namespace Iwgb\Media\Handler;

use Pimple\Container;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use Siler\Http\Request;

class Upload extends RootHandler {

    use SpacesActionTrait;

    private ServerRequestInterface $request;

    public function __construct(Container $c) {
        parent::__construct($c);

        $this->request = $c['request'];
    }

    /**
     * {@inheritdoc}
     */
    public function __invoke(array $args): void {

        /** @var UploadedFileInterface $file */
        $file = $this->request->getUploadedFiles()['file'];
        $form = $this->request->getParsedBody();
        $generate = !empty($form['generate']);

        if (empty($file)
            || $file->getError() != UPLOAD_ERR_OK
            || empty($form['folder'])
            || (
                !$generate
                && empty($form['filename'])
            )
        ) {
            $this->redirect("/{$this->getEncodedRoot()}/view", [
                'action' => 'upload',
                'status' => 'failed',
            ]);
            return;
        }

        $extension = pathinfo($file->getClientFilename(), PATHINFO_EXTENSION);
        $generatedName = uniqid() . '.' . $extension;
        $path = $this->getRoot(false) . $form['folder'];
        $name = $generate
            ? $generatedName
            : "{$form['filename']}.{$extension}";

        if (!preg_match(self::$FILE_NAME_REGEX, $name)) {
            $this->redirect('/upload', [
                'action' => 'upload',
                'status' => 'failed',
            ]);
            return;
        }

        $file->moveTo(APP_ROOT . '/var/upload/' . $generatedName);

        $this->cdn->putObject([
            'Bucket' => $this->settings['spaces']['bucket'],
            'Key' => $path . $name,
            'ACL' => 'public-read',
            'ContentType' => $file->getClientMediaType(),
            'SourceFile' => APP_ROOT . '/var/upload/' . $generatedName,
        ]);

        unlink('/var/upload/' . $generatedName);

        $this->redirect('/' . base64_encode($path) . '/view', [
            'action' => 'upload',
            'status' => 'success',
        ]);
    }
}