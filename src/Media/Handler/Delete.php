<?php

namespace Iwgb\Internal\Media\Handler;

use GuzzleHttp;
use GuzzleHttp\Exception\GuzzleException;
use Iwgb\Internal\HttpCompatibleException;
use Iwgb\Internal\Provider\SpacesCdnProvider as S3;
use Pimple\Container;
use Siler\Http\Response;

class Delete extends AbstractApiHandler {

    private const KEYCDN_PURGE_BASE_URL = 'https://api.keycdn.com/zones/purgeurl/';

    private GuzzleHttp\Client $http;

    public function __construct(Container $c) {
        parent::__construct($c);

        $this->http = new GuzzleHttp\Client();
    }

    /**
     * {@inheritdoc}
     * @throws HttpCompatibleException
     * @throws GuzzleException
     */
    public function __invoke(array $args): void {
        $this->authorise();

        $key = S3::sanitiseKey(base64_decode($args['id'] ?? ''));

        $this->validateObjectKey($key, true);

        $this->store->deleteObject([
            'Bucket' => $this->bucket,
            'Key' => $key,
        ]);

        $this->purgeCacheByKey($key);

        self::withCors();
        Response\json(['id' => $args['id']]);
    }

    /**
     * @param string $key
     * @throws GuzzleException
     */
    private function purgeCacheByKey(string $key): void {
        $this->http->delete(self::KEYCDN_PURGE_BASE_URL . "{$this->settings['spaces']['cdn']['zoneId']}.json", [
            'auth' => [$this->settings['spaces']['cdn']['apiKey']],
            'headers' => ['content-type' => 'application/json'],
            'json' => ['urls' => ["{$this->settings['spaces']['cdn']['zoneHost']}/{$key}"]],
        ]);
    }
}