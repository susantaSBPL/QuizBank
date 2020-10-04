<?php declare (strict_types = 1);

/*
 * (c) Kinetxx Inc <admin@kinetxx.com>
 */
namespace App\Services;

/**
 * Class UrlService
 */
class UrlInfo
{
    private string $verificationKey;
    private string $intervalString;

    /**
     * UrlInfo constructor.
     *
     * @param string $key
     * @param string $interval
     */
    public function __construct(string $key, string $interval)
    {
        $this->verificationKey = $key;
        $this->intervalString = $interval;
    }

    /**
     * @return string
     */
    public function getVerificationKey(): string
    {
        return $this->verificationKey;
    }

    /**
     * @return string
     */
    public function getIntervalString(): string
    {
        return $this->intervalString;
    }
}
