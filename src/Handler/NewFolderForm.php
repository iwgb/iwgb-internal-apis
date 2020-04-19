<?php

namespace Iwgb\Media\Handler;

class NewFolderForm extends RootHandler {

    use SpacesActionTrait;

    /**
     * {@inheritdoc}
     */
    public function __invoke(array $args): void {

        $path = base64_decode($args['path']);

        if (!$this->cdn->doesObjectExist($this->bucket, $path)) {
            $this->redirect("/{$this->getEncodedRoot()}/view", [
                'action' => 'newFolder',
                'status' => 'failed',
            ]);
            return;
        }

        $path = str_replace($this->getRoot(false), '', $path);

        $this->render('new-folder.html.twig', 'New folder', [
            'path'  => $path,
            'pathId'=> $args['path'],
        ]);
    }
}