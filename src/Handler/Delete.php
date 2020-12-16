<?php

namespace Iwgb\Media\Handler;

class Delete extends ViewHandler {

    use SpacesActionTrait;

    /**
     * {@inheritdoc}
     */
    public function __invoke(array $args): void {

        $path = base64_decode($args['path']);

        if (!$this->store->doesObjectExist($this->bucket, $path)) {
            $this->redirect("/{$this->getEncodedRoot()}/view", [
                'action' => 'delete',
                'status' => 'failed',
            ]);
            return;
        }

        $parent = substr($path, 0,
            strrpos($path, '/', -2) + 1
        );

        $this->store->deleteObject([
            'Bucket' => $this->bucket,
            'Key'    => $path,
        ]);

        $this->redirect('/' . base64_encode($parent) . '/view', [
            'action' => 'delete',
            'status' => 'success',
        ]);
    }
}