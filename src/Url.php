<?php declare(strict_types=1);

namespace Ellipse\FastRoute;

class Url
{
    /**
     * The url path.
     *
     * @var \Ellipse\FastRoute\UrlPath
     */
    private $path;

    /**
     * The query parameters.
     *
     * @var array
     */
    private $query;

    /**
     * The fragment.
     *
     * @var string
     */
    private $fragment;

    /**
     * Set up an url with the given url path, query parameters and fragment.
     *
     * @param \Ellipse\FastRoute\UrlPath    $path
     * @param array                         $query
     * @param string                        $fragment
     */
    public function __construct(UrlPath $path, array $query = [], string $fragment = '')
    {
        $this->path = $path;
        $this->query = $query;
        $this->fragment = $fragment;
    }

    /**
     * Return a string representation of the url.
     *
     * @return string
     */
    public function value(): string
    {
        return $this->path->value() . $this->query() . $this->fragment();
    }

    /**
     * Return the string representation of the query parameters.
     *
     * @return string
     */
    private function query(): string
    {
        return (count($this->query) > 0)
            ? '?' . http_build_query($this->query)
            : '';
    }

    /**
     * Return the string representation of the fragment.
     *
     * @return string
     */
    private function fragment(): string
    {
        return ($this->fragment == '') ? '' : '#' . $this->fragment;
    }
}
