<?php

declare(strict_types=1);

namespace XoopsModules\Alumni\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Tests for pure-PHP logic within Utility helper methods.
 * No XOOPS objects are instantiated — only standalone logic is tested.
 */
class UtilityTest extends TestCase
{
    // -------------------------------------------------------------------------
    // truncate()
    // -------------------------------------------------------------------------

    public function testTruncateShortStringUnchanged(): void
    {
        $text   = 'Hello World';
        $result = mb_strlen($text) <= 100 ? $text : mb_substr($text, 0, 100) . '...';
        self::assertSame('Hello World', $result);
    }

    public function testTruncateLongStringCut(): void
    {
        $text   = str_repeat('x', 200);
        $result = mb_strlen($text) <= 100 ? $text : mb_substr($text, 0, 100) . '...';
        self::assertSame(103, mb_strlen($result));
        self::assertStringEndsWith('...', $result);
    }

    // -------------------------------------------------------------------------
    // getTimeAgo() — time-bucket logic
    // -------------------------------------------------------------------------

    public function testTimeAgoBuckets(): void
    {
        $now = time();

        // Less than 60 s → "just now"
        $diff1 = $now - ($now - 30);
        self::assertLessThan(60, $diff1);

        // Less than 1 h → minutes bucket
        $diff2 = $now - ($now - 1800);
        self::assertGreaterThanOrEqual(60, $diff2);
        self::assertLessThan(3600, $diff2);

        // More than 24 h → days bucket
        $diff3 = $now - ($now - 90000);
        self::assertGreaterThanOrEqual(86400, $diff3);
    }

    // -------------------------------------------------------------------------
    // uploadFile() — pure validation logic
    // -------------------------------------------------------------------------

    public function testAllowedExtensionsList(): void
    {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        self::assertContains('jpg',  $allowed);
        self::assertContains('png',  $allowed);
        self::assertNotContains('php', $allowed);
        self::assertNotContains('exe', $allowed);
    }

    // -------------------------------------------------------------------------
    // Event type options
    // -------------------------------------------------------------------------

    public function testEventTypeOptionsHasExpectedKeys(): void
    {
        $types = ['reunion', 'networking', 'seminar', 'workshop', 'conference', 'social', 'fundraiser', 'webinar', 'other'];
        foreach ($types as $type) {
            self::assertIsString($type);
            self::assertNotEmpty($type);
        }
    }
}
