<?php

namespace Iwgb\Media\Handler;

use Carbon;
use DateTime;
use Pimple\Container;
use Siler\http\Request;
use Siler\http\Response;
use Siler\Twig as Template;
use Twig;

abstract class ViewHandler extends RootHandler {

    protected Twig\Environment $view;

    protected Carbon\Factory $datetime;

    public function __construct(Container $c) {
        parent::__construct($c);

        $this->view = $c['view'];
        $this->datetime = $c['datetime'];
    }

    /**
     * {@inheritdoc}
     * @throws Twig\Error\LoaderError
     * @throws Twig\Error\RuntimeError
     * @throws Twig\Error\SyntaxError
     */
    abstract public function __invoke(array $args): void;

    /**
     * @param string $template
     * @param string $title
     * @param array  $data
     * @throws Twig\Error\LoaderError
     * @throws Twig\Error\RuntimeError
     * @throws Twig\Error\SyntaxError
     */
    protected function render(string $template, string $title, array $data = []) {

        $this->view->addFilter(new Twig\TwigFilter('timeAgo',
            fn(DateTime $datetime): string => $this->datetime->instance($datetime)->diffForHumans()
        ));

        $this->view->addGlobal('_get', Request\get());
        $this->view->addGlobal('cdnUrl', $this->settings['spaces']['cdnUrl']);
        $this->view->addGlobal('shortUrl', $this->settings['spaces']['shortUrl']);

        Response\html(Template\render($template, array_merge($data, ['title' => $title])));
    }


    protected function redirect(string $uri, array $queryData = []): void {

        $query = '';
        if (!empty($queryData)) {
            $query = '?' . http_build_query($queryData);
        }

        Response\redirect("{$uri}{$query}");
    }
}