<?php

declare(strict_types=1);

namespace App\Services;

use Closure;
use Detection\MobileDetect;

/**
 * Class Agent
 *
 * Extended wrapper around the MobileDetect library that:
 * - Detects platform (OS), browser, and device type from a User-Agent string.
 * - Adds support for merging custom detection rules via config.
 * - Provides caching for resolved values (platform, browser, desktop flag)
 *   to avoid recalculating on multiple calls.
 * - Includes a factory method (fromUserAgent) for analyzing arbitrary
 *   User-Agent strings, e.g., from database-stored sessions or logs.
 *
 * Example usage:
 * ```php
 * $agent = Agent::fromUserAgent($uaString);
 * echo $agent->browser();    // "Chrome"
 * echo $agent->platform();   // "Windows"
 * var_dump($agent->isDesktop()); // true
 * ```
 */
class Agent extends MobileDetect
{
    /**
     * Cache store for resolved values like platform, browser, or desktop flag.
     * This avoids recalculating the same detection multiple times.
     *
     * @var array<string, mixed>
     */
    protected array $store = [];

    // Cache keys for storing resolved values
    private const CACHE_PLATFORM = 'agent.platform';
    private const CACHE_BROWSER  = 'agent.browser';
    private const CACHE_DESKTOP  = 'agent.desktop';

    /**
     * Detect the platform (e.g., Windows, macOS, Android, iOS).
     * Uses cache to avoid repeated computation.
     *
     * @example
     * ```php
     * $agent = Agent::fromUserAgent($uaString);
     * echo $agent->platform(); // "Windows"
     * ```
     */
    public function platform(): ?string
    {
        return $this->retrieveUsingCacheOrResolve(self::CACHE_PLATFORM, function (): ?string {
            return $this->findDetectionRulesAgainstUserAgent(
                $this->mergeRules(
                    // Merge built-in MobileDetect OS rules with custom ones from config
                    MobileDetect::getOperatingSystems(),
                    config('agent.operating_systems', [])
                )
            );
        });
    }

    /**
     * Detect the browser (e.g., Chrome, Firefox, Safari).
     * Uses cache to avoid repeated computation.
     *
     * @example
     * ```php
     * $agent = Agent::fromUserAgent($uaString);
     * echo $agent->browser(); // "Firefox"
     * ```
     */
    public function browser(): ?string
    {
        return $this->retrieveUsingCacheOrResolve(self::CACHE_BROWSER, function (): ?string {
            return $this->findDetectionRulesAgainstUserAgent(
                $this->mergeRules(
                    // Merge custom browser rules from config with MobileDetect defaults
                    config('agent.browsers', []),
                    MobileDetect::getBrowsers()
                )
            );
        });
    }

    /**
     * Determine if the current device is a desktop.
     * - Returns true if it's explicitly detected as a CloudFront desktop viewer.
     * - Otherwise, true if not mobile and not tablet.
     * Uses cache to avoid repeated computation.
     *
     * @example
     * ```php
     * $agent = Agent::fromUserAgent($uaString);
     * var_dump($agent->isDesktop()); // true
     * ```
     */
    public function isDesktop(): bool
    {
        return $this->retrieveUsingCacheOrResolve(self::CACHE_DESKTOP, function (): bool {
            if (
                $this->getUserAgent() === static::$cloudFrontUA
                && $this->getHttpHeader('HTTP_CLOUDFRONT_IS_DESKTOP_VIEWER') === 'true'
            ) {
                return true;
            }

            return ! $this->isMobile() && ! $this->isTablet();
        });
    }

    /**
     * Run detection rules against the current User-Agent string.
     * Returns the first matching key (e.g., "Windows", "Chrome") or null if none matched.
     *
     * @example
     * ```php
     * $agent = Agent::fromUserAgent("Mozilla/5.0 ... Chrome/100.0");
     * echo $agent->browser(); // "Chrome"
     * ```
     */
    protected function findDetectionRulesAgainstUserAgent(array $rules): ?string
    {
        $userAgent = $this->getUserAgent();

        foreach ($rules as $key => $regex) {
            if (empty($regex)) {
                continue;
            }

            if ($this->match($regex, $userAgent)) {
                // Return the matched key (name of platform/browser)
                // or fallback to the first match value
                return $key ?: reset($this->matchesArray);
            }
        }

        return null;
    }

    /**
     * Retrieve a value from cache if it exists, otherwise resolve it using callback.
     * Stores the resolved result for future use.
     *
     * @example
     * ```php
     * $result = $this->retrieveUsingCacheOrResolve('key', fn() => 'computed');
     * ```
     */
    protected function retrieveUsingCacheOrResolve(string $key, callable $callback): mixed
    {
        if (array_key_exists($key, $this->store)) {
            return $this->store[$key];
        }

        return tap($callback(), fn($result) => $this->store[$key] = $result);
    }

    /**
     * Merge multiple rule arrays into one.
     * - If a key doesn't exist, add it.
     * - If it exists and is an array, append.
     * - If it exists and is a string, concatenate with "|" (regex OR).
     *
     * @param  array<string, string>  ...$all
     * @return array<string, string>
     *
     * @example
     * ```php
     * $merged = $this->mergeRules(['Windows' => 'Win'], ['Linux' => 'Linux']);
     * ```
     */
    protected function mergeRules(array ...$all): array
    {
        $merged = [];

        foreach ($all as $rules) {
            foreach ($rules as $key => $value) {
                if (empty($merged[$key])) {
                    $merged[$key] = $value;
                } elseif (is_array($merged[$key])) {
                    $merged[$key][] = $value;
                } else {
                    $merged[$key] .= '|' . $value;
                }
            }
        }

        return $merged;
    }

    /**
     * Factory method to create an Agent instance from a given User-Agent string.
     * Useful for parsing stored user agents from sessions or logs.
     *
     * @example
     * ```php
     * $agent = Agent::fromUserAgent("Mozilla/5.0 ... Firefox/100.0");
     * echo $agent->browser(); // "Firefox"
     * ```
     */
    public static function fromUserAgent(?string $ua): self
    {
        $agent = new self();
        $agent->setUserAgent($ua);
        return $agent;
    }
}
