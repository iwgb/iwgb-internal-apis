<?php

namespace Iwgb\Media\Handler;

use Pimple\Container;
use Psr\Http\Message\ServerRequestInterface;

class NewFolder extends RootHandler {

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

        $form = $this->request->getParsedBody();

        if (empty($form['name'])
            || empty($form['path'])
            || !preg_match(self::$NAME_REGEX, $form['name'])
        ) {
            $this->redirect("/{$this->getEncodedRoot()}/view", [
                'action' => 'newFolder',
                'status' => 'failed',
            ]);
            return;
        }

        $parent = $this->getRoot(false) . $form['path'];

        if (!$this->cdn->doesObjectExist($this->bucket, $parent)) {
            $this->redirect("/{$this->getEncodedRoot()}/view", [
                'action' => 'newFolder',
                'status' => 'failed',
            ]);
            return;
        }

        $path = "{$parent}{$form['name']}/";

        $this->cdn->putObject([
            'Bucket' => $this->settings['spaces']['bucket'],
            'Key'    => $path,
            'ACL'    => 'public-read',
        ]);

        $this->redirect('/' . base64_encode($path) . '/view', [
            'action' => 'newFolder',
            'status' => 'success',
        ]);
    }
}