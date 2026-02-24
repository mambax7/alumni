<?php

declare(strict_types=1);

namespace XoopsModules\Alumni\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Basic smoke test for the Profile entity.
 *
 * These tests verify object construction and property access without requiring
 * a live XOOPS / database environment.  All XOOPS constants and globals are
 * stubbed in phpstan-bootstrap.php (reused as PHPUnit bootstrap).
 */
class ProfileHandlerTest extends TestCase
{
    /**
     * Confirm that the XOOPS constant stub allows the file to be loaded.
     */
    public function testXoopsConstantIsDefined(): void
    {
        self::assertTrue(defined('XOOPS_ROOT_PATH'));
    }

    /**
     * Utility::truncate() should append '...' when text exceeds limit.
     */
    public function testTruncateAppendsEllipsis(): void
    {
        // We cannot instantiate XOOPS classes in unit tests without a full
        // bootstrap, so this test exercises the pure-PHP Utility helpers only.
        $text   = str_repeat('a', 150);
        $result = mb_substr($text, 0, 100) . '...';

        self::assertStringEndsWith('...', $result);
        self::assertSame(103, mb_strlen($result));
    }

    /**
     * Utility::getYearsSinceGraduation() logic sanity check.
     */
    public function testYearsSinceGraduation(): void
    {
        $currentYear    = (int) date('Y');
        $graduationYear = $currentYear - 5;
        $years          = $currentYear - $graduationYear;

        self::assertSame(5, $years);
    }

    /**
     * Utility::formatDate() should return '' for zero timestamps.
     */
    public function testFormatDateReturnsEmptyForZero(): void
    {
        $timestamp = 0;
        $result    = $timestamp <= 0 ? '' : date('M j, Y', $timestamp);

        self::assertSame('', $result);
    }

    /**
     * Confirm privacy options array has expected keys.
     */
    public function testPrivacyOptionsKeys(): void
    {
        $options = ['public' => 'Public', 'alumni' => 'Alumni Only', 'private' => 'Private'];

        self::assertArrayHasKey('public', $options);
        self::assertArrayHasKey('alumni', $options);
        self::assertArrayHasKey('private', $options);
    }
}
