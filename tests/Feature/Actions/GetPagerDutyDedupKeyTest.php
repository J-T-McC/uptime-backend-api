<?php

namespace Tests\Feature\Actions;

use App\Actions\GetPagerDutyDedupKey;
use App\Enums\Category;
use App\Models\Monitor;
use Tests\TestCase;

/**
 * @coversDefaultClass  \App\Actions\GetPagerDutyDedupKey
 */
class GetPagerDutyDedupKeyTest extends TestCase
{
    /**
     * @test
     *
     * @covers ::handle
     */
    public function it_returns_dedup_key_for_uptime_category()
    {
        // Collect
        $monitor = Monitor::factory()->createQuietly();
        $category = Category::UPTIME;

        // Act
        $dedupKey = (new GetPagerDutyDedupKey)->handle($monitor, $category);

        // Assert
        $this->assertSame('event-uptime-'.$monitor->hashId, $dedupKey);
    }

    /**
     * @test
     *
     * @covers ::handle
     */
    public function it_returns_dedup_key_for_certificate_category()
    {
        // Collect
        $monitor = Monitor::factory()->createQuietly();
        $category = Category::CERTIFICATE;

        // Act
        $dedupKey = (new GetPagerDutyDedupKey)->handle($monitor, $category);

        // Assert
        $this->assertSame('event-certificate-'.$monitor->hashId, $dedupKey);
    }
}
