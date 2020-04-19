<?php

namespace Iwgb\Media\Handler;

use Aws\Api\DateTimeResult;
use Exception;
use Guym4c\FontAwesomeFileIcons\FileIcons;
use Pimple\Container;
use ScriptFUSION\Byte\Base;
use ScriptFUSION\Byte\ByteFormatter;
use Twig\TwigFilter;
use Twig\TwigFunction;
use voku\helper\UTF8;

class View extends RootHandler {

    use SpacesActionTrait;

    private ByteFormatter $byteFormatter;

    public function __construct(Container $c) {
        parent::__construct($c);

        $this->byteFormatter = (new ByteFormatter())->setBase(Base::DECIMAL);
    }

    /**
     * {@inheritdoc}
     * @throws Exception
     */
    public function __invoke(array $args): void {

        if ($args['path'] == 'root') {
            $this->redirect("/{$this->getEncodedRoot()}/view");
            return;
        }

        $prefix = base64_decode($args['path']);

        $objects = $this->cdn->listObjects([
            'Bucket' => $this->settings['spaces']['bucket'],
            'Prefix' => $prefix,
        ])->toArray()['Contents'];

        $files = [];
        $folders = [];
        foreach ($objects as $object) {
            if ($object['Key'] !== $prefix
                && preg_match(
                    '/^' . str_replace('/', '\/', $prefix) . '[a-zA-Z0-9\-.]+\/?$/',
                    $object['Key']
                )
            ) {

                /** @var DateTimeResult $modified */
                $modified = $object['LastModified'];

                $parsedObject = [
                    'id'       => base64_encode($object['Key']),
                    'key'      => $object['Key'],
                    'name'     => str_replace($prefix, '', $object['Key']),
                    'modified' => $modified,
                    'size'     => $object['Size'],
                ];

                if (substr($object['Key'], -1) == '/') {
                    $folders[] = array_merge($parsedObject, ['name' => substr($parsedObject['name'], 0, -1)]);
                } else {
                    $files[] = $parsedObject;
                }
            }
        }

        $sortObjects = fn(array $a, array $b): int => UTF8::strcasecmp($a['name'], $b['name']);
        usort($folders, $sortObjects);
        usort($files, $sortObjects);

        $folderGroups = [];
        for ($i = 0; $i < count($folders); $i += 3) {
            $folderGroup = [];
            for ($j = 0; $j < 3; $j++) {
                $folderGroup[] = $folders[$i + $j] ?? null;
            }
            $folderGroups[] = $folderGroup;
        }

        $encodedParent = $prefix === $this->getRoot()
            ? null
            : base64_encode(substr($prefix, 0,
                strrpos($prefix, '/', -2) + 1
            ));

        $this->view->addFunction(new TwigFunction('getExtension', fn(string $s) => self::getExtension($s)));
        $this->view->addFunction(new TwigFunction('isImage', fn(string $s) => self::isImage($s)));
        $this->view->addFilter(new TwigFilter('bytes', fn (int $b) => $this->byteFormatter->format($b)));
        $this->view->addFilter(new TwigFilter('fileIcon', fn (string $name) => FileIcons::byFilename($name)));
        $this->view->addFilter(new TwigFilter('removeRoot', fn(string $key) => str_replace($this->getRoot(), '', $key)));

        $this->render('view/view.html.twig', 'Files', [
            'folder'       => str_replace($this->getRoot(false), '', $prefix),
            'files'        => $files,
            'folderGroups' => $folderGroups,
            'parent'       => $encodedParent,
            'encodedPath'  => $args['path'],
        ]);
    }
}