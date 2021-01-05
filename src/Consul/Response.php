<?php
namespace SwoStar\Consul;

/**
 *
 */
class Response
{

    /**
      * @var array
     */
    private $headers;

    /**
     * @var string
     */
    private $body;

    /**
     * @var int
     */
    private $status;

    public static function new(array $headers, string $body, int $status = 200): self
    {
        // $self = self::__instance();
        $self = app(Response::class);

        $self->body    = $body;
        $self->status  = $status;
        $self->headers = $headers;

        return $self;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->status;
    }

    /**
     * @return array|mixed
     */
    public function getResult()
    {
        if (empty($this->body)) {
            return $this->body;
        }

        return \json_decode($this->body, true);
        // return JsonHelper::decode($this->body, true);
    }

}
